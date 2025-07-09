<?php
// データベース設定
// ローカル環境（XAMPP）とサクラサーバーで自動切り替え
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    // ローカル環境（XAMPP）
    $host = 'localhost';
    $dbname = 'survey_db';
    $username = 'root';
    $password = '';
} else {
    // サクラサーバー環境
    $host = ''; // サクラサーバーのMySQLホスト名に変更
    $dbname = ''; // データベース名に変更
    $username = ''; // ユーザー名に変更
    $password = ''; // パスワードに変更
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('データベース接続エラー: ' . $e->getMessage());
}

// セッション開始
session_start();
?>
