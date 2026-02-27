<?php
require_once '../include/db.php';

// 取得表單資料
$item        = $_POST['item'] ?? '';
$category    = $_POST['category'] ?? '';
$amount      = $_POST['amount'] ?? 0;
$expense_date = $_POST['expense_date'] ?? '';
$paymentMethod = $_POST['paymentMethod'] ?? '';


// 基本檢查
if ($item === '' || $amount <= 0 || $expense_date === '') {
    die('資料不完整');
}

// SQL（MySQL PDO）
$sql = "
    INSERT INTO daily_expense_tab (項目, 金額, 支出日期, 支出方式, 分類)
    VALUES (:item,  :amount, :expense_date, :paymentMethod, :category)
    ";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':item'          => $item,
    ':amount'        => $amount,
    ':expense_date'  => $expense_date,
    ':paymentMethod' => $paymentMethod,
    ':category'      => $category
 ]);
?>
