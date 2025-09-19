<?php
// Script đơn giản để tạo dữ liệu demo cho views

// Kết nối trực tiếp database
$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Lấy tất cả posts
    $stmt = $pdo->query('SELECT id FROM posts ORDER BY id ASC');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Updating view counts for " . count($posts) . " posts...\n";
    
    foreach($posts as $post) {
        // Tạo random views cho mỗi bài viết
        $randomViews = rand(100, 3000);
        
        $updateStmt = $pdo->prepare('UPDATE posts SET view = ? WHERE id = ?');
        $updateStmt->execute([$randomViews, $post['id']]);
        
        echo "Updated post ID " . $post['id'] . " with " . $randomViews . " views\n";
    }
    
    echo "Done! All posts now have view counts.\n";
    echo "You can now test the views chart in the admin dashboard.\n";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>