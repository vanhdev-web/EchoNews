<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Menu;
use Exception;

/**
 * Admin Menu Controller
 * Quản lý menu navigation trong admin panel
 */
class MenuController extends BaseController
{
    protected $menuModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->menuModel = new Menu();
    }
    
    /**
     * Danh sách menus
     */
    public function index()
    {
        // Lấy tham số phân trang
        $page = (int)($this->getInput('page') ?? 1);
        $limit = 15;
        $offset = ($page - 1) * $limit;
        
        // Lấy tham số tìm kiếm
        $search = $this->getInput('search');
        
        // Lấy danh sách menus với phân trang
        $menus = $this->menuModel->getAdminList($offset, $limit, $search);
        $totalMenus = $this->menuModel->getAdminCount($search);
        $totalPages = ceil($totalMenus / $limit);
        
        return $this->render('admin.menus.index', [
            'menus' => $menus,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalMenus' => $totalMenus,
            'search' => $search
        ]);
    }
    
    /**
     * Hiển thị form tạo menu mới
     */
    public function create()
    {
        $parentMenus = $this->menuModel->getParentMenus();
        
        return $this->render('admin.menus.create', [
            'parentMenus' => $parentMenus
        ]);
    }
    
    /**
     * Lưu menu mới
     */
    public function store()
    {
        try {
            $data = $this->getInput();
            
            // Validate dữ liệu
            $errors = $this->menuModel->validateMenuData($data);
            if (!empty($errors)) {
                return $this->redirect('admin/menus/create', implode(', ', $errors), 'error');
            }
            
            // Clean parent_id if empty
            if (empty($data['parent_id'])) {
                $data['parent_id'] = null;
            }
            
            // Tạo menu
            $menuId = $this->menuModel->create($data);
            
            if ($menuId) {
                return $this->redirect('admin/menus', 'Menu created successfully!', 'success');
            } else {
                return $this->redirect('admin/menus/create', 'Failed to create menu', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/menus/create', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Hiển thị chi tiết menu
     */
    public function show($id)
    {
        $menu = $this->menuModel->getById($id);
        
        if (!$menu) {
            return $this->redirect('admin/menus', 'Menu not found', 'error');
        }
        
        $children = $this->menuModel->getChildren($id);
        
        return $this->render('admin.menus.show', [
            'menu' => $menu,
            'children' => $children
        ]);
    }
    
    /**
     * Hiển thị form chỉnh sửa menu
     */
    public function edit($id)
    {
        $menu = $this->menuModel->getById($id);
        
        if (!$menu) {
            return $this->redirect('admin/menus', 'Menu not found', 'error');
        }
        
        $parentMenus = $this->menuModel->getParentMenus();
        
        return $this->render('admin.menus.edit', [
            'menu' => $menu,
            'parentMenus' => $parentMenus
        ]);
    }
    
    /**
     * Cập nhật menu
     */
    public function update($id)
    {
        try {
            $menu = $this->menuModel->getById($id);
            if (!$menu) {
                return $this->redirect('admin/menus', 'Menu not found', 'error');
            }
            
            $data = $this->getInput();
            
            // Validate dữ liệu
            $errors = $this->menuModel->validateMenuData($data, $id);
            if (!empty($errors)) {
                return $this->redirect("admin/menus/{$id}/edit", implode(', ', $errors), 'error');
            }
            
            // Clean parent_id if empty
            if (empty($data['parent_id'])) {
                $data['parent_id'] = null;
            }
            
            // Cập nhật menu
            $success = $this->menuModel->update($id, $data);
            
            if ($success) {
                return $this->redirect('admin/menus', 'Menu updated successfully!', 'success');
            } else {
                return $this->redirect("admin/menus/{$id}/edit", 'Failed to update menu', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect("admin/menus/{$id}/edit", 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Xóa menu
     */
    public function destroy($id)
    {
        try {
            $menu = $this->menuModel->getById($id);
            if (!$menu) {
                return $this->redirect('admin/menus', 'Menu not found', 'error');
            }
            
            // Check if menu has children
            if ($this->menuModel->hasChildren($id)) {
                $confirm = $this->getInput('confirm_delete');
                if ($confirm !== 'yes') {
                    return $this->redirect('admin/menus', 'Menu has sub-menus. Please confirm deletion.', 'warning');
                }
            }
            
            $success = $this->menuModel->deleteWithChildren($id);
            
            if ($success) {
                return $this->redirect('admin/menus', 'Menu deleted successfully!', 'success');
            } else {
                return $this->redirect('admin/menus', 'Failed to delete menu', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/menus', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Get menu tree (API endpoint)
     */
    public function getMenuTree()
    {
        try {
            $menuTree = $this->menuModel->getMenuTree();
            
            header('Content-Type: application/json');
            echo json_encode($menuTree);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}