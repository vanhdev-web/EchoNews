<?php
// Check all posts in database regardless of status

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking ALL posts in database...\n\n";
    
    // Check all posts regardless of status
    $stmt = $pdo->query("
        SELECT posts.id, posts.title, posts.status, 
               (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category 
        FROM posts 
        ORDER BY posts.cat_id, posts.created_at DESC
    ");
    $allPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total posts in database: " . count($allPosts) . "\n\n";
    
    // Group by status
    $postsByStatus = [];
    foreach($allPosts as $post) {
        $status = $post['status'] == 1 ? 'Published' : 'Draft/Unpublished';
        if (!isset($postsByStatus[$status])) {
            $postsByStatus[$status] = [];
        }
        $postsByStatus[$status][] = $post;
    }
    
    foreach($postsByStatus as $status => $posts) {
        echo "$status posts: " . count($posts) . "\n";
        
        // Group by category
        $byCategory = [];
        foreach($posts as $post) {
            $category = $post['category'] ?: 'No Category';
            if (!isset($byCategory[$category])) {
                $byCategory[$category] = [];
            }
            $byCategory[$category][] = $post;
        }
        
        foreach($byCategory as $categoryName => $categoryPosts) {
            echo "  - $categoryName: " . count($categoryPosts) . " posts\n";
            foreach($categoryPosts as $post) {
                echo "    * [ID:{$post['id']}] " . substr($post['title'], 0, 60) . "...\n";
            }
        }
        echo "\n";
    }
    
    // Check if we need to update status
    $unpublishedCount = count($postsByStatus['Draft/Unpublished'] ?? []);
    if($unpublishedCount > 0) {
        echo "FOUND $unpublishedCount unpublished posts!\n";
        echo "Do you want to publish them? Run this command:\n";
        echo "UPDATE posts SET status = 1 WHERE status != 1;\n\n";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>