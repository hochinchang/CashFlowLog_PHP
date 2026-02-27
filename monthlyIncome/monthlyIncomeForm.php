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
<h2>新增每月固定收入</h2>

<form method="post" action="monthlyIncomeSave.php" target="hiddenFrame"
      id="incomeForm">
    <label>
        項目
        <select name="item" required>
            <option value="">請選擇</option>
            <option value="本俸">本俸</option>
            <option value="專業加給">專業加給</option>
            <option value="其他">其他</option>
        </select>
    </label>

    <label>
        金額
        <input type="number" name="amount" required>
    </label>

    <label>
        進帳日期
        <input type="date" name="income_date" id="incomeDate" value="<?= date('Y-m-d') ?>" required>
    </label>

    <label>
        備註
        <input type="text" name="note">
    </label>

    <button type="submit">儲存</button>
</form>
<!-- 隱藏 iframe -->
<iframe name="hiddenFrame" style="display:none;"></iframe>

<script>
const incomeDateInput = document.getElementById('incomeDate');    
const incomeForm = document.getElementById('incomeForm');

incomeDateInput.addEventListener('change', function () {
    const dateValue = this.value;
    if (!dateValue) return;

    const month = dateValue.substring(0, 7);

    parent.frames['incomeList'].location.href =
        'monthlyIncome.php?month=' + month;
});

/* ② 表單送出 → 存完再更新一次（保險） */
incomeForm.addEventListener('submit', function () {
    setTimeout(() => {
        const dateValue = incomeDateInput.value;
        if (!dateValue) return;

        const month = dateValue.substring(0, 7);

        parent.frames['incomeList'].location.href =
            'monthlyIncome.php?month=' + month;
    }, 300);
});
</script>
</body>
</html>


