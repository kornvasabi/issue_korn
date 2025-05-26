<?php
    $host = 'localhost';
    $port = '3309'; // ✅ กำหนดพอร์ตที่นี่
    $db = 'issue_tracker_korn';
    $user = 'root';
    $pass = '1234';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        exit('Database connection failed: ' . $e->getMessage());
    }
?>
