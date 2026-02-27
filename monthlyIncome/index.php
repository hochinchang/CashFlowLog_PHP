<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>CashFlowLog｜每月收入</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, "Microsoft JhengHei", sans-serif;
            background: #f5f6fa;
        }

        header {
            background: #2c3e50;
            color: white;
            padding: 12px 20px;
            font-size: 20px;
        }

        .container {
            display: flex;
            height: calc(100vh - 52px);
        }

        .left {
            flex: 4;
            background: #ffffff;
            border-right: 1px solid #ddd;
        }

        .right {
            flex: 6;
            background: #fafafa;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>

<header>
    CashFlowLog｜每月收入管理
</header>

<div class="container">
    <div class="left">
        <iframe src="/CashFlowLogs_PHP/monthlyIncome/monthlyIncomeForm.php"></iframe>
    </div>
    <div class="right">
        <iframe src="/CashFlowLogs_PHP/monthlyIncome/monthlyIncome.php" name="incomeList"></iframe>
    </div>
</div>

</body>
</html>

