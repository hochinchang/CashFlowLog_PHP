<html>
<head>
        <meta charset="UTF-8">
        <title></title>
</head>
<body>       

<?php
        require_once '../include/db.php';
        $day = $_GET['day'] ?? date('Y-m-d');
        [$yy,$mm,$dd] = explode('-', $day);
        $sql = "SELECT * FROM daily_expense_tab WHERE 支出日期 = :day";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':day' => $day
            ]);
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
         
 ?>
     <h2><?=$yy ?>年<?=$mm ?>月<?=$dd ?>日支出</h2>    
    <table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>項目</th>
            <th>金額</th>
            <th>支出日期</th>
            <th>支出方式</th>
            <th>分類</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data)): ?>
            <tr>
                <td colspan="5">尚無資料</td>
            </tr>
        <?php else: ?>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['項目'] ?></td>
                    <td style="text-align:right;">
                        <?= number_format($row['金額']) ?>
                    </td>
                    <td><?= $row['支出日期'] ?></td>
                    <td><?= $row['支出方式'] ?></td>
                    <td><?= $row['分類'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table> 
   
 
</table>    
</body>
</html>
       
