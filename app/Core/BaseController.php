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
    
    protected function getInput($key = null, $default = null)
    {
        $data = array_merge($_GET, $_POST);
        
        if ($key) {
            return $data[$key] ?? $default;
        }
        
        return $data;
    }
    
    protected function redirectBack($message = null, $type = 'info')
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        
        header('Location: ' . $referer);
        exit;
    }
    
    protected function validate($input, $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $input[$field] ?? '';
            $ruleList = explode('|', $rule);
            
            foreach ($ruleList as $singleRule) {
                if ($singleRule === 'required' && empty($value)) {
                    $errors[$field][] = ucfirst($field) . ' is required.';
                } elseif (strpos($singleRule, 'min:') === 0) {
                    $min = (int) substr($singleRule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field][] = ucfirst($field) . " must be at least {$min} characters.";
                    }
                } elseif (strpos($singleRule, 'max:') === 0) {
                    $max = (int) substr($singleRule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field][] = ucfirst($field) . " must not exceed {$max} characters.";
                    }
                } elseif ($singleRule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = ucfirst($field) . ' must be a valid email address.';
                }
            }
        }
        
        return $errors;
    }
    
    protected function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function requireAuth()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('login', 'Please login', 'warning');
        }
    }
    
    protected function requireAdmin()
    {
        $this->requireAuth();
        
        // Check admin flag that was set during login
        if (empty($_SESSION['admin'])) {
            $this->redirect('/', 'Access denied - Admin privileges required', 'error');
        }
    }
}