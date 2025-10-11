<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Banner;
use Exception;

/**
 * Admin Banner Controller
 * Quản lý banners trong admin panel
 */
class BannerController extends BaseController
{
    protected $bannerModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->bannerModel = new Banner();
    }
    
    /**
     * Danh sách banners
     */
    public function index()
    {
        // Lấy tham số phân trang
        $page = (int)($this->getInput('page') ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Lấy tham số tìm kiếm
        $search = $this->getInput('search');
        
        // Lấy danh sách banners với phân trang
        $banners = $this->bannerModel->getAdminList($offset, $limit, $search);
        $totalBanners = $this->bannerModel->getAdminCount($search);
        $totalPages = ceil($totalBanners / $limit);
        
        return $this->render('admin.banners.index', [
            'banners' => $banners,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalBanners' => $totalBanners,
            'search' => $search
        ]);
    }
    
    /**
     * Hiển thị form tạo banner mới
     */
    public function create()
    {
        return $this->render('admin.banners.create');
    }
    
    /**
     * Lưu banner mới
     */
    public function store()
    {
        try {
            $data = $this->getInput();
            
            // Validate dữ liệu
            if (empty($data['url'])) {
                return $this->redirect('admin/banners/create', 'Banner URL is required', 'error');
            }
            
            // Validate URL format
            if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
                return $this->redirect('admin/banners/create', 'Please enter a valid URL', 'error');
            }
            
            // Handle image upload
            $imageFile = $_FILES['image'] ?? null;
            if (!$imageFile || $imageFile['error'] !== UPLOAD_ERR_OK) {
                return $this->redirect('admin/banners/create', 'Banner image is required', 'error');
            }
            
            // Tạo banner
            $bannerId = $this->bannerModel->createBanner($data, $imageFile);
            
            if ($bannerId) {
                return $this->redirect('admin/banners', 'Banner created successfully!', 'success');
            } else {
                return $this->redirect('admin/banners/create', 'Failed to create banner', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/banners/create', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Hiển thị chi tiết banner
     */
    public function show($id)
    {
        $banner = $this->bannerModel->getById($id);
        
        if (!$banner) {
            return $this->redirect('admin/banners', 'Banner not found', 'error');
        }
        
        return $this->render('admin.banners.show', [
            'banner' => $banner
        ]);
    }
    
    /**
     * Hiển thị form chỉnh sửa banner
     */
    public function edit($id)
    {
        $banner = $this->bannerModel->getById($id);
        
        if (!$banner) {
            return $this->redirect('admin/banners', 'Banner not found', 'error');
        }
        
        return $this->render('admin.banners.edit', [
            'banner' => $banner
        ]);
    }
    
    /**
     * Cập nhật banner
     */
    public function update($id)
    {
        try {
            $banner = $this->bannerModel->getById($id);
            if (!$banner) {
                return $this->redirect('admin/banners', 'Banner not found', 'error');
            }
            
            $data = $this->getInput();
            
            // Validate dữ liệu
            if (empty($data['url'])) {
                return $this->redirect("admin/banners/{$id}/edit", 'Banner URL is required', 'error');
            }
            
            // Validate URL format
            if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
                return $this->redirect("admin/banners/{$id}/edit", 'Please enter a valid URL', 'error');
            }
            
            // Handle image upload (optional for update)
            $imageFile = $_FILES['image'] ?? null;
            
            // Cập nhật banner
            $success = $this->bannerModel->updateBanner($id, $data, $imageFile);
            
            if ($success) {
                return $this->redirect('admin/banners', 'Banner updated successfully!', 'success');
            } else {
                return $this->redirect("admin/banners/{$id}/edit", 'Failed to update banner', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect("admin/banners/{$id}/edit", 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Xóa banner
     */
    public function destroy($id)
    {
        try {
            $banner = $this->bannerModel->getById($id);
            if (!$banner) {
                return $this->redirect('admin/banners', 'Banner not found', 'error');
            }
            
            $success = $this->bannerModel->deleteBanner($id);
            
            if ($success) {
                return $this->redirect('admin/banners', 'Banner deleted successfully!', 'success');
            } else {
                return $this->redirect('admin/banners', 'Failed to delete banner', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/banners', 'Error: ' . $e->getMessage(), 'error');
        }
    }
}