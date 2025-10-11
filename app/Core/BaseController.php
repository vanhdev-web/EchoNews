<?php

namespace App\Core;

use Database\DataBase;

abstract class BaseController
{
    protected $db;
    protected $viewEngine;
    
    public function __construct()
    {
        $this->db = new DataBase();
        $this->viewEngine = new ViewEngine();
    }
    
    protected function render($view, $data = [])
    {
        return $this->viewEngine->render($view, $data);
    }
    
    protected function redirect($url, $message = null, $type = 'info')
    {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        
        header('Location: ' . url($url));
        exit;
    }
    
    protected function getInput($key = null)
    {
        $data = array_merge($_GET, $_POST);
        
        if ($key) {
            return $data[$key] ?? null;
        }
        
        return $data;
    }
    
    protected function requireAuth()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('login', 'Please login to access this page', 'warning');
        }
    }
    
    protected function requireAdmin()
    {
        $this->requireAuth();
        
        $userPermission = $_SESSION['user']['permission'] ?? 'user';
        if ($userPermission !== 'admin') {
            $this->redirect('/', 'Access denied. Admin privileges required.', 'error');
        }
    }
}
