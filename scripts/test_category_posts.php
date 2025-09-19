<?php
// Test category posts functionality

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Testing category posts...\n\n";
    
    // Get all categories with post count
    $stmt = $pdo->query("
        SELECT categories.*, 
               (SELECT COUNT(*) FROM posts WHERE posts.cat_id = categories.id AND posts.status = 1) AS published_count,
               (SELECT COUNT(*) FROM posts WHERE posts.cat_id = categories.id) AS total_count
        FROM categories 
        ORDER BY name ASC
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Categories with post counts:\n";
    foreach($categories as $category) {
        echo "ID: {$category['id']}, Name: {$category['name']}, Published Posts: {$category['published_count']}, Total Posts: {$category['total_count']}\n";
        
        // Get posts for this category
        if($category['published_count'] > 0) {
            $stmt = $pdo->prepare("
                SELECT posts.id, posts.title, posts.status, posts.created_at, posts.view
                FROM posts 
                WHERE cat_id = ? AND posts.status = 1 
                ORDER BY created_at DESC
            ");
            $stmt->execute([$category['id']]);
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "  Posts in this category:\n";
            foreach($posts as $post) {
                echo "    - {$post['title']} (Views: {$post['view']}, Date: {$post['created_at']})\n";
            }
        }
        echo "\n";
    }
    
    // Test specific category (first one with posts)
    $testCategory = null;
    foreach($categories as $cat) {
        if($cat['published_count'] > 0) {
            $testCategory = $cat;
            break;
        }
    }
    
    if($testCategory) {
        echo "Testing category page for: {$testCategory['name']} (ID: {$testCategory['id']})\n";
        echo "URL: http://localhost/OnlineNewsSite/category/{$testCategory['id']}\n";
        echo "Expected posts: {$testCategory['published_count']}\n";
    } else {
        echo "No categories with published posts found!\n";
        
        // Let's check what posts exist
        $stmt = $pdo->query("SELECT id, title, status, cat_id FROM posts LIMIT 10");
        $allPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Sample posts in database:\n";
        foreach($allPosts as $post) {
            echo "ID: {$post['id']}, Title: {$post['title']}, Status: {$post['status']}, Category ID: {$post['cat_id']}\n";
        }
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>