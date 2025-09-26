<?php

namespace App\Models;

use App\Core\BaseModel;

/**
 * Category Model
 * Quản lý categories/danh mục
 */
class Category extends BaseModel
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description', 'status'];
    
    /**
     * Get categories with posts count
     */
    public function getWithPostsCount()
    {
        $sql = "SELECT c.*, COUNT(p.id) as posts_count
                FROM categories c 
                LEFT JOIN posts p ON c.id = p.cat_id AND p.status = 1
                GROUP BY c.id 
                ORDER BY posts_count DESC, c.name ASC";
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get active categories
     */
    public function getActive()
    {
        return $this->where('status', 1);
    }
    
    /**
     * Get category with posts
     */
    public function getWithPosts($categoryId, $limit = null)
    {
        $sql = "SELECT c.*, p.id as post_id, p.title, p.summary, p.image, p.created_at as post_date, u.username
                FROM categories c 
                LEFT JOIN posts p ON c.id = p.cat_id AND p.status = 1
                LEFT JOIN users u ON p.user_id = u.id
                WHERE c.id = ?
                ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $result = $this->db->select($sql, [$categoryId]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get categories for menu
     */
    public function getForMenu()
    {
        $sql = "SELECT c.*, COUNT(p.id) as posts_count
                FROM categories c 
                LEFT JOIN posts p ON c.id = p.cat_id AND p.status = 1
                WHERE c.status = 1
                GROUP BY c.id 
                HAVING posts_count > 0
                ORDER BY c.name ASC";
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Check if category name exists
     */
    public function nameExists($name, $excludeId = null)
    {
        $sql = "SELECT id FROM categories WHERE name = ?";
        $params = [$name];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->select($sql, $params);
        return $result && $result->fetch();
    }
    
    /**
     * Get popular categories (by posts count)
     */
    public function getPopular($limit = 5)
    {
        $sql = "SELECT c.*, COUNT(p.id) as posts_count
                FROM categories c 
                INNER JOIN posts p ON c.id = p.cat_id AND p.status = 1
                WHERE c.status = 1
                GROUP BY c.id 
                ORDER BY posts_count DESC, c.name ASC
                LIMIT " . (int)$limit;
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get category statistics
     */
    public function getStatistics()
    {
        $sql = "SELECT 
                    COUNT(*) as total_categories,
                    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_count,
                    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive_count
                FROM categories";
        
        $result = $this->db->select($sql);
        return $result ? $result->fetch() : [];
    }
    
    /**
     * Activate category
     */
    public function activate($id)
    {
        return $this->update($id, ['status' => 1]);
    }
    
    /**
     * Deactivate category
     */
    public function deactivate($id)
    {
        return $this->update($id, ['status' => 0]);
    }
    
    /**
     * Get posts by category with pagination
     */
    public function getPostsPaginated($categoryId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT p.*, u.username, c.name as category_name
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN categories c ON p.cat_id = c.id 
                WHERE p.cat_id = ? AND p.status = 1
                ORDER BY p.created_at DESC 
                LIMIT {$perPage} OFFSET {$offset}";
        
        $result = $this->db->select($sql, [$categoryId]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Count posts in category
     */
    public function countPosts($categoryId)
    {
        $sql = "SELECT COUNT(*) as count FROM posts WHERE cat_id = ? AND status = 1";
        $result = $this->db->select($sql, [$categoryId]);
        return $result ? $result->fetch()['count'] : 0;
    }
}