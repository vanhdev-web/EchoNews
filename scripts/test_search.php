<?php
// Test search functionality

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Testing search functionality...\n\n";
    
    // Test search query
    $searchTerm = 'technology';
    $searchPattern = '%' . $searchTerm . '%';
    
    $searchQuery = "
        SELECT posts.*, 
               (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments_count, 
               (SELECT username FROM users WHERE users.id = posts.user_id) AS username, 
               (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category 
        FROM posts 
        WHERE posts.status = 1 
        AND (posts.title LIKE ? OR posts.body LIKE ? OR posts.summary LIKE ?)
        ORDER BY posts.created_at DESC 
        LIMIT 0, 10
    ";
    
    $stmt = $pdo->prepare($searchQuery);
    $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Search term: '$searchTerm'\n";
    echo "Results found: " . count($results) . "\n\n";
    
    foreach($results as $post) {
        echo "ID: {$post['id']}\n";
        echo "Title: {$post['title']}\n";
        echo "Category: {$post['category']}\n";
        echo "Author: {$post['username']}\n";
        echo "Views: {$post['view']}\n";
        echo "Comments: {$post['comments_count']}\n";
        echo "---\n";
    }
    
    // Test JSON output
    echo "\nJSON Output (for AJAX):\n";
    echo json_encode(array_slice($results, 0, 5), JSON_PRETTY_PRINT);
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>