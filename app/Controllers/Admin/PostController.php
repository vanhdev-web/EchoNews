<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Exception;

/**
 * Admin Post Controller
 * Quản lý bài viết trong admin panel
 */
class PostController extends BaseController
{
    protected $postModel;
    protected $categoryModel;
    protected $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->userModel = new User();
    }
    
    /**
     * Danh sách bài viết
     */
    public function index()
    {
        // Lấy tham số phân trang
        $page = (int)($this->getInput('page') ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Lấy tham số tìm kiếm và lọc
        $search = $this->getInput('search');
        $status = $this->getInput('status');
        $category = $this->getInput('category');
        
        // Lấy danh sách bài viết với phân trang
        $posts = $this->postModel->getAdminList($offset, $limit, $search, $status, $category);
        $totalPosts = $this->postModel->getAdminCount($search, $status, $category);
        $totalPages = ceil($totalPosts / $limit);
        
        // Lấy danh sách category để filter
        $categories = $this->categoryModel->getAll();
        
        return $this->render('admin.post.index', [
            'posts' => $posts,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'search' => $search,
            'status' => $status,
            'category' => $category
        ]);
    }
    
    /**
     * Hiển thị form tạo bài viết mới
     */
    public function create()
    {
        $categories = $this->categoryModel->getAll();
        
        return $this->render('admin.post.create', [
            'categories' => $categories
        ]);
    }
    
    /**
     * Lưu bài viết mới
     */
    public function store()
    {
        try {
            $data = $this->getInput();
            
            // Validate dữ liệu
            if (empty($data['title']) || empty($data['body']) || empty($data['cat_id'])) {
                return $this->redirect('admin/posts/create', 'Please fill all required fields', 'error');
            }
            
            // Xử lý upload ảnh
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            }
            
            // Chuẩn bị dữ liệu
            $postData = [
                'title' => $data['title'],
                'summary' => $data['summary'] ?? '',
                'body' => $data['body'],
                'cat_id' => $data['cat_id'],
                'user_id' => $_SESSION['user']['id'] ?? $_SESSION['user'],
                'image' => $image ?: 'no-image.jpg', // Default image if none uploaded
                'status' => $data['status'] ?? 'disable',
                'selected' => (int)($data['selected'] ?? 0),
                'breaking_news' => (int)($data['breaking_news'] ?? 0),
                'published_at' => $data['published_at'] ?? date('Y-m-d H:i:s')
            ];
            
            $result = $this->postModel->create($postData);
            
            if ($result) {
                return $this->redirect('admin/posts', 'Post created successfully', 'success');
            } else {
                return $this->redirect('admin/posts/create', 'Failed to create post', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/posts/create', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Hiển thị chi tiết bài viết
     */
    public function show($id)
    {
        $post = $this->postModel->getById($id);
        
        if (!$post) {
            return $this->redirect('admin/posts', 'Post not found', 'error');
        }
        
        return $this->render('admin.post.show', [
            'post' => $post
        ]);
    }
    
    /**
     * Hiển thị form chỉnh sửa bài viết
     */
    public function edit($id)
    {
        $post = $this->postModel->getById($id);
        
        if (!$post) {
            return $this->redirect('admin/posts', 'Post not found', 'error');
        }
        
        $categories = $this->categoryModel->getAll();
        
        return $this->render('admin.post.edit', [
            'post' => $post,
            'categories' => $categories
        ]);
    }
    
    /**
     * Cập nhật bài viết
     */
    public function update($id)
    {
        try {
            $post = $this->postModel->getById($id);
            
            if (!$post) {
                return $this->redirect('admin/posts', 'Post not found', 'error');
            }
            
            $data = $this->getInput();
            
            // Validate dữ liệu
            if (empty($data['title']) || empty($data['body']) || empty($data['cat_id'])) {
                return $this->redirect("admin/posts/{$id}/edit", 'Please fill all required fields', 'error');
            }
            
            // Xử lý upload ảnh mới (nếu có)
            $image = $post['image']; // Giữ ảnh cũ
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $newImage = $this->uploadImage($_FILES['image']);
                if ($newImage) {
                    $image = $newImage;
                    // TODO: Xóa ảnh cũ nếu cần
                }
            }
            
            // Chuẩn bị dữ liệu
            $postData = [
                'title' => $data['title'],
                'summary' => $data['summary'] ?? '',
                'body' => $data['body'],
                'cat_id' => $data['cat_id'],
                'image' => $image,
                'status' => $data['status'] ?? 'disable',
                'selected' => (int)($data['selected'] ?? 0),
                'breaking_news' => (int)($data['breaking_news'] ?? 0),
                'published_at' => $data['published_at'] ?? $post['published_at'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->postModel->update($id, $postData);
            
            if ($result) {
                return $this->redirect('admin/posts', 'Post updated successfully', 'success');
            } else {
                return $this->redirect("admin/posts/{$id}/edit", 'Failed to update post', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect("admin/posts/{$id}/edit", 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Xóa bài viết
     */
    public function delete($id)
    {
        try {
            $post = $this->postModel->getById($id);
            
            if (!$post) {
                return $this->redirect('admin/posts', 'Post not found', 'error');
            }
            
            $result = $this->postModel->delete($id);
            
            if ($result) {
                // TODO: Xóa ảnh nếu cần
                return $this->redirect('admin/posts', 'Post deleted successfully', 'success');
            } else {
                return $this->redirect('admin/posts', 'Failed to delete post', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/posts', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Alias for delete method (for compatibility)
     */
    public function destroy($id)
    {
        return $this->delete($id);
    }
    
    /**
     * Upload ảnh bài viết
     */
    private function uploadImage($file)
    {
        $uploadDir = 'public/post-image/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Tạo tên file unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = date('Y-m-d-H-i-s') . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        }
        
        return null;
    }
    
    /**
     * Toggle breaking news status
     */
    public function toggleBreakingNews($id)
    {
        try {
            $post = $this->postModel->find($id);
            
            if (!$post) {
                return $this->redirect('admin/posts', 'Post not found', 'error');
            }
            
            // Toggle breaking news (1 = no, 2 = yes)
            $newStatus = $post['breaking_news'] == 2 ? 1 : 2;
            
            $result = $this->postModel->update($id, ['breaking_news' => $newStatus]);
            
            if ($result) {
                $message = $newStatus == 2 ? 'Post added to Breaking News' : 'Post removed from Breaking News';
                return $this->redirect('admin/posts', $message, 'success');
            } else {
                return $this->redirect('admin/posts', 'Failed to update breaking news status', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/posts', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        try {
            $post = $this->postModel->find($id);
            
            if (!$post) {
                return $this->redirect('admin/posts', 'Post not found', 'error');
            }
            
            // Toggle featured status (1 = no, 2 = yes)
            $newStatus = $post['selected'] == 2 ? 1 : 2;
            
            $result = $this->postModel->update($id, ['selected' => $newStatus]);
            
            if ($result) {
                $message = $newStatus == 2 ? 'Post added to Featured' : 'Post removed from Featured';
                return $this->redirect('admin/posts', $message, 'success');
            } else {
                return $this->redirect('admin/posts', 'Failed to update featured status', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/posts', 'Error: ' . $e->getMessage(), 'error');
        }
    }
}