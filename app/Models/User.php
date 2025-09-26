<?php

namespace App\Models;

use App\Core\BaseModel;

/**
 * User Model
 * Quản lý users và authentication
 */
class User extends BaseModel
{
    protected $table = 'users';
    protected $fillable = [
        'first_name', 'last_name', 'username', 
        'email', 'password', 'permission'
    ];
    protected $hidden = ['password'];
    
    /**
     * Create new user với password hash
     */
    public function createUser($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->create($data);
    }
    
    /**
     * Verify user credentials
     */
    public function verifyCredentials($email, $password)
    {
        $user = $this->first('email', $email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $this->hideAttributes($user);
        }
        
        return false;
    }
    
    /**
     * Get user by email
     */
    public function getByEmail($email)
    {
        $user = $this->first('email', $email);
        return $user ? $this->hideAttributes($user) : null;
    }
    
    /**
     * Get user with posts count
     */
    public function getWithPostsCount($limit = null)
    {
        $sql = "SELECT u.*, COUNT(p.id) as posts_count
                FROM users u 
                LEFT JOIN posts p ON u.id = p.user_id 
                GROUP BY u.id 
                ORDER BY posts_count DESC, u.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->select($sql, $params);
        return $result && $result->fetch();
    }
    
    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null)
    {
        $sql = "SELECT id FROM users WHERE username = ?";
        $params = [$username];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->select($sql, $params);
        return $result && $result->fetch();
    }
    
    /**
     * Update password
     */
    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }
    
    /**
     * Get users by permission level
     */
    public function getByPermission($permission)
    {
        return $this->where('permission', $permission);
    }
    
    /**
     * Get admins
     */
    public function getAdmins()
    {
        return $this->getByPermission(1);
    }
    
    /**
     * Get regular users
     */
    public function getRegularUsers()
    {
        return $this->getByPermission(0);
    }
    
    /**
     * Update last login
     */
    public function updateLastLogin($id)
    {
        $sql = "UPDATE users SET updated_at = NOW() WHERE id = ?";
        return $this->db->select($sql, [$id]);
    }
    
    /**
     * Get user statistics
     */
    public function getStatistics()
    {
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN permission = 1 THEN 1 ELSE 0 END) as admin_count,
                    SUM(CASE WHEN permission = 0 THEN 1 ELSE 0 END) as user_count,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_registrations
                FROM users";
        
        $result = $this->db->select($sql);
        return $result ? $result->fetch() : [];
    }
}