<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

/**
 * HomeController
 * Xử lý trang chủ và các tính năng công khai
 */
class HomeController extends BaseController
{
    protected $postModel;
    protected $categoryModel;
    protected $commentModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->commentModel = new Comment();
    }
    
    /**
     * Trang chủ
     */
    public function index()
    {
        // Get website settings
        $settings = $this->getWebsiteSettings();
        
        // Get categories with posts count
        $categories = $this->categoryModel->getWithPostsCount();
        
        // Handle AJAX search
        if ($this->getInput('ajax') == '1' && $this->getInput('search')) {
            return $this->handleAjaxSearch();
        }
        
        // Handle search
        $searchTerm = $this->getInput('search', '');
        if (!empty($searchTerm)) {
            $posts = $this->postModel->search($searchTerm, 50);
            $isSearchPage = true;
        } else {
            $posts = $this->postModel->getWithRelations(200);
            $isSearchPage = false;
        }
        
        // Get featured content
        $topSelectedPosts = $this->getTopSelectedPosts();
        $breakingNews = $this->getBreakingNews();
        $mostViewedPosts = $this->postModel->getPopular(6);
        
        return $this->render('home.index', [
            'settings' => $settings,
            'categories' => $categories,
            'lastPosts' => $posts,
            'topSelectedPosts' => $topSelectedPosts,
            'breakingNews' => $breakingNews,
            'mostViewedPosts' => $mostViewedPosts,
            'searchTerm' => $searchTerm,
            'isSearchPage' => $isSearchPage
        ]);
    }
    
    /**
     * Hiển thị bài viết
     */
    public function showPost($id)
    {
        // Increment view count
        $this->postModel->incrementView($id);
        
        // Get post with full details
        $post = $this->postModel->getWithFullDetails($id);
        
        if (!$post) {
            return $this->render('errors.404');
        }
        
        // Get related posts from same category
        $relatedPosts = $this->postModel->getByCategory($post['cat_id'], $id, 3);
        
        // If not enough related posts, get popular posts
        if (count($relatedPosts) < 3) {
            $additionalPosts = $this->postModel->getPopular(3);
            $relatedPosts = array_merge($relatedPosts, $additionalPosts);
            $relatedPosts = array_slice($relatedPosts, 0, 3);
        }
        
        // Get comments for this post
        $comments = $this->commentModel->getByPost($id);
        $commentsCount = count($comments);
        
        // Get popular posts for sidebar
        $popularPosts = $this->postModel->getPopular(5);
        
        return $this->render('home.show-post', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'comments' => $comments,
            'commentsCount' => $commentsCount,
            'popularPosts' => $popularPosts
        ]);
    }
    
    /**
     * Hiển thị category
     */
    public function showCategory($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return $this->render('errors.404');
        }
        
        // Get posts in this category with pagination
        $page = $this->getInput('page', 1);
        $posts = $this->categoryModel->getPostsPaginated($id, $page, 12);
        $totalPosts = $this->categoryModel->countPosts($id);
        $totalPages = ceil($totalPosts / 12);
        
        return $this->render('home.show-category', [
            'category' => $category,
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
        ]);
    }
    
    /**
     * Lưu comment
     */
    public function commentStore()
    {
        $this->requireAuth();
        
        $input = $this->getInput();
        
        // Validate input
        $errors = $this->validate($input, [
            'post_id' => 'required',
            'comment' => 'required|min:3|max:1000'
        ]);
        
        if (!empty($errors)) {
            return $this->redirectBack('Please check your input.', 'error');
        }
        
        // Create comment with toxic detection
        $commentData = [
            'user_id' => $_SESSION['user'],
            'post_id' => $input['post_id'],
            'comment' => $this->sanitize($input['comment'])
        ];
        
        $result = $this->commentModel->createWithToxicCheck($commentData);
        
        if ($result) {
            return $this->redirectBack('Your comment has been submitted for review.', 'success');
        } else {
            return $this->redirectBack('Failed to submit comment. Please try again.', 'error');
        }
    }
    
    /**
     * AJAX search handler
     */
    protected function handleAjaxSearch()
    {
        $searchTerm = $this->getInput('search');
        $results = $this->postModel->search($searchTerm, 10);
        
        // Format results for AJAX response
        $formattedResults = array_map(function($post) {
            return [
                'id' => $post['id'],
                'title' => $post['title'],
                'url' => url('show-post/' . $post['id']),
                'category' => $post['category_name'] ?? 'Uncategorized'
            ];
        }, $results);
        
        return $this->json($formattedResults);
    }
    
    /**
     * Get website settings
     */
    protected function getWebsiteSettings()
    {
        $result = $this->postModel->query("SELECT * FROM websetting");
        return $result ? $result->fetch() : null;
    }
    
    /**
     * Get top selected posts
     */
    protected function getTopSelectedPosts()
    {
        $sql = "SELECT p.*, u.username, c.name as category_name,
                       COUNT(cm.id) as comments_count
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                LEFT JOIN comments cm ON p.id = cm.post_id AND cm.status = 'approved'
                WHERE p.selected = 2 AND p.status = 1 
                GROUP BY p.id
                ORDER BY p.created_at DESC 
                LIMIT 3";
        
        $result = $this->postModel->query($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get breaking news
     */
    protected function getBreakingNews()
    {
        $sql = "SELECT * FROM posts WHERE breaking_news = 2 AND status = 1 ORDER BY created_at DESC LIMIT 1";
        $result = $this->postModel->query($sql);
        return $result ? $result->fetch() : null;
    }
}