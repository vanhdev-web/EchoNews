<?php

namespace App\Models;

use App\Core\BaseModel;

/**
 * Post Model
 * Quản lý posts/articles
 */
class Post extends BaseModel
{
    protected $table = 'posts';
    protected $fillable = [
        'user_id', 'cat_id', 'title', 'summary', 
        'body', 'image', 'status', 'view', 'selected',
        'breaking_news', 'published_at'
    ];
    
    /**
     * Get posts with category and user info
     */
    public function getWithRelations($limit = null)
    {
        $sql = "SELECT p.*, u.username, c.name as category_name, c.id as category_id
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                WHERE p.status = 1 
                ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get post with full details
     */
    public function getWithFullDetails($id)
    {
        $sql = "SELECT p.*, u.username, u.email, c.name as category_name, c.id as category_id
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                WHERE p.id = ? AND p.status = 1";
        
        $result = $this->db->select($sql, [$id]);
        return $result ? $result->fetch() : null;
    }
    
    /**
     * Get posts by category
     */
    public function getByCategory($categoryId, $excludeId = null, $limit = 3)
    {
        $sql = "SELECT p.*, u.username, c.name as category_name
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                WHERE p.cat_id = ? AND p.status = 1";
        
        $params = [$categoryId];
        
        if ($excludeId) {
            $sql .= " AND p.id != ?";
            $params[] = $excludeId;
        }
        
        $sql .= " ORDER BY p.view DESC, p.created_at DESC LIMIT " . (int)$limit;
        
        $result = $this->db->select($sql, $params);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get popular posts
     */
    public function getPopular($limit = 5)
    {
        $sql = "SELECT p.*, u.username, c.name as category_name,
                       COUNT(cm.id) as comments_count
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                LEFT JOIN comments cm ON p.id = cm.post_id AND cm.status = 'approved'
                WHERE p.status = 1 
                GROUP BY p.id
                ORDER BY p.view DESC, comments_count DESC, p.created_at DESC 
                LIMIT " . (int)$limit;
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Search posts
     */
    public function search($query, $limit = 10)
    {
        $sql = "SELECT p.*, u.username, c.name as category_name
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                WHERE (p.title LIKE ? OR p.summary LIKE ? OR p.body LIKE ?) 
                AND p.status = 1
                ORDER BY p.created_at DESC 
                LIMIT " . (int)$limit;
        
        $searchTerm = '%' . $query . '%';
        $result = $this->db->select($sql, [$searchTerm, $searchTerm, $searchTerm]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Increment view count
     */
    public function incrementView($id)
    {
        $sql = "UPDATE posts SET view = IFNULL(view, 0) + 1 WHERE id = ?";
        return $this->db->select($sql, [$id]);
    }
    
    /**
     * Get posts count by status
     */
    public function getCountByStatus()
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'enable' THEN 1 ELSE 0 END) as published,
                    SUM(CASE WHEN status = 'disable' THEN 1 ELSE 0 END) as draft
                FROM posts";
        
        $result = $this->db->select($sql);
        return $result ? $result->fetch() : [];
    }
    
    /**
     * Get recent posts for admin
     */
    public function getRecent($limit = 5)
    {
        $sql = "SELECT p.*, u.username, c.name as category_name
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                ORDER BY p.created_at DESC 
                LIMIT " . (int)$limit;
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get all posts for dropdowns
     */
    public function getAll()
    {
        $sql = "SELECT id, title FROM posts ORDER BY title ASC";
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get total views across all posts
     */
    public function getTotalViews()
    {
        $sql = "SELECT SUM(view) as 'SUM(view)' FROM posts";
        $result = $this->db->select($sql);
        return $result ? $result->fetch() : ['SUM(view)' => 0];
    }
    
    /**
     * Get post by ID
     */
    public function getById($id)
    {
        $sql = "SELECT p.*, u.username, c.name as category_name 
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                WHERE p.id = ?";
        
        $result = $this->db->select($sql, [$id]);
        return $result ? $result->fetch() : null;
    }
    
    /**
     * Get admin posts list with pagination and filters
     */
    public function getAdminList($offset = 0, $limit = 10, $search = null, $status = null, $category = null)
    {
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(p.title LIKE ? OR p.summary LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($status && $status !== 'all') {
            $conditions[] = "p.status = ?";
            $params[] = $status;
        }
        
        if ($category && $category !== 'all') {
            $conditions[] = "p.cat_id = ?";
            $params[] = $category;
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT p.*, u.username, c.name as category_name 
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                $whereClause
                ORDER BY p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $result = $this->db->select($sql, $params);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get admin posts count with filters
     */
    public function getAdminCount($search = null, $status = null, $category = null)
    {
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(title LIKE ? OR summary LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($status && $status !== 'all') {
            $conditions[] = "status = ?";
            $params[] = $status;
        }
        
        if ($category && $category !== 'all') {
            $conditions[] = "cat_id = ?";
            $params[] = $category;
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT COUNT(*) as total FROM posts $whereClause";
        
        $result = $this->db->select($sql, $params);
        return $result ? (int)$result->fetch()['total'] : 0;
    }
    
    /**
     * Get posts with view counts for dashboard
     */
    public function getPostsWithViews($limit = 10)
    {
        $sql = "SELECT p.*, u.username, c.name as category_name 
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                WHERE p.status = 'enable'
                ORDER BY p.view DESC, p.created_at DESC 
                LIMIT " . (int)$limit;
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
}