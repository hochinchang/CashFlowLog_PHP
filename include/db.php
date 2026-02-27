<?php
// ===== 資料庫設定 =====
$db_host = "localhost";
$db_name = "cashflowlog2026";   // 你的資料庫名稱
$db_user = "root";          // XAMPP 預設
$db_pass = "Pamela0128--";              // XAMPP 預設通常是空的
$db_charset = "utf8mb4";

// ===== DSN（MySQL 專用）=====
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";

try {
    $pdo = new PDO(
        $dsn,
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 出錯就丟例外
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // 陣列方式
            PDO::ATTR_EMULATE_PREPARES   => false,                  // 使用真正預處理
        ]
    );
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

