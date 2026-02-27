<html>
<head>
        <meta charset="UTF-8">
        <title></title>
</head>
<body> 
    ...
    <?php
        require_once '../include/db.php';
        $month = $_GET['month'] ?? date('Y-m');
        [$yy,$mm] = explode('-', $month);
        $sql = "SELECT * FROM fix_expense_tab WHERE 支出日期 BETWEEN :start AND :end ;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':start' => "$yy-$mm-01",
            ':end'   => date('Y-m-t', strtotime("$yy-$mm-01"))]);
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
     
    ?>
   

    <h2><?=$yy ?>年<?=$mm ?>月固定支出</h2>    
    <table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>項目</th>
            <th>金額</th>
            <th>支出日期</th>    
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data)): ?>
            <tr>
                <td colspan="4">尚無資料</td>
            </tr>
        <?php else: ?>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['項目'] ?></td>
                    <td style="text-align:right;">
                        <?= number_format($row['金額']) ?>
                    </td>
                    <td><?= $row['支出日期'] ?></td>                 
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
       
