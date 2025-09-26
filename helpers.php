<?php

/**
 * Helper Functions
 * Các hàm tiện ích được sử dụng trong toàn bộ application
 */

/**
 * Generate URL
 */
function url($path = '')
{
    return CURRENT_DOMAIN . ltrim($path, '/');
}

/**
 * Generate asset URL
 */
function asset($path)
{
    return CURRENT_DOMAIN . 'public/' . ltrim($path, '/');
}

/**
 * Get current domain
 */
function current_domain()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    return $protocol . $_SERVER['HTTP_HOST'];
}

/**
 * Redirect helper
 */
function redirect($url, $message = null, $type = 'success')
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
 * Flash message helper
 */
function flash($key = null)
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
 * Old input helper
 */
function old($key, $default = '')
{
    $oldInput = $_SESSION['old_input'] ?? [];
    return $oldInput[$key] ?? $default;
}

/**
 * Error helper
 */
function error($key)
{
    $errors = $_SESSION['errors'] ?? [];
    return $errors[$key] ?? null;
}

/**
 * CSRF token helper
 */
function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * CSRF field helper
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verify CSRF token
 */
function verify_csrf($token = null)
{
    $token = $token ?? ($_POST['csrf_token'] ?? '');
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize input
 */
function sanitize($input)
{
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is authenticated
 */
function is_auth()
{
    return isset($_SESSION['user']) && $_SESSION['user'] !== null;
}

/**
 * Check if user is admin
 */
function is_admin()
{
    return is_auth() && isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

/**
 * Get authenticated user ID
 */
function auth_id()
{
    return $_SESSION['user'] ?? null;
}

/**
 * Get authenticated username
 */
function auth_username()
{
    return $_SESSION['username'] ?? null;
}

/**
 * Format date
 */
function format_date($date, $format = 'M d, Y')
{
    return date($format, strtotime($date));
}

/**
 * Format relative time (time ago)
 */
function time_ago($datetime)
{
    $time = time() - strtotime($datetime);
    
    if ($time < 60) {
        return 'just now';
    } elseif ($time < 3600) {
        return floor($time / 60) . ' minutes ago';
    } elseif ($time < 86400) {
        return floor($time / 3600) . ' hours ago';
    } elseif ($time < 2592000) {
        return floor($time / 86400) . ' days ago';
    } elseif ($time < 31104000) {
        return floor($time / 2592000) . ' months ago';
    } else {
        return floor($time / 31104000) . ' years ago';
    }
}

/**
 * Truncate text
 */
function str_limit($text, $limit = 100, $end = '...')
{
    if (strlen($text) <= $limit) {
        return $text;
    }
    
    return substr($text, 0, $limit) . $end;
}

/**
 * Slug generator
 */
function str_slug($text, $separator = '-')
{
    // Replace non letter or digits by separator
    $text = preg_replace('~[^\pL\d]+~u', $separator, $text);
    
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    
    // Trim
    $text = trim($text, $separator);
    
    // Remove duplicate separators
    $text = preg_replace('~-+~', $separator, $text);
    
    // Lowercase
    $text = strtolower($text);
    
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}

/**
 * Debug helper
 */
function dd(...$vars)
{
    foreach ($vars as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    die();
}

/**
 * JSON response helper
 */
function json_response($data, $statusCode = 200)
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Upload file helper
 */
function upload_file($file, $directory = 'uploads', $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'])
{
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'error' => 'No file uploaded'];
    }
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error occurred'];
    }
    
    // Get file info
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Validate file type
    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type'];
    }
    
    // Validate file size (max 5MB)
    if ($fileSize > 5 * 1024 * 1024) {
        return ['success' => false, 'error' => 'File too large'];
    }
    
    // Create unique filename
    $newFileName = date('Y-m-d-H-i-s') . '.' . $fileExt;
    $uploadPath = BASE_PATH . '/public/' . $directory . '/' . $newFileName;
    
    // Create directory if it doesn't exist
    if (!file_exists(dirname($uploadPath))) {
        mkdir(dirname($uploadPath), 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        return ['success' => true, 'filename' => $directory . '/' . $newFileName];
    } else {
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
}

/**
 * Pagination helper
 */
function paginate($currentPage, $totalPages, $baseUrl)
{
    $html = '<nav aria-label="Page navigation">';
    $html .= '<ul class="pagination justify-content-center">';
    
    // Previous page
    if ($currentPage > 1) {
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">&laquo; Previous</a>';
        $html .= '</li>';
    }
    
    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    for ($i = $start; $i <= $end; $i++) {
        $active = ($i == $currentPage) ? ' active' : '';
        $html .= '<li class="page-item' . $active . '">';
        $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a>';
        $html .= '</li>';
    }
    
    // Next page
    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">Next &raquo;</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}