<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>新增每日支出</title>
    <style>
        form {
            width: 360px;
            padding: 16px;
            border: 1px solid #ccc;
        }
        label {
            display: block;
            margin-top: 12px;
        }
        input, select, button {
            width: 100%;
            padding: 6px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
…
<h2>新增每日支出</h2>
<?php require_once '../include/dailyExpenseItems.php'; ?>
<form method="post" action="dailyExpenseSave.php" target="hiddenFrame" id="expenseForm">
    <label>
        項目
        <input type="text" name="item" required>
    </label>
    <label>
        分類
        <select type="item" name="category" required>
            <option value="">請選擇</option>
            <?php foreach ($categoryItems as $categoryItem): ?>
            <option value="<?= htmlspecialchars($categoryItem) ?>">
                <?= htmlspecialchars($categoryItem) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>
        金額
        <input type="number" name="amount" required>
    </label>

    <label>
        支出日
        <input type="date" name="expense_date" id="expenseDate" value="<?= date('Y-m-d') ?>" required>
    </label>

    <label>
        支出方式
        <select type="item" name="paymentMethod" regured>
        <?php foreach ($paymentMethodItems as $paymentMethodItem): ?>
            <option value="<?= htmlspecialchars($paymentMethodItem) ?>">
                <?= htmlspecialchars($paymentMethodItem) ?>
            </option>
        <?php endforeach; ?>
        </select>   
    </label>

    <button type="submit">儲存</button>
</form>
<!-- 隱藏 iframe -->
<iframe name="hiddenFrame" style="display:none;"></iframe>
<script>
const expenseDateInput = document.getElementById('expenseDate');    
const expenseForm = document.getElementById('expenseForm');

expenseDateInput.addEventListener('change', function () {
    const dateValue = this.value;
    if (!dateValue) return;

    const day = dateValue.substring(0, 10);

    parent.frames['expenseList'].location.href =
        'dailyExpense.php?day=' + day;
});

/* ② 表單送出 → 存完再更新一次（保險） */
expenseForm.addEventListener('submit', function () {
    setTimeout(() => {
        const dateValue = expenseDateInput.value;
        if (!dateValue) return;

        const day = dateValue.substring(0, 10);

        parent.frames['expenseList'].location.href =
            'dailyExpense.php?day=' + day;
    }, 300);
});
</script>
</body>
</html>


