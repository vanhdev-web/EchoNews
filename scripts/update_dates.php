<?php
// Script để cập nhật ngày tạo bài viết để có dữ liệu cho biểu đồ

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Lấy tất cả posts
    $stmt = $pdo->query('SELECT id, view FROM posts ORDER BY id ASC');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Updating dates for " . count($posts) . " posts...\n";
    
    foreach($posts as $index => $post) {
        // Tạo ngày trong khoảng 7 ngày gần đây
        $daysAgo = rand(0, 6);
        $newDate = date('Y-m-d H:i:s', strtotime("-{$daysAgo} days"));
        
        // Cập nhật created_at để có dữ liệu cho biểu đồ
        $updateStmt = $pdo->prepare('UPDATE posts SET created_at = ?, published_at = ? WHERE id = ?');
        $updateStmt->execute([$newDate, $newDate, $post['id']]);
        
        echo "Updated post ID " . $post['id'] . " to date " . $newDate . " (Views: " . $post['view'] . ")\n";
    }
    
    echo "Done! Posts now have dates within last 7 days.\n";
    echo "Refresh the admin dashboard to see the chart with data.\n";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>