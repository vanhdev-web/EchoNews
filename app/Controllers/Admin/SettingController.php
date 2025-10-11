<?php

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Models\Setting;
use Exception;

/**
 * Admin Setting Controller
 * Quản lý cài đặt hệ thống trong admin panel
 */
class SettingController extends BaseController
{
    protected $settingModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        
        $this->settingModel = new Setting();
    }
    
    /**
     * Hiển thị trang cài đặt
     */
    public function index()
    {
        $settings = $this->settingModel->getAllSettings();
        
        return $this->render('admin.settings.index', [
            'settings' => $settings
        ]);
    }
    
    /**
     * Cập nhật cài đặt
     */
    public function update()
    {
        try {
            $data = $this->getInput();
            
            // Validate dữ liệu
            if (empty($data)) {
                return $this->redirect('admin/settings', 'No data provided', 'error');
            }
            
            // Xử lý upload logo nếu có
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $logoPath = $this->uploadLogo($_FILES['logo']);
                if ($logoPath) {
                    $data['logo'] = $logoPath;
                }
            }
            
            // Cập nhật từng setting
            $success = true;
            foreach ($data as $key => $value) {
                if (!$this->settingModel->updateSetting($key, $value)) {
                    $success = false;
                    break;
                }
            }
            
            if ($success) {
                return $this->redirect('admin/settings', 'Settings updated successfully!', 'success');
            } else {
                return $this->redirect('admin/settings', 'Failed to update settings', 'error');
            }
            
        } catch (Exception $e) {
            return $this->redirect('admin/settings', 'Error: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Upload logo
     */
    private function uploadLogo($file)
    {
        $uploadDir = 'public/setting/';
        
        // Tạo thư mục nếu chưa có
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        // Validate file size (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            return false;
        }
        
        // Tạo tên file unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        }
        
        return null;
    }
}