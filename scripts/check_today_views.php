<?php
// Kiểm tra views hôm nay và dữ liệu dashboard

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $today = date('Y-m-d');
    echo "Checking data for today: $today\n\n";
    
    // 1. Kiểm tra posts được tạo/cập nhật hôm nay
    $stmt = $pdo->prepare("
        SELECT id, title, view, created_at, updated_at 
        FROM posts 
        WHERE DATE(created_at) = ? OR DATE(updated_at) = ?
        ORDER BY updated_at DESC
    ");
    $stmt->execute([$today, $today]);
    $todayPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Posts from today:\n";
    foreach($todayPosts as $post) {
        echo "ID: {$post['id']}, Views: {$post['view']}, Created: {$post['created_at']}, Updated: {$post['updated_at']}\n";
        echo "Title: " . substr($post['title'], 0, 50) . "\n\n";
    }
    
    // 2. Kiểm tra tổng views hôm nay theo query dashboard
    $stmt = $pdo->prepare("
        SELECT DATE(created_at) as date, SUM(view) as total_views 
        FROM posts 
        WHERE view > 0 AND DATE(created_at) = ?
        GROUP BY DATE(created_at)
    ");
    $stmt->execute([$today]);
    $todayViews = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Dashboard query result for today:\n";
    if($todayViews) {
        echo "Date: {$todayViews['date']}, Total Views: {$todayViews['total_views']}\n";
    } else {
        echo "No data found for today in dashboard query!\n";
    }
    
    // 3. Kiểm tra tất cả posts có view > 0
    $stmt = $pdo->query("
        SELECT DATE(created_at) as date, COUNT(*) as posts_count, SUM(view) as total_views 
        FROM posts 
        WHERE view > 0
        GROUP BY DATE(created_at) 
        ORDER BY date DESC
    ");
    $allViews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nAll dates with views:\n";
    foreach($allViews as $dayData) {
        echo "Date: {$dayData['date']}, Posts: {$dayData['posts_count']}, Total Views: {$dayData['total_views']}\n";
    }
    
    // 4. Tổng views hiện tại
    $stmt = $pdo->query("SELECT SUM(view) as total FROM posts WHERE view > 0");
    $totalViews = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nTotal views in database: {$totalViews['total']}\n";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>