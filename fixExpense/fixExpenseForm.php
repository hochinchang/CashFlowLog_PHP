<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>新增每月收入</title>
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
<h2>新增每月固定支出</h2>
<?php require_once '../include/fixExpenseItems.php'; ?>
<form method="post" action="fixExpenseSave.php" target="hiddenFrame"
      id="expenseForm">
    <label>
        項目
        <select name="item" required>
            <option value="">請選擇</option>
            <?php foreach ($fixExpenseItems as $item): ?>
            <option value="<?= htmlspecialchars($item) ?>">
                <?= htmlspecialchars($item) ?>
            </option>
            <?php endforeach; ?>
        </select>   
    </label>

    <label>
        金額
        <input type="number" name="amount" required>
    </label>

    <label>
        支出日期
        <input type="date" name="expense_date" id="expenseDate" value="<?= date('Y-m-d') ?>" required>
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

    const month = dateValue.substring(0, 7);

    parent.frames['expenseList'].location.href =
        'fixExpense.php?month=' + month;
});

/* ② 表單送出 → 存完再更新一次（保險） */
expenseForm.addEventListener('submit', function () {
    setTimeout(() => {
        const dateValue = expenseDateInput.value;
        if (!dateValue) return;

        const month = dateValue.substring(0, 7);

        parent.frames['expenseList'].location.href =
            'fixExpense.php?month=' + month;
    }, 300);
});
</script>
</body>
</html>


