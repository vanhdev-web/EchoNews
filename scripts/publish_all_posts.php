<?php
// Publish all posts

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Publishing all posts...\n\n";
    
    // Check current status
    $stmt = $pdo->query("SELECT COUNT(*) as count, status FROM posts GROUP BY status");
    $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Before publishing:\n";
    foreach($statusCounts as $status) {
        $statusText = $status['status'] == 1 ? 'Published' : 'Unpublished';
        echo "Status {$status['status']} ($statusText): {$status['count']} posts\n";
    }
    
    // Publish all posts
    $stmt = $pdo->prepare("UPDATE posts SET status = 1 WHERE status != 1");
    $result = $stmt->execute();
    $rowsUpdated = $stmt->rowCount();
    
    echo "\n✅ Updated $rowsUpdated posts to published status.\n\n";
    
    // Check after
    $stmt = $pdo->query("SELECT COUNT(*) as count, status FROM posts GROUP BY status");
    $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "After publishing:\n";
    foreach($statusCounts as $status) {
        $statusText = $status['status'] == 1 ? 'Published' : 'Unpublished';
        echo "Status {$status['status']} ($statusText): {$status['count']} posts\n";
    }
    
    // Show posts by category now
    echo "\nPosts by category after publishing:\n";
    $stmt = $pdo->query("
        SELECT 
            (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category,
            COUNT(*) as post_count
        FROM posts 
        WHERE status = 1 
        GROUP BY cat_id 
        ORDER BY category
    ");
    $categoryStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($categoryStats as $stat) {
        echo "- {$stat['category']}: {$stat['post_count']} posts\n";
    }
    
    echo "\n🎉 Now refresh the homepage to see all posts!\n";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>