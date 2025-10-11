<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;

/**
 * AdminDashboardController
 * Xử lý dashboard admin với analytics
 */
class DashboardController extends BaseController
{
    protected $postModel;
    protected $userModel;
    protected $commentModel;
    protected $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->postModel = new Post();
        $this->userModel = new User();
        $this->commentModel = new Comment();
        $this->categoryModel = new Category();
    }
    
    /**
     * Dashboard chính
     */
    public function index()
    {
        // Get statistics
        $postStats = $this->postModel->getCountByStatus();
        $userStats = $this->userModel->getStatistics();
        $commentStats = $this->commentModel->getStatistics();
        $categoryStats = $this->categoryModel->getStatistics();
        
        // Get view analytics data (7 days)
        $viewData = $this->getViewAnalytics();
        
        // Get recent activity
        $recentPosts = $this->postModel->getRecent(5);
        $recentComments = $this->commentModel->getRecent(5);
        $topCommentedPosts = $this->getTopCommentedPosts();
        
        // Get total views for dashboard
        $postsViews = $this->postModel->getTotalViews();
        
        // Get posts with view count for table
        $postsWithView = $this->postModel->getPostsWithViews(10);
        
        return $this->render('admin.dashboard.index', [
            'postStats' => $postStats,
            'userStats' => $userStats,
            'commentStats' => $commentStats,
            'categoryStats' => $categoryStats,
            'viewData' => $viewData,
            'recentPosts' => $recentPosts,
            'recentComments' => $recentComments,
            'topCommentedPosts' => $topCommentedPosts,
            'postsViews' => $postsViews,
            'postsWithView' => $postsWithView
        ]);
    }
    
    /**
     * Get view analytics for chart
     */
    protected function getViewAnalytics()
    {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as posts_count,
                    COALESCE(SUM(view), 0) as total_views
                FROM posts 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        
        $result = $this->postModel->query($sql);
        $data = $result ? $result->fetchAll() : [];
        
        // Fill missing dates with zero values
        $analyticsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $found = false;
            
            foreach ($data as $row) {
                if ($row['date'] === $date) {
                    $analyticsData[] = [
                        'date' => $date,
                        'posts' => (int)$row['posts_count'],
                        'views' => (int)$row['total_views']
                    ];
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $analyticsData[] = [
                    'date' => $date,
                    'posts' => 0,
                    'views' => 0
                ];
            }
        }
        
        return $analyticsData;
    }
    
    /**
     * Get top commented posts
     */
    protected function getTopCommentedPosts()
    {
        $sql = "SELECT p.id, p.title, COUNT(c.id) as comment_count
                FROM posts p 
                LEFT JOIN comments c ON p.id = c.post_id 
                GROUP BY p.id 
                ORDER BY comment_count DESC 
                LIMIT 5";
        
        $result = $this->postModel->query($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get system info
     */
    public function systemInfo()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'disk_free_space' => $this->formatBytes(disk_free_space('.')),
            'disk_total_space' => $this->formatBytes(disk_total_space('.'))
        ];
        
        return $this->render('admin.dashboard.system-info', [
            'systemInfo' => $systemInfo
        ]);
    }
    
    /**
     * Get database version
     */
    protected function getDatabaseVersion()
    {
        try {
            $result = $this->postModel->query("SELECT VERSION() as version");
            $version = $result ? $result->fetch() : null;
            return $version ? $version['version'] : 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
    
    /**
     * Format bytes to human readable
     */
    protected function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Analytics API endpoint
     */
    public function analyticsApi()
    {
        $type = $this->getInput('type', 'views');
        $days = $this->getInput('days', 7);
        
        switch ($type) {
            case 'views':
                $data = $this->getViewAnalytics();
                break;
            case 'comments':
                $data = $this->getCommentAnalytics($days);
                break;
            case 'users':
                $data = $this->getUserAnalytics($days);
                break;
            default:
                $data = [];
        }
        
        return $this->json(['data' => $data]);
    }
    
    /**
     * Get comment analytics
     */
    protected function getCommentAnalytics($days = 7)
    {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as comment_count,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN status = 'unseen' THEN 1 ELSE 0 END) as pending_count
                FROM comments 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        
        $result = $this->commentModel->query($sql, [$days]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get user analytics
     */
    protected function getUserAnalytics($days = 7)
    {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as new_users
                FROM users 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        
        $result = $this->userModel->query($sql, [$days]);
        return $result ? $result->fetchAll() : [];
    }
}