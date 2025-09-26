<?php

namespace App\Core;

/**
 * View Engine Class
 * Xử lý rendering views và layout
 */
class ViewEngine
{
    protected $viewsPath;
    protected $layout = 'layouts/app';
    protected $data = [];
    
    public function __construct()
    {
        $this->viewsPath = BASE_PATH . '/app/Views/';
    }
    
    /**
     * Render view với layout
     */
    public function render($view, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        
        // Extract data to variables
        extract($this->data);
        
        // Start output buffering
        ob_start();
        
        // Include view file
        $viewFile = $this->viewsPath . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        // Get view content
        $content = ob_get_clean();
        
        // If no layout, return content directly
        if ($this->layout === null) {
            return $content;
        }
        
        // Render with layout
        $layoutFile = $this->viewsPath . str_replace('.', '/', $this->layout) . '.php';
        
        if (file_exists($layoutFile)) {
            ob_start();
            include $layoutFile;
            return ob_get_clean();
        } else {
            return $content;
        }
    }
    
    /**
     * Render view without layout
     */
    public function renderPartial($view, $data = [])
    {
        $originalLayout = $this->layout;
        $this->layout = null;
        $content = $this->render($view, $data);
        $this->layout = $originalLayout;
        return $content;
    }
    
    /**
     * Set layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }
    
    /**
     * Add global data
     */
    public function share($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        
        return $this;
    }
    
    /**
     * Include partial view
     */
    public function include($view, $data = [])
    {
        $mergedData = array_merge($this->data, $data);
        extract($mergedData);
        
        $viewFile = $this->viewsPath . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        }
    }
    
    /**
     * Render component
     */
    public function component($component, $data = [])
    {
        return $this->include('components/' . $component, $data);
    }
    
    /**
     * Asset helper
     */
    public function asset($path)
    {
        return CURRENT_DOMAIN . 'public/' . ltrim($path, '/');
    }
    
    /**
     * URL helper
     */
    public function url($path = '')
    {
        return CURRENT_DOMAIN . ltrim($path, '/');
    }
    
    /**
     * Flash message helper
     */
    public function flash($key = null)
    {
        if (!isset($_SESSION['flash_message'])) {
            return null;
        }
        
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        
        if ($key) {
            return $message[$key] ?? null;
        }
        
        return $message;
    }
    
    /**
     * CSRF token helper
     */
    public function csrf()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Old input helper (for form validation)
     */
    public function old($key, $default = '')
    {
        $oldInput = $_SESSION['old_input'] ?? [];
        return $oldInput[$key] ?? $default;
    }
    
    /**
     * Error helper
     */
    public function error($key)
    {
        $errors = $_SESSION['errors'] ?? [];
        return $errors[$key] ?? null;
    }
}