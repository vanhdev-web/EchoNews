<?php

echo "=== Database Connection Test ===\n";

// Configuration từ index_new_mvc.php
$config = [
    'host' => 'localhost',
    'port' => '3306', 
    'dbname' => 'news-project',
    'username' => 'root',
    'password' => ''
];

echo "Trying to connect to MySQL...\n";
echo "Host: {$config['host']}:{$config['port']}\n";
echo "Database: {$config['dbname']}\n";
echo "Username: {$config['username']}\n";

try {
    // Test kết nối PDO
    $dsn = "mysql:host={$config['host']};port={$config['port']};charset=utf8";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ MySQL connection: SUCCESS\n";
    
    // Test database tồn tại
    $databases = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Available databases: " . implode(', ', $databases) . "\n";
    
    if (in_array($config['dbname'], $databases)) {
        echo "✅ Database '{$config['dbname']}': EXISTS\n";
        
        // Test kết nối với database
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        
        // Test tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables: " . implode(', ', $tables) . "\n";
        
        echo "✅ Database connection with '{$config['dbname']}': SUCCESS\n";
        
    } else {
        echo "❌ Database '{$config['dbname']}': NOT FOUND\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Connection FAILED: " . $e->getMessage() . "\n";
    
    // Suggestions
    echo "\n=== Troubleshooting ===\n";
    echo "1. Check if XAMPP MySQL is running\n";
    echo "2. Check MySQL port (default: 3306)\n";
    echo "3. Verify MySQL credentials\n";
    echo "4. Check if database 'news-project' exists\n";
}

echo "\n=== End Test ===\n";