# CashFlowLog_PHP

CashFlowLog 是一個以 **PHP + MySQL** 製作的個人現金流紀錄工具，聚焦在「固定收入、固定支出、每日支出」與「月/年統計報表」。

---

## 功能特色

- **每月固定收入管理**
  - 新增每月收入（如本俸、專業加給、其他）
  - 依月份檢視收入清單
- **每月固定支出管理**
  - 新增固定支出項目
  - 依月份檢視固定支出清單
- **每日支出管理**
  - 記錄每日支出項目、分類與支付方式
  - 依日期檢視當日支出
- **每月支出日曆**
  - 以日曆方式呈現每一天的支出分類、項目與小計/總計
- **分類月累計報表**
  - 顯示全年各分類每月累計
  - 同步計算每月固定收入、固定支出與淨收入

---

## 專案結構

```text
CashFlowLog_PHP/
├── index.php                  # 主入口，分頁切換
├── header.php                 # 上方功能分頁
├── include/
│   ├── db.php                 # PDO 連線設定
│   ├── dailyExpenseItems.php  # 每日支出分類/支付方式預設清單
│   └── fixExpenseItems.php    # 固定支出預設項目
├── monthlyIncome/             # 每月固定收入
├── fixExpense/                # 每月固定支出
├── dailyExpense/              # 每日支出
├── expenseCalendar/           # 月報表（日曆/矩陣）
└── categorySummary/           # 分類月累計與年度彙整
```

---

## 執行環境

- PHP 8.x（建議）
- MySQL 8.x / MariaDB 10.x
- Apache（XAMPP、MAMP 或 Linux LAMP 均可）

---

## 安裝與啟動

1. 將專案放到 Web 根目錄（例如 XAMPP 的 `htdocs`）。
2. 建立資料庫（預設名稱：`cashflowlog2026`）。
3. 依照下方「資料表需求」建立資料表。
4. 修改 `include/db.php` 內的連線資訊（主機、帳號、密碼、資料庫名稱）。
5. 透過瀏覽器開啟：
   - `http://localhost/CashFlowLog_PHP/index.php`

> 注意：程式中部分 iframe 路徑使用 `/CashFlowLogs_PHP/...`（多一個 `s`）。
> 若你使用的是 `CashFlowLog_PHP` 目錄名稱，請一併調整相關 iframe `src`，避免頁面載入不到。

---

## 資料表需求（最小欄位）

以下為程式目前實際使用到的欄位，請至少建立這些欄位：

### 1) `monthly_income_tab`
- `項目` (varchar)
- `金額` (int)
- `進帳日期` (date)
- `收入月份` (char(7) 或 varchar)
- `說明` (varchar / text，可為 null)

### 2) `fix_expense_tab`
- `項目` (varchar)
- `金額` (int)
- `支出日期` (date)
- `支出月份` (char(7) 或 varchar)

### 3) `daily_expense_tab`
- `項目` (varchar)
- `金額` (int)
- `支出日期` (date)
- `支出方式` (varchar)
- `分類` (varchar)

> 備註：`monthly_income_tab` 與 `fix_expense_tab` 的寫入使用了 `ON DUPLICATE KEY UPDATE`，建議你依需求建立對應唯一鍵（例如「項目 + 月份」）以符合預期行為。

---

## 使用流程建議

1. 先在「每月固定收入」建立本月固定收入。
2. 在「每月固定支出」建立固定支出。
3. 每日於「每日支出」補上當日花費。
4. 到「每月報表」觀察日曆型支出分布。
5. 到「分類月累計」檢視年度走勢與淨收入。

---

## 安全性提醒

目前 `include/db.php` 內含明碼資料庫帳密，正式環境建議：

- 改用 `.env` 或伺服器環境變數管理憑證
- 建立低權限資料庫使用者
- 不要將真實密碼提交到版本控制

---

## 後續可擴充方向

- 新增編輯/刪除功能
- 登入與多使用者帳本
- 圖表化（分類佔比、月趨勢）
- 匯出 CSV / Excel
- 預算警示與超支提醒

---

如果你要，我也可以再幫你補一份：
- `schema.sql`（可直接匯入）
- `.env.example`
- 更完整的部署文件（Windows/XAMPP 與 Linux/Apache 各一版）
