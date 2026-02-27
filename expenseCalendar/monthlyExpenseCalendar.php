<?php
require_once '../include/db.php';

// =======================
// 基本日期設定
// =======================
$month = $_GET['month'] ?? date('Y-m');
$firstDay = $month . '-01';
$lastDay  = date('Y-m-t', strtotime($firstDay));

$startWeekday = date('w', strtotime($firstDay)); // 0=Sun
$totalDays = date('t', strtotime($firstDay));

// =======================
// 讀取資料
// =======================
$sql = "
    SELECT 支出日期, 分類, 項目, 金額
    FROM daily_expense_tab
    WHERE 支出日期 BETWEEN :start AND :end
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':start' => $firstDay,
    ':end'   => $lastDay
]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =======================
// 整理成日曆用結構
// =======================
$calendar = [];      // [date][category][] = ['item','amount']
$dailyTotal = [];    // [date] => total

foreach ($rows as $row) {
    $date = $row['支出日期'];

    $calendar[$date][$row['分類']][] = [
        'item'   => $row['項目'],
        'amount' => (int)$row['金額']
    ];

    if (!isset($dailyTotal[$date])) {
        $dailyTotal[$date] = 0;
    }
    $dailyTotal[$date] += (int)$row['金額'];
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>CashFlowLog 月支出日曆</title>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
    }
    
    th {
        background: #f0f0f0;
        text-align: center;
        height: 32px;          /* 星期列高度 */
        padding: 4px;
        font-weight: bold;
    }

    td {
        border: 1px solid #ccc;
        vertical-align: top;
        padding: 6px;
        height: 140px;         /* 日期內容高度 */
        font-size: 13px;
    }
    th {
        background: #f0f0f0;
        text-align: center;
    }
    
    /* 星期標題 */
    th.sun {
        background: #ffecec;
        color: #c62828;
    } 
    th.sat {
        background: #eef2ff;
        color: #283593;
    }

    /* 日期格 */
    td.sun {
        background: #fff5f5;
    }
    td.sat {
        background: #f5f8ff;
    }
    
    .day {
        font-weight: bold;
        margin-bottom: 4px;
    }
    .subtotal {
        border-top: 1px dashed #aaa;
        margin-top: 4px;
        text-align: right;
        font-weight: bold;
    }
    .total {
        margin-top: 4px;
        color: #003366;
        font-weight: bold;
        text-align: right;
    }
    .empty {
        background: #fafafa;
    }
</style>
</head>

<body>

<h2><?= date('Y年m月', strtotime($month)) ?> 支出日曆</h2>

<form method="get">
    <input type="month" name="month" value="<?= htmlspecialchars($month) ?>">
    <button type="submit">查詢</button>
</form>

<br>

<table>
    <tr>
        <th class="sun">日</th>
        <th>一</th>
        <th>二</th>
        <th>三</th>
        <th>四</th>
        <th>五</th>
        <th class="sat">六</th>
    </tr>

    <tr>
    <?php
    // 補前面空白
    for ($i = 0; $i < $startWeekday; $i++) {
        echo "<td class='empty'></td>";
    }

    // 當月日期
    for ($day = 1; $day <= $totalDays; $day++) {
        $date = sprintf('%s-%02d', $month, $day);
        $weekday = date('w', strtotime($date)); // 0=日, 6=六
        $class = '';

        if ($weekday == 0) $class = 'sun';
        if ($weekday == 6) $class = 'sat';

        echo "<td class='{$class}'>";

        echo "<div class='day'>{$day}</div>";

        if (!empty($calendar[$date])) {
            foreach ($calendar[$date] as $cat => $items) {
                $catTotal = 0;
                echo "<strong>{$cat}</strong><br>";

                foreach ($items as $it) {
                    echo "{$it['item']} {$it['amount']}<br>";
                    $catTotal += $it['amount'];
                }

                echo "<div class='subtotal'>{$catTotal}</div>";
            }

            echo "<div class='total'>合計 {$dailyTotal[$date]}</div>";
        }

        echo "</td>";

        // 換行（星期六）
        if (date('w', strtotime($date)) == 6) {
            echo "</tr><tr>";
        }
    }

    // 補後面空白
    $endWeekday = date('w', strtotime($lastDay));
    for ($i = $endWeekday; $i < 6; $i++) {
        echo "<td class='empty'></td>";
    }
    ?>
    </tr>
</table>

</body>
</html>

