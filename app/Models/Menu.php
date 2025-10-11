<?php

namespace App\Models;

use App\Core\BaseModel;

/**
 * Menu Model
 * Quản lý menu navigation website
 */
class Menu extends BaseModel
{
    protected $table = 'menus';
    protected $fillable = [
        'name', 'url', 'parent_id'
    ];
    
    /**
     * Get all menus for admin management
     */
    public function getAdminList($offset = 0, $limit = 15, $search = null)
    {
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(name LIKE ? OR url LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT m.*, p.name as parent_name
                FROM menus m 
                LEFT JOIN menus p ON m.parent_id = p.id 
                $whereClause
                ORDER BY m.parent_id ASC, m.name ASC 
                LIMIT $limit OFFSET $offset";
        
        $result = $this->db->select($sql, $params);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get admin menus count with filters
     */
    public function getAdminCount($search = null)
    {
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(name LIKE ? OR url LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT COUNT(*) as total FROM menus $whereClause";
        $result = $this->db->select($sql, $params);
        return $result ? (int)$result->fetch()['total'] : 0;
    }
    
    /**
     * Get menu by ID with parent info
     */
    public function getById($id)
    {
        $sql = "SELECT m.*, p.name as parent_name
                FROM menus m 
                LEFT JOIN menus p ON m.parent_id = p.id 
                WHERE m.id = ?";
        $result = $this->db->select($sql, [$id]);
        return $result ? $result->fetch() : null;
    }
    
    /**
     * Get all parent menus (for dropdown)
     */
    public function getParentMenus()
    {
        $sql = "SELECT id, name FROM menus WHERE parent_id IS NULL ORDER BY name ASC";
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Get menu tree structure for public display
     */
    public function getMenuTree()
    {
        $sql = "SELECT * FROM menus ORDER BY parent_id ASC, name ASC";
        $result = $this->db->select($sql);
        $menus = $result ? $result->fetchAll() : [];
        
        return $this->buildMenuTree($menus);
    }
    
    /**
     * Build hierarchical menu tree
     */
    private function buildMenuTree($menus, $parentId = null)
    {
        $tree = [];
        
        foreach ($menus as $menu) {
            if ($menu['parent_id'] == $parentId) {
                $menu['children'] = $this->buildMenuTree($menus, $menu['id']);
                $tree[] = $menu;
            }
        }
        
        return $tree;
    }
    
    /**
     * Check if menu has children
     */
    public function hasChildren($id)
    {
        $sql = "SELECT COUNT(*) as count FROM menus WHERE parent_id = ?";
        $result = $this->db->select($sql, [$id]);
        $count = $result ? (int)$result->fetch()['count'] : 0;
        return $count > 0;
    }
    
    /**
     * Delete menu and its children
     */
    public function deleteWithChildren($id)
    {
        // First delete all children
        $children = $this->getChildren($id);
        foreach ($children as $child) {
            $this->deleteWithChildren($child['id']);
        }
        
        // Then delete the menu itself
        return $this->delete($id);
    }
    
    /**
     * Get direct children of a menu
     */
    public function getChildren($parentId)
    {
        $sql = "SELECT * FROM menus WHERE parent_id = ?";
        $result = $this->db->select($sql, [$parentId]);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Validate menu data
     */
    public function validateMenuData($data, $id = null)
    {
        $errors = [];
        
        // Check required fields
        if (empty($data['name'])) {
            $errors[] = 'Menu name is required';
        }
        
        if (empty($data['url'])) {
            $errors[] = 'Menu URL is required';
        }
        
        // Check for circular reference (menu cannot be parent of itself)
        if ($id && isset($data['parent_id']) && $data['parent_id'] == $id) {
            $errors[] = 'Menu cannot be parent of itself';
        }
        
        // Check if parent exists
        if (isset($data['parent_id']) && $data['parent_id']) {
            $parent = $this->getById($data['parent_id']);
            if (!$parent) {
                $errors[] = 'Parent menu does not exist';
            }
        }
        
        return $errors;
    }
}