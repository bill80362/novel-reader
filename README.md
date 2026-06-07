# Novel Reader

個人小說發佈平台，支援 AI 自動上架，以手機閱讀體驗為核心。

---

## 環境需求

- PHP 8.4+
- MySQL 8.x
- Node.js（前端資源編譯）

---

## 安裝

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
```

---

## 啟動開發伺服器

```bash
composer run dev
```

或分別執行：

```bash
php artisan serve
npm run dev
```

---

## 後台管理

網址：`/admin`

| 模組 | 說明 |
|------|------|
| 小說管理 | 新增/編輯小說、上傳封面、切換狀態（草稿 / 已發佈 / 完結） |
| 章節管理 | 新增/編輯章節、Rich Text 編輯器、字數自動計算 |
| 分類管理 | 新增/編輯分類與封面 |
| 標籤管理 | 新增/編輯標籤 |
| 系統設定 | GA4 Measurement ID、網站名稱等全域設定 |
| 儀表板 | 總瀏覽量、Top 10 排行、最新更新 |

---

## 前台路由

| 路徑 | 說明 |
|------|------|
| `/` | 首頁（精選 Banner、最新章節、分類導覽） |
| `/novels` | 小說列表（分類/標籤篩選、排序） |
| `/novels/{slug}` | 小說詳情（封面、簡介、章節列表） |
| `/novels/{slug}/{chapter-slug}` | 章節閱讀頁 |
| `/search` | 全文搜尋 |

---

## MCP Server（AI 自動上架）

MCP Server 端點由 `laravel/mcp` 提供，供 AI Agent 呼叫以自動建立內容。

### 設定方式

1. 在 `.env` 設定 MCP Bearer Token：

```env
MCPAPP_SECRET=your-strong-secret-token
```

2. 將 AI Agent / MCP Client 的 server URL 指向本站的 MCP 端點：

```text
https://your-domain.com/mcp
```

3. 在請求標頭加入 Bearer Token：

```http
Authorization: Bearer your-strong-secret-token
```

4. 開發環境可直接使用本機網址，例如：

```text
http://127.0.0.1:8000/mcp
```

若 token 錯誤或未提供，伺服器會回傳 `401 Unauthorized`。

| 工具 | 說明 |
|------|------|
| `list_categories` | 取得所有分類（含 ID） |
| `list_tags` | 取得所有標籤 |
| `create_novel` | 建立新小說 |
| `create_chapter` | 新增章節到指定小說 |
| `publish_novel` | 將小說狀態設為已發佈 |
| `get_novel_stats` | 取得小說／章節瀏覽數據 |

設定 MCP 連線時，將 MCP Server URL 指向本站的 MCP 端點（見 `config/mcp.php`）。

---

## 執行測試

```bash
php artisan test --compact
```

---

## 程式碼格式化

```bash
vendor/bin/pint --dirty
```
