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
        'body', 'image', 'status', 'view'
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
                    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as published,
                    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as draft
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
}