<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Category;

/**
 * Admin Category Controller
 * Quản lý danh mục trong admin panel
 */
class CategoryController extends BaseController
{
    protected $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->categoryModel = new Category();
    }
    
    /**
     * Danh sách danh mục
     */
    public function index()
    {
        // Lấy tham số phân trang
        $page = (int)($this->getInput('page') ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Lấy tham số tìm kiếm
        $search = $this->getInput('search');
        
        // Lấy danh sách danh mục với phân trang
        $categories = $this->categoryModel->getAdminList($offset, $limit, $search);
        $totalCategories = $this->categoryModel->getAdminCount($search);
        $totalPages = ceil($totalCategories / $limit);
        
        return $this->render('admin.category.index', [
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCategories' => $totalCategories,
            'search' => $search
        ]);
    }
    
    /**
     * Hiển thị form tạo danh mục mới
     */
    public function create()
    {
        return $this->render('admin.category.create');
    }
    
    /**
     * Lưu danh mục mới
     */
    public function store()
    {
        try {
            $data = $this->getInput();
            
            // Validate dữ liệu
            if (empty($data['name'])) {
                return $this->redirect('admin/categories/create', 'Category name is required', 'error');
            }
            
            // Kiểm tra tên danh mục đã tồn tại
            if ($this->categoryModel->nameExists($data['name'])) {
                return $this->redirect('admin/categories/create', 'Category name already exists', 'error');
            }
            
            // Chuẩn bị dữ liệu
            $categoryData = [
                'name' => trim($data['name']),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->categoryModel->create($categoryData);
            
            if ($result) {
                return $this->redirect('admin/categories', 'Category created successfully', 'success');
            } else {
                return $this->redirect('admin/categories/create', 'Failed to create category', 'error');
            }
            
        } catch (\Exception $e) {
            return $this->redirect('admin/categories/create', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Hiển thị chi tiết danh mục
     */
    public function show($id)
    {
        $category = $this->categoryModel->getById($id);
        
        if (!$category) {
            return $this->redirect('admin/categories', 'Category not found', 'error');
        }
        
        // Lấy số bài viết trong danh mục
        $postCount = $this->categoryModel->countPosts($id);
        
        return $this->render('admin.category.show', [
            'category' => $category,
            'postCount' => $postCount
        ]);
    }
    
    /**
     * Hiển thị form chỉnh sửa danh mục
     */
    public function edit($id)
    {
        $category = $this->categoryModel->getById($id);
        
        if (!$category) {
            return $this->redirect('admin/categories', 'Category not found', 'error');
        }
        
        return $this->render('admin.category.edit', [
            'category' => $category
        ]);
    }
    
    /**
     * Cập nhật danh mục
     */
    public function update($id)
    {
        try {
            $category = $this->categoryModel->getById($id);
            
            if (!$category) {
                return $this->redirect('admin/categories', 'Category not found', 'error');
            }
            
            $data = $this->getInput();
            
            // Validate dữ liệu
            if (empty($data['name'])) {
                return $this->redirect("admin/categories/{$id}/edit", 'Category name is required', 'error');
            }
            
            // Kiểm tra tên danh mục đã tồn tại (trừ chính nó)
            if ($this->categoryModel->nameExists($data['name'], $id)) {
                return $this->redirect("admin/categories/{$id}/edit", 'Category name already exists', 'error');
            }
            
            // Chuẩn bị dữ liệu
            $categoryData = [
                'name' => trim($data['name']),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->categoryModel->update($id, $categoryData);
            
            if ($result) {
                return $this->redirect('admin/categories', 'Category updated successfully', 'success');
            } else {
                return $this->redirect("admin/categories/{$id}/edit", 'Failed to update category', 'error');
            }
            
        } catch (\Exception $e) {
            return $this->redirect("admin/categories/{$id}/edit", 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Xóa danh mục
     */
    public function delete($id)
    {
        try {
            $category = $this->categoryModel->getById($id);
            
            if (!$category) {
                return $this->redirect('admin/categories', 'Category not found', 'error');
            }
            
            // Kiểm tra xem còn bài viết trong danh mục không
            $postCount = $this->categoryModel->countPosts($id);
            if ($postCount > 0) {
                return $this->redirect('admin/categories', "Cannot delete category. It has {$postCount} posts.", 'error');
            }
            
            $result = $this->categoryModel->delete($id);
            
            if ($result) {
                return $this->redirect('admin/categories', 'Category deleted successfully', 'success');
            } else {
                return $this->redirect('admin/categories', 'Failed to delete category', 'error');
            }
            
        } catch (\Exception $e) {
            return $this->redirect('admin/categories', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Alias for delete method (for compatibility)
     */
    public function destroy($id)
    {
        return $this->delete($id);
    }
}