<?php
// Test xem bài và kiểm tra view có tăng không

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Lấy bài đầu tiên
    $stmt = $pdo->query("SELECT id, title, view FROM posts WHERE status = 'published' ORDER BY id ASC LIMIT 1");
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($post) {
        echo "Testing post view increment...\n";
        echo "Post ID: {$post['id']}\n";
        echo "Title: {$post['title']}\n";
        echo "Current views: {$post['view']}\n\n";
        
        // Simulate view increment (giống như trong Home.php)
        $stmt = $pdo->prepare("UPDATE posts SET view = view + 1, updated_at = NOW() WHERE id = ?");
        $result = $stmt->execute([$post['id']]);
        
        if($result) {
            echo "✓ View incremented successfully!\n";
            
            // Kiểm tra lại
            $stmt = $pdo->prepare("SELECT view, updated_at FROM posts WHERE id = ?");
            $stmt->execute([$post['id']]);
            $updated = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "New views: {$updated['view']}\n";
            echo "Updated at: {$updated['updated_at']}\n\n";
            
            // Kiểm tra tổng views hôm nay
            $today = date('Y-m-d');
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as posts_count, SUM(view) as total_views 
                FROM posts 
                WHERE DATE(updated_at) = ? AND view > 0
            ");
            $stmt->execute([$today]);
            $todayStats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "Today's stats (updated_at = $today):\n";
            echo "Posts updated: {$todayStats['posts_count']}\n";
            echo "Total views: {$todayStats['total_views']}\n";
            
        } else {
            echo "✗ Failed to increment view!\n";
        }
    } else {
        echo "No published posts found!\n";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>