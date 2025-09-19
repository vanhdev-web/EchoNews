<?php
// Update post status to published

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking and updating post status...\n\n";
    
    // Check current status distribution
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM posts GROUP BY status");
    $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Current post status distribution:\n";
    foreach($statusCounts as $status) {
        $statusText = $status['status'] == 1 ? 'Published' : ($status['status'] == 0 ? 'Draft' : 'Other');
        echo "Status {$status['status']} ($statusText): {$status['count']} posts\n";
    }
    
    // Update all posts to published status if needed
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts WHERE status = 1");
    $publishedCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if($publishedCount == 0) {
        echo "\nNo published posts found. Setting all posts to published status...\n";
        $stmt = $pdo->prepare("UPDATE posts SET status = 1");
        $stmt->execute();
        
        echo "All posts have been set to published status.\n";
    } else {
        echo "\nFound $publishedCount published posts.\n";
    }
    
    // Show posts by category after update
    echo "\nPosts by category after update:\n";
    $stmt = $pdo->query("
        SELECT c.name as category_name, COUNT(p.id) as post_count
        FROM categories c
        LEFT JOIN posts p ON c.id = p.cat_id AND p.status = 1
        GROUP BY c.id, c.name
        ORDER BY c.name
    ");
    $categoryStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($categoryStats as $stat) {
        echo "{$stat['category_name']}: {$stat['post_count']} published posts\n";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>