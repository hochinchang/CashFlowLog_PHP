<?php
$page = $_GET['page'] ?? 'monthly_income'; // 預設頁面
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>CashFlowLog</title>
    <style>
        body { font-family: Arial; margin: 0; }
        .tabs {
            display: flex;
            background: #333;
        }
        .tabs a {
            color: #fff;
            padding: 14px 20px;
            text-decoration: none;
        }
        .tabs a.active {
            background: #555;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="content">
    <?php
    switch ($page) {
        case 'monthly_income':
            include 'monthlyIncome/index.php';
            break;

        case 'fixed_expense':
            include 'fixExpense/index.php';
            break;

        case 'daily_expense':
            include 'dailyExpense/index.php';
            break;
        case 'monthly_calendar':
            include 'expenseCalendar/index.php';
            break;
        default:
            echo "<h2>頁面不存在</h2>";
    }
    ?>
</div>

</body>
</html>


