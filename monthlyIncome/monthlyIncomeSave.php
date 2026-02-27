<?php
require_once '../include/db.php';

// 取得表單資料
$item        = $_POST['item'] ?? '';
$amount      = $_POST['amount'] ?? 0;
$income_date = $_POST['income_date'] ?? '';
$note        = $_POST['note'] ?? '';

// 基本檢查
if ($item === '' || $amount <= 0 || $income_date === '') {
    die('資料不完整');
}
// 算出 YYYY-MM
$month = date('Y-m', strtotime($income_date));

// ⭐ 核心規則就在這一行
$income_month = ($item === '其他') ? null : $month;

// SQL（MySQL PDO）
$sql = "
    INSERT INTO monthly_income_tab (項目, 金額, 進帳日期, 收入月份,說明)
    VALUES (:item, :amount, :income_date,:income_month, :note)
    ON DUPLICATE KEY UPDATE
    金額 = VALUES(金額),
    進帳日期 = VALUES(進帳日期),
    說明 = VALUES(說明)
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':item'        => $item,
    ':amount'      => $amount,
    ':income_date' => $income_date,
    ':income_month'=> $income_month,
    ':note'        => $note
 ]);
/*
// 儲存完成後導回表單或清單頁
header("Location: monthlyIncomeForm.php");
exit;
*/
