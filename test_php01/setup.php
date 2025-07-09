<?php
require_once 'config.php';

// テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS surveys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    satisfaction ENUM('very_satisfied', 'satisfied', 'neutral', 'dissatisfied', 'very_dissatisfied') NOT NULL,
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $pdo->exec($sql);
    echo "データベースのセットアップが完了しました。";
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>