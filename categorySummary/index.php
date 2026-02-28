<?php
require_once __DIR__ . '/../include/db.php';

$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

$sql = "
    SELECT
        MONTH(支出日期) AS month_num,
        分類,
        SUM(金額) AS month_total
    FROM daily_expense_tab
    WHERE YEAR(支出日期) = :year
    GROUP BY MONTH(支出日期), 分類
    ORDER BY 分類, MONTH(支出日期)
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':year' => $year]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fixedExpenseSql = "
    SELECT
        MONTH(支出日期) AS month_num,
        SUM(金額) AS month_total
    FROM fix_expense_tab
    WHERE YEAR(支出日期) = :year
    GROUP BY MONTH(支出日期)
";
$fixedExpenseStmt = $pdo->prepare($fixedExpenseSql);
$fixedExpenseStmt->execute([':year' => $year]);
$fixedExpenseRows = $fixedExpenseStmt->fetchAll(PDO::FETCH_ASSOC);

$fixedIncomeSql = "
    SELECT
        MONTH(進帳日期) AS month_num,
        SUM(金額) AS month_total
    FROM monthly_income_tab
    WHERE YEAR(進帳日期) = :year
    GROUP BY MONTH(進帳日期)
";
$fixedIncomeStmt = $pdo->prepare($fixedIncomeSql);
$fixedIncomeStmt->execute([':year' => $year]);
$fixedIncomeRows = $fixedIncomeStmt->fetchAll(PDO::FETCH_ASSOC);

$categories = [];
$monthlyByCategory = [];

foreach ($rows as $row) {
    $category = $row['分類'];
    $monthNum = (int)$row['month_num'];
    $amount = (int)$row['month_total'];

    $categories[$category] = true;
    $monthlyByCategory[$category][$monthNum] = $amount;
}

$categoryList = array_keys($categories);
sort($categoryList);

$months = range(1, 12);
$cumulativeByCategory = [];
$monthGrandTotal = array_fill(1, 12, 0);
$monthFixedExpenseTotal = array_fill(1, 12, 0);
$monthFixedIncomeTotal = array_fill(1, 12, 0);

foreach ($categoryList as $category) {
    $running = 0;

    foreach ($months as $monthNum) {
        $monthAmount = $monthlyByCategory[$category][$monthNum] ?? 0;
        $running += $monthAmount;

        $cumulativeByCategory[$category][$monthNum] = [
            'month' => $monthAmount,
            'cumulative' => $running,
        ];

        $monthGrandTotal[$monthNum] += $monthAmount;
    }
}

foreach ($fixedExpenseRows as $row) {
    $monthNum = (int)$row['month_num'];
    $monthFixedExpenseTotal[$monthNum] = (int)$row['month_total'];
}

foreach ($fixedIncomeRows as $row) {
    $monthNum = (int)$row['month_num'];
    $monthFixedIncomeTotal[$monthNum] = (int)$row['month_total'];
}

$runningGrandTotal = 0;
$monthGrandCumulative = [];
$runningFixedExpenseTotal = 0;
$monthFixedExpenseCumulative = [];
$runningFixedIncomeTotal = 0;
$monthFixedIncomeCumulative = [];
$runningNetIncomeTotal = 0;
$monthNetIncomeTotal = [];
$monthNetIncomeCumulative = [];
foreach ($months as $monthNum) {
    $runningGrandTotal += $monthGrandTotal[$monthNum];
    $monthGrandCumulative[$monthNum] = $runningGrandTotal;

    $runningFixedExpenseTotal += $monthFixedExpenseTotal[$monthNum];
    $monthFixedExpenseCumulative[$monthNum] = $runningFixedExpenseTotal;

    $runningFixedIncomeTotal += $monthFixedIncomeTotal[$monthNum];
    $monthFixedIncomeCumulative[$monthNum] = $runningFixedIncomeTotal;

    $monthNetIncomeTotal[$monthNum] = $monthFixedIncomeTotal[$monthNum] - ($monthGrandTotal[$monthNum] + $monthFixedExpenseTotal[$monthNum]);
    $runningNetIncomeTotal += $monthNetIncomeTotal[$monthNum];
    $monthNetIncomeCumulative[$monthNum] = $runningNetIncomeTotal;
}

$hasData = !empty($categoryList)
    || array_sum($monthFixedExpenseTotal) !== 0
    || array_sum($monthFixedIncomeTotal) !== 0;
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>分類月累計統計</title>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        font-size: 14px;
        text-align: right;
    }

    th {
        background: #f3f4f6;
        text-align: center;
    }

    td.category {
        text-align: left;
        white-space: nowrap;
        font-weight: bold;
        background: #fafafa;
    }

    .month-amount {
        color: #6b7280;
        font-size: 12px;
        margin-top: 2px;
    }

    .grand-total-row {
        background: #eef6ff;
        font-weight: bold;
    }

    .empty-message {
        margin-top: 12px;
        color: #666;
    }
</style>
</head>
<body>

<h2><?= htmlspecialchars((string)$year) ?> 年各分類每月累計金額</h2>

<form method="get">
    <label>
        年度：
        <input type="number" name="year" min="2000" max="2100" value="<?= htmlspecialchars((string)$year) ?>">
    </label>
    <button type="submit">查詢</button>
</form>

<?php if (!$hasData): ?>
    <p class="empty-message">此年度尚無支出或收入資料。</p>
<?php else: ?>
    <table>
        <tr>
            <th>分類\月份</th>
            <?php foreach ($months as $monthNum): ?>
                <th><?= $monthNum ?> 月</th>
            <?php endforeach; ?>
        </tr>

        <?php foreach ($categoryList as $category): ?>
            <tr>
                <td class="category"><?= htmlspecialchars($category) ?></td>

                <?php foreach ($months as $monthNum): ?>
                    <?php $cell = $cumulativeByCategory[$category][$monthNum]; ?>
                    <td>
                        <?= number_format($cell['cumulative']) ?>
                        <div class="month-amount">當月 <?= number_format($cell['month']) ?></div>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>

        <tr class="grand-total-row">
            <td class="category">總計</td>
            <?php foreach ($months as $monthNum): ?>
                <td>
                    <?= number_format($monthGrandCumulative[$monthNum]) ?>
                    <div class="month-amount">當月 <?= number_format($monthGrandTotal[$monthNum]) ?></div>
                </td>
            <?php endforeach; ?>
        </tr>

        <tr class="grand-total-row">
            <td class="category">每月固定支出總計</td>
            <?php foreach ($months as $monthNum): ?>
                <td>
                    <?= number_format($monthFixedExpenseCumulative[$monthNum]) ?>
                    <div class="month-amount">當月 <?= number_format($monthFixedExpenseTotal[$monthNum]) ?></div>
                </td>
            <?php endforeach; ?>
        </tr>

        <tr class="grand-total-row">
            <td class="category">每月固定收入總計</td>
            <?php foreach ($months as $monthNum): ?>
                <td>
                    <?= number_format($monthFixedIncomeCumulative[$monthNum]) ?>
                    <div class="month-amount">當月 <?= number_format($monthFixedIncomeTotal[$monthNum]) ?></div>
                </td>
            <?php endforeach; ?>
        </tr>

        <tr class="grand-total-row">
            <td class="category">淨收入（固定收入 - 全部支出）</td>
            <?php foreach ($months as $monthNum): ?>
                <td>
                    <?= number_format($monthNetIncomeCumulative[$monthNum]) ?>
                    <div class="month-amount">當月 <?= number_format($monthNetIncomeTotal[$monthNum]) ?></div>
                </td>
            <?php endforeach; ?>
        </tr>
    </table>
<?php endif; ?>

</body>
</html>
