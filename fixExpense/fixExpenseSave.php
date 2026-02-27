<?php
require_once '../include/db.php';

// 取得表單資料
$item        = $_POST['item'] ?? '';
$amount      = $_POST['amount'] ?? 0;
$expense_date = $_POST['expense_date'] ?? '';


// 基本檢查
if ($item === '' || $amount <= 0 || $expense_date === '') {
    die('資料不完整');
}
// 算出 YYYY-MM
$expense_month = date('Y-m', strtotime($expense_date));

// SQL（MySQL PDO）
$sql = "
    INSERT INTO fix_expense_tab (項目, 金額, 支出日期, 支出月份)
    VALUES (:item, :amount, :expense_date, :expense_month)
    ON DUPLICATE KEY UPDATE
    金額 = VALUES(金額),
    支出日期 = VALUES(支出日期)
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':item'        => $item,
    ':amount'      => $amount,
    ':expense_date' => $expense_date,
    ':expense_month' => $expense_month
    
 ]);
/*
// 儲存完成後導回表單或清單頁
header("Location: monthlyIncomeForm.php");
exit;
*/
