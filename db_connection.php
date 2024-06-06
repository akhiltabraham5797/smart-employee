<?php
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'sems');
    define('CHARSET', 'utf8mb4');

    // Connecting the PHP to the Mysql Database - sems
    try {
        $data_source_name = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".CHARSET;
        $pdo = new PDO($data_source_name, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
    } catch (PDOException $e) {
        die("Connnection failed: ".$e->getMessage());
    }