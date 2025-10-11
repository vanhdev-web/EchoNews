<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\User;
use Exception;

/**
 * Admin User Controller
 * Quản lý users trong admin panel
 */
class UserController extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->userModel = new User();
    }
    
    /**
     * Danh sách users
     */
    public function index()
    {
        // Lấy tham số phân trang
        $page = (int)($this->getInput('page') ?? 1);
        $limit = 15;
        $offset = ($page - 1) * $limit;
        
        // Lấy tham số tìm kiếm và filter
        $search = $this->getInput('search');
        $permission = $this->getInput('permission');
        
        // Lấy danh sách users với phân trang
        $users = $this->userModel->getAdminList($offset, $limit, $search, $permission);
        $totalUsers = $this->userModel->getAdminCount($search, $permission);
        $totalPages = ceil($totalUsers / $limit);
        
        return $this->render('admin.users.index', [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers,
            'search' => $search,
            'permission' => $permission
        ]);
    }
    
    /**
     * Hiển thị form tạo user mới
     */
    public function create()
    {
        return $this->render('admin.users.create');
    }
    
    /**
     * Lưu user mới
     */
    public function store()
    {
        try {
            $data = $this->getInput();
            
            // Validate dữ liệu
            $errors = $this->validateUserData($data);
            if (!empty($errors)) {
                return $this->redirect('admin/users/create', implode(', ', $errors), 'error');
            }
            
            // Tạo user
            $userId = $this->userModel->createUser($data);
            
            if ($userId) {
                return $this->redirect('admin/users', 'User created successfully!', 'success');
            } else {
                return $this->redirect('admin/users/create', 'Failed to create user', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/users/create', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Hiển thị chi tiết user
     */
    public function show($id)
    {
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            return $this->redirect('admin/users', 'User not found', 'error');
        }
        
        return $this->render('admin.users.show', [
            'user' => $user
        ]);
    }
    
    /**
     * Hiển thị form chỉnh sửa user
     */
    public function edit($id)
    {
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            return $this->redirect('admin/users', 'User not found', 'error');
        }
        
        return $this->render('admin.users.edit', [
            'user' => $user
        ]);
    }
    
    /**
     * Cập nhật user
     */
    public function update($id)
    {
        try {
            $user = $this->userModel->getById($id);
            if (!$user) {
                return $this->redirect('admin/users', 'User not found', 'error');
            }
            
            $data = $this->getInput();
            
            // Validate dữ liệu (exclude current user for email/username check)
            $errors = $this->validateUserData($data, $id);
            if (!empty($errors)) {
                return $this->redirect("admin/users/{$id}/edit", implode(', ', $errors), 'error');
            }
            
            // Cập nhật user
            $success = $this->userModel->updateUser($id, $data);
            
            if ($success) {
                return $this->redirect('admin/users', 'User updated successfully!', 'success');
            } else {
                return $this->redirect("admin/users/{$id}/edit", 'Failed to update user', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect("admin/users/{$id}/edit", 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Xóa user
     */
    public function destroy($id)
    {
        try {
            $user = $this->userModel->getById($id);
            if (!$user) {
                return $this->redirect('admin/users', 'User not found', 'error');
            }
            
            // Không cho phép xóa admin cuối cùng
            if ($user['permission'] == 'admin') {
                $adminCount = $this->userModel->getByPermission('admin');
                if (count($adminCount) <= 1) {
                    return $this->redirect('admin/users', 'Cannot delete the last admin user', 'error');
                }
            }
            
            // Không cho phép xóa chính mình
            if ($user['id'] == $_SESSION['user']['id']) {
                return $this->redirect('admin/users', 'Cannot delete your own account', 'error');
            }
            
            $success = $this->userModel->delete($id);
            
            if ($success) {
                return $this->redirect('admin/users', 'User deleted successfully!', 'success');
            } else {
                return $this->redirect('admin/users', 'Failed to delete user', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/users', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Validate user data
     */
    private function validateUserData($data, $excludeId = null)
    {
        $errors = [];
        
        // Required fields
        if (empty($data['username'])) {
            $errors[] = 'Username is required';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        }
        
        // Email validation
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address';
        }
        
        // Password validation (only for new users)
        if ($excludeId === null && empty($data['password'])) {
            $errors[] = 'Password is required for new users';
        }
        
        if (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }
        
        // Check unique email
        if (!empty($data['email']) && $this->userModel->emailExists($data['email'], $excludeId)) {
            $errors[] = 'Email already exists';
        }
        
        // Check unique username
        if (!empty($data['username']) && $this->userModel->usernameExists($data['username'], $excludeId)) {
            $errors[] = 'Username already exists';
        }
        
        // Permission validation
        if (isset($data['permission']) && !in_array($data['permission'], ['user', 'admin'])) {
            $errors[] = 'Invalid permission value';
        }
        
        return $errors;
    }
    
    /**
     * Promote user to admin
     */
    public function promote($id)
    {
        try {
            $user = $this->userModel->getById($id);
            if (!$user) {
                return $this->redirect('admin/users', 'User not found', 'error');
            }
            
            if ($user['permission'] == 'admin') {
                return $this->redirect('admin/users', 'User is already an admin', 'warning');
            }
            
            $success = $this->userModel->update($id, ['permission' => 'admin']);
            
            if ($success) {
                return $this->redirect('admin/users', 'User promoted to admin successfully!', 'success');
            } else {
                return $this->redirect('admin/users', 'Failed to promote user', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/users', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Demote admin to regular user
     */
    public function demote($id)
    {
        try {
            $user = $this->userModel->getById($id);
            if (!$user) {
                return $this->redirect('admin/users', 'User not found', 'error');
            }
            
            if ($user['permission'] == 'user') {
                return $this->redirect('admin/users', 'User is already a regular user', 'warning');
            }
            
            // Check if this is the last admin
            $adminCount = count($this->userModel->getByPermission('admin'));
            if ($adminCount <= 1) {
                return $this->redirect('admin/users', 'Cannot demote the last admin user', 'error');
            }
            
            // Don't allow self-demotion
            if ($user['id'] == $_SESSION['user']['id']) {
                return $this->redirect('admin/users', 'Cannot demote yourself', 'error');
            }
            
            $success = $this->userModel->update($id, ['permission' => 'user']);
            
            if ($success) {
                return $this->redirect('admin/users', 'Admin privileges removed successfully!', 'success');
            } else {
                return $this->redirect('admin/users', 'Failed to remove admin privileges', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/users', 'Error: ' . $e->getMessage(), 'error');
        }
    }
}