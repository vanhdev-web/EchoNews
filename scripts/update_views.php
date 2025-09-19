<?php
// Script để tạo dữ liệu demo cho views

// Include file config
$configPath = dirname(__DIR__) . '/index.php';
if (file_exists($configPath)) {
    // Get constants from index.php
    $content = file_get_contents($configPath);
    if (preg_match('/define\s*\(\s*[\'"]BASE_PATH[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]\s*\)/', $content, $matches)) {
        define('BASE_PATH', $matches[1]);
    }
}

require_once dirname(__DIR__) . '/database/DataBase.php';

use DataBase\DataBase;

$db = new DataBase();

// Lấy tất cả posts
$posts = $db->select('SELECT * FROM posts ORDER BY id ASC')->fetchAll();

echo "Updating view counts for " . count($posts) . " posts...\n";

foreach($posts as $index => $post) {
    // Tạo random views cho mỗi bài viết
    $randomViews = rand(50, 2000);
    
    $db->update('posts', $post['id'], ['view'], [$randomViews]);
    
    echo "Updated post ID " . $post['id'] . " with " . $randomViews . " views\n";
}

echo "Done! All posts now have view counts.\n";
echo "You can now test the views chart in the admin dashboard.\n";
?>