<?php

namespace App\Core;

/**
 * Base Controller Class
 * Cung cấp functionality cơ bản cho tất cả controllers
 */
abstract class BaseController
{
    protected $view;
    
    public function __construct()
    {
        $this->view = new ViewEngine();
    }
    
    /**
     * Render view với data
     */
    protected function render($viewPath, $data = [])
    {
        return $this->view->render($viewPath, $data);
    }
    
    /**
     * Redirect với message
     */
    protected function redirect($url, $message = null, $type = 'success')
    {
        if ($message) {
            $_SESSION['flash_message'] = [
                'message' => $message,
                'type' => $type
            ];
        }
        
        header("Location: " . url($url));
        exit;
    }
    
    /**
     * Redirect back
     */
    protected function redirectBack($message = null, $type = 'success')
    {
        if ($message) {
            $_SESSION['flash_message'] = [
                'message' => $message,
                'type' => $type
            ];
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: " . $referer);
        exit;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Validate request data
     */
    protected function validate($data, $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $ruleArray = explode('|', $rule);
            
            foreach ($ruleArray as $singleRule) {
                if ($singleRule === 'required' && empty($data[$field])) {
                    $errors[$field][] = "The {$field} field is required.";
                }
                
                if (strpos($singleRule, 'min:') === 0) {
                    $min = (int) substr($singleRule, 4);
                    if (strlen($data[$field]) < $min) {
                        $errors[$field][] = "The {$field} must be at least {$min} characters.";
                    }
                }
                
                if (strpos($singleRule, 'max:') === 0) {
                    $max = (int) substr($singleRule, 4);
                    if (strlen($data[$field]) > $max) {
                        $errors[$field][] = "The {$field} may not be greater than {$max} characters.";
                    }
                }
                
                if ($singleRule === 'email' && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The {$field} must be a valid email address.";
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function requireAuth()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'] === null) {
            $this->redirect('login', 'Please login to access this page.', 'error');
        }
    }
    
    /**
     * Check if user is admin
     */
    protected function requireAdmin()
    {
        $this->requireAuth();
        
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            $this->redirect('/', 'Access denied. Admin privileges required.', 'error');
        }
    }
    
    /**
     * Get request data
     */
    protected function getInput($key = null, $default = null)
    {
        $input = array_merge($_GET, $_POST, $_FILES);
        
        if ($key === null) {
            return $input;
        }
        
        return $input[$key] ?? $default;
    }
    
    /**
     * Sanitize input data
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}