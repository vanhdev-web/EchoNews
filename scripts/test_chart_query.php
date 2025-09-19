<?php
// Test query cho dashboard chart

$host = 'localhost';
$dbname = 'news-project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Testing dashboard chart query...\n\n";
    
    // Kiểm tra posts với view > 0
    $stmt = $pdo->query("SELECT id, title, view, created_at FROM posts WHERE view > 0 ORDER BY created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Posts with views > 0:\n";
    foreach($posts as $post) {
        echo "ID: {$post['id']}, Title: " . substr($post['title'], 0, 30) . "..., Views: {$post['view']}, Date: {$post['created_at']}\n";
    }
    
    echo "\n---\n\n";
    
    // Query chính từ dashboard
    $stmt = $pdo->query("
        SELECT DATE(created_at) as date, SUM(view) as total_views 
        FROM posts 
        WHERE view > 0
        GROUP BY DATE(created_at) 
        ORDER BY date DESC
        LIMIT 7
    ");
    $viewsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Chart data (grouped by date):\n";
    if(empty($viewsData)) {
        echo "No data found!\n";
    } else {
        foreach($viewsData as $data) {
            echo "Date: {$data['date']}, Total Views: {$data['total_views']}\n";
        }
    }
    
    echo "\nJSON for chart:\n";
    echo json_encode($viewsData, JSON_PRETTY_PRINT);
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>