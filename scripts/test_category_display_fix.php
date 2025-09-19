<?php
// Test category display after fix

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Testing category display fix...\n\n";
    
    // Test the new query (LIMIT 200)
    $stmt = $pdo->query("
        SELECT posts.*, 
               (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, 
               (SELECT username FROM users WHERE users.id = posts.user_id) AS username, 
               (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category 
        FROM posts 
        WHERE status = 1 
        ORDER BY created_at DESC 
        LIMIT 0, 200
    ");
    $lastPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total posts retrieved: " . count($lastPosts) . "\n\n";
    
    // Group by category
    $postsByCategory = [];
    foreach($lastPosts as $post) {
        $category = $post['category'];
        if (!isset($postsByCategory[$category])) {
            $postsByCategory[$category] = [];
        }
        $postsByCategory[$category][] = $post;
    }
    
    echo "Posts by category:\n";
    foreach($postsByCategory as $categoryName => $posts) {
        echo "- $categoryName: " . count($posts) . " posts\n";
        foreach($posts as $post) {
            echo "  * " . substr($post['title'], 0, 50) . "...\n";
        }
        echo "\n";
    }
    
    // Test featured categories
    $featuredCategories = ['Technology', 'Business', 'Sports', 'Health', 'Entertainment', 'Science', 'Politics'];
    
    echo "Featured categories test:\n";
    foreach($featuredCategories as $categoryName) {
        $categoryPosts = array_filter($lastPosts, function($post) use ($categoryName) {
            return stripos($post['category'], $categoryName) !== false;
        });
        
        echo "- $categoryName: " . count($categoryPosts) . " posts will be displayed\n";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>