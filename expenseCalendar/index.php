<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>CashFlowLog｜每月支出報表</title>
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
        
        .content {
        width: 100%;
        height: 100%;
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
    CashFlowLog｜每月支出報表
</header>

<div class="container">
    <div class="content">
        <iframe src="/CashFlowLogs_PHP/expenseCalendar/monthlyExpenseCalendar.php"></iframe>
    </div>
</div>

</body>
</html>

