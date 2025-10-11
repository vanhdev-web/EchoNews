<?php

namespace App\Models;

use App\Core\BaseModel;

/**
 * Setting Model
 * Quản lý cài đặt hệ thống
 */
class Setting extends BaseModel
{
    protected $table = 'settings';
    protected $fillable = [
        'setting_key', 'setting_value', 'description'
    ];
    
    /**
     * Lấy tất cả settings
     */
    public function getAllSettings()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY setting_key";
        $result = $this->db->select($sql);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Lấy setting theo key
     */
    public function getSetting($key)
    {
        $sql = "SELECT * FROM {$this->table} WHERE setting_key = ?";
        $result = $this->db->select($sql, [$key]);
        return $result ? $result->fetch() : null;
    }
    
    /**
     * Lấy giá trị setting theo key
     */
    public function getSettingValue($key, $default = null)
    {
        $setting = $this->getSetting($key);
        return $setting ? $setting['setting_value'] : $default;
    }
    
    /**
     * Cập nhật hoặc tạo mới setting
     */
    public function updateSetting($key, $value)
    {
        $existingSetting = $this->getSetting($key);
        
        if ($existingSetting) {
            // Update existing setting
            return $this->db->update($this->table, $existingSetting['id'], 
                ['setting_value'], [$value]);
        } else {
            // Create new setting
            return $this->db->insert($this->table, 
                ['setting_key', 'setting_value'], [$key, $value]);
        }
    }
    
    /**
     * Lấy settings dạng key-value array
     */
    public function getSettingsArray()
    {
        $settings = $this->getAllSettings();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $result;
    }
    
    /**
     * Tạo setting mặc định nếu chưa có
     */
    public function createDefaultSettings()
    {
        $defaults = [
            'site_name' => 'Online News Site',
            'site_description' => 'Your trusted news source',
            'site_keywords' => 'news, online, breaking news',
            'admin_email' => 'admin@example.com',
            'logo' => 'public/setting/logo.png',
            'posts_per_page' => '10',
            'enable_comments' => '1',
            'enable_toxic_detection' => '1'
        ];
        
        foreach ($defaults as $key => $value) {
            if (!$this->getSetting($key)) {
                $this->updateSetting($key, $value);
            }
        }
        
        return true;
    }
}