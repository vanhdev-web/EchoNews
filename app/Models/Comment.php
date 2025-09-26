<?php

namespace App\Models;

use App\Core\BaseModel;

/**
 * Comment Model
 * Quản lý comments và moderation
 */
class Comment extends BaseModel
{
    protected $table = 'comments';
    protected $fillable = [
        'user_id', 'post_id', 'comment', 'status'
    ];
    
    /**
     * Get comments with user and post info
     */
    public function getWithRelations()
    {
        $sql = "SELECT c.*, p.title as post_title, u.username, u.email
                FROM comments c 
                LEFT JOIN posts p ON c.post_id = p.id 
                LEFT JOIN users u ON c.user_id = u.id 
                ORDER BY c.created_at DESC";
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get comments for a specific post
     */
    public function getByPost($postId, $status = 'approved')
    {
        $sql = "SELECT c.*, u.username, u.email
                FROM comments c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.post_id = ? AND c.status = ?
                ORDER BY c.created_at ASC";
        
        $result = $this->db->select($sql, [$postId, $status]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get pending comments (unseen)
     */
    public function getPending()
    {
        return $this->where('status', 'unseen');
    }
    
    /**
     * Get approved comments
     */
    public function getApproved()
    {
        return $this->where('status', 'approved');
    }
    
    /**
     * Approve comment
     */
    public function approve($id)
    {
        return $this->update($id, ['status' => 'approved']);
    }
    
    /**
     * Reject comment
     */
    public function reject($id)
    {
        return $this->update($id, ['status' => 'rejected']);
    }
    
    /**
     * Mark as seen
     */
    public function markAsSeen($id)
    {
        return $this->update($id, ['status' => 'seen']);
    }
    
    /**
     * Create comment với toxic detection
     */
    public function createWithToxicCheck($data)
    {
        // Load toxic comment detector
        require_once BASE_PATH . '/lib/ToxicCommentDetector.php';
        $toxicDetector = new \ToxicCommentDetector();
        
        // Check comment toxicity
        $toxicResult = $toxicDetector->checkComment($data['comment']);
        
        // Set status based on toxicity
        $data['status'] = $toxicResult['should_approve'] ? 'approved' : 'unseen';
        
        return $this->create($data);
    }
    
    /**
     * Get comment statistics
     */
    public function getStatistics()
    {
        $sql = "SELECT 
                    COUNT(*) as total_comments,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN status = 'unseen' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_comments
                FROM comments";
        
        $result = $this->db->select($sql);
        return $result ? $result->fetch() : [];
    }
    
    /**
     * Get recent comments
     */
    public function getRecent($limit = 5)
    {
        $sql = "SELECT c.*, u.username, p.title as post_title
                FROM comments c 
                LEFT JOIN users u ON c.user_id = u.id 
                LEFT JOIN posts p ON c.post_id = p.id 
                ORDER BY c.created_at DESC 
                LIMIT " . (int)$limit;
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get comments count by post
     */
    public function getCountByPost($postId, $status = 'approved')
    {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE post_id = ? AND status = ?";
        $result = $this->db->select($sql, [$postId, $status]);
        return $result ? $result->fetch()['count'] : 0;
    }
    
    /**
     * Mark unseen comments as seen
     */
    public function markUnseenAsSeen()
    {
        $sql = "UPDATE comments SET status = 'seen' WHERE status = 'unseen'";
        return $this->db->select($sql);
    }
    
    /**
     * Bulk approve comments
     */
    public function bulkApprove($ids)
    {
        if (empty($ids)) return false;
        
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "UPDATE comments SET status = 'approved' WHERE id IN ({$placeholders})";
        
        return $this->db->select($sql, $ids);
    }
    
    /**
     * Bulk reject comments
     */
    public function bulkReject($ids)
    {
        if (empty($ids)) return false;
        
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "UPDATE comments SET status = 'rejected' WHERE id IN ({$placeholders})";
        
        return $this->db->select($sql, $ids);
    }
}