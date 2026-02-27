<?php
$current = $_GET['page'] ?? 'monthly_income';
?>

<div class="tabs">
    <a href="index.php?page=monthly_income"
       class="<?= $current == 'monthly_income' ? 'active' : '' ?>">
        每月固定收入
    </a>

    <a href="index.php?page=fixed_expense"
       class="<?= $current == 'fixed_expense' ? 'active' : '' ?>">
        每月固定支出
    </a>

    <a href="index.php?page=daily_expense"
       class="<?= $current == 'daily_expense' ? 'active' : '' ?>">
        每日支出
    </a>
    
    <a href="index.php?page=monthly_calendar"
       class="<?= $current == 'monthly_calendar' ? 'active' : '' ?>">
        每月報表
    </a>
</div>


