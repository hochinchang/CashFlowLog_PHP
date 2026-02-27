<?php
require_once '../include/db.php';   // PDO 連線

// 取得月份
$month = $_GET['month'] ?? date('Y-m');
$start = $month . '-01';
$end   = date('Y-m-t', strtotime($start));

// 查詢每日支出（不 GROUP，逐筆）
$sql = "
    SELECT 支出日期, 分類, 項目, 金額
    FROM daily_expense_tab
    WHERE 支出日期 BETWEEN :start AND :end
    ORDER BY 分類, 支出日期
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':start' => $start,
    ':end'   => $end
]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =======================
// 整理資料
// =======================
$data = [];             // [category][date][] = ['item'=>..., 'amount'=>...]
$dates = [];
$categories = [];
$dailyTotal = [];       // [date] => total

foreach ($rows as $row) {
    $date = $row['支出日期'];
    $cat  = $row['分類'];

    $data[$cat][$date][] = [
        'item'   => $row['項目'],
        'amount' => (int)$row['金額']
    ];

    $dates[$date] = true;
    $categories[$cat] = true;

    // 每日總計
    if (!isset($dailyTotal[$date])) {
        $dailyTotal[$date] = 0;
    }
    $dailyTotal[$date] += (int)$row['金額'];
}

$dates = array_keys($dates);
sort($dates);

$categories = array_keys($categories);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>CashFlowLog 月支出表</title>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 6px;
        vertical-align: top;
        font-size: 14px;
    }
    th {
        background: #f2f2f2;
        text-align: center;
    }
    .subtotal {
        margin-top: 4px;
        padding-top: 4px;
        border-top: 1px dashed #aaa;
        font-weight: bold;
        text-align: right;
    }
    .daily-total {
        background: #eef;
        font-weight: bold;
        text-align: right;
    }
</style>
</head>

<body>

<h2><?= date('Y年m月', strtotime($month)) ?> 支出總覽</h2>

<form method="get">
    <label>
        選擇月份：
        <input type="month" name="month" value="<?= htmlspecialchars($month) ?>">
    </label>
    <button type="submit">查詢</button>
</form>

<br>

<table>
    <tr>
        <th>分類＼日期</th>
        <?php foreach ($dates as $date): ?>
            <th><?= date('d', strtotime($date)) ?></th>
        <?php endforeach; ?>
    </tr>

    <!-- 各分類列 -->
    <?php foreach ($categories as $cat): ?>
        <tr>
            <th><?= htmlspecialchars($cat) ?></th>

            <?php foreach ($dates as $date): ?>
                <td>
                    <?php
                    $subtotal = 0;

                    if (!empty($data[$cat][$date])) {
                        foreach ($data[$cat][$date] as $row) {
                            echo htmlspecialchars($row['item']) . ' ' . $row['amount'] . "<br>";
                            $subtotal += $row['amount'];
                        }

                        // 小計
                        echo "<div class='subtotal'>{$subtotal}</div>";
                    }
                    ?>
                </td>
            <?php endforeach; ?>

        </tr>
    <?php endforeach; ?>

    <!-- 每日總計 -->
    <tr>
        <th>每日總計</th>
        <?php foreach ($dates as $date): ?>
            <td class="daily-total">
                <?= $dailyTotal[$date] ?? '' ?>
            </td>
        <?php endforeach; ?>
    </tr>
</table>

</body>
</html>

