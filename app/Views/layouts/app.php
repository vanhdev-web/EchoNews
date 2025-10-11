<?php 
/**
 * Main Layout for MVC Application
 * This layout wraps all view content
 */

// Include header
require_once BASE_PATH . '/app/Views/layouts/header.php';

// View content will be rendered here
echo $content ?? '';

// Include footer
require_once BASE_PATH . '/app/Views/layouts/footer.php';
?>