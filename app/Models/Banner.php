<?php

namespace App\Models;

use App\Core\BaseModel;

/**
 * Banner Model
 * Quản lý banners hiển thị trên website
 */
class Banner extends BaseModel
{
    protected $table = 'banners';
    protected $fillable = [
        'image', 'url'
    ];
    
    /**
     * Get all banners for admin management
     */
    public function getAdminList($offset = 0, $limit = 15, $search = null)
    {
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(url LIKE ?)";
            $params[] = "%$search%";
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT * FROM banners 
                $whereClause
                ORDER BY created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $result = $this->db->select($sql, $params);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get admin banners count with filters
     */
    public function getAdminCount($search = null)
    {
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(url LIKE ?)";
            $params[] = "%$search%";
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT COUNT(*) as total FROM banners $whereClause";
        $result = $this->db->select($sql, $params);
        return $result ? (int)$result->fetch()['total'] : 0;
    }
    
    /**
     * Get banner by ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM banners WHERE id = ?";
        $result = $this->db->select($sql, [$id]);
        return $result ? $result->fetch() : null;
    }
    
    /**
     * Get active banners for public display
     */
    public function getActiveBanners()
    {
        $sql = "SELECT * FROM banners ORDER BY created_at DESC";
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Create new banner with image upload
     */
    public function createBanner($data, $imageFile = null)
    {
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($imageFile);
            if ($imagePath) {
                $data['image'] = $imagePath;
            }
        }
        
        return $this->create($data);
    }
    
    /**
     * Update banner with optional image upload
     */
    public function updateBanner($id, $data, $imageFile = null)
    {
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($imageFile);
            if ($imagePath) {
                // Delete old image
                $oldBanner = $this->getById($id);
                if ($oldBanner && $oldBanner['image'] && file_exists($oldBanner['image'])) {
                    unlink($oldBanner['image']);
                }
                $data['image'] = $imagePath;
            }
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Delete banner with image cleanup
     */
    public function deleteBanner($id)
    {
        $banner = $this->getById($id);
        if ($banner) {
            // Delete image file
            if ($banner['image'] && file_exists($banner['image'])) {
                unlink($banner['image']);
            }
            return $this->delete($id);
        }
        return false;
    }
    
    /**
     * Upload banner image
     */
    private function uploadImage($imageFile)
    {
        $uploadDir = 'public/banner-image/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Validate image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($imageFile['type'], $allowedTypes)) {
            return false;
        }
        
        // Generate filename
        $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $filename = date('Y-m-d-H-i-s') . '.' . $extension;
        $filePath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($imageFile['tmp_name'], $filePath)) {
            return $filePath;
        }
        
        return false;
    }
}