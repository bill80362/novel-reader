## Context

全新 Laravel 13 + Filament v5 個人小說平台，目前僅有框架骨架（User model、基礎 migrations）。無任何業務邏輯。需要一次性建立完整系統，包含資料庫、後台、前台、MCP HTTP Server。

現有依賴：`filament/filament ^5.0`、`laravel/framework ^13.8`。
尚未安裝：`laravel/mcp`、`spatie/laravel-sitemap`。

## Goals / Non-Goals

**Goals:**
- 建立完整資料庫 schema 與 Eloquent Model 關聯
- Filament v5 後台 CRUD 管理
- 手機優先的前台 Blade 閱讀體驗
- MCP HTTP Server 供 AI Agent 安全上架小說
- SEO 基礎設施（sitemap、meta、Schema.org）
- 自建瀏覽計數 + GA4 動態注入

**Non-Goals:**
- 使用者登入 / 會員系統（無讀者帳號）
- 付費牆 / 訂閱功能
- 即時通知或 WebSocket
- 多國語言 i18n

## Decisions

### D1：MCP 使用 HTTP 模式 + Bearer Token 認證

**決策**：使用 `laravel/mcp` 的 HTTP transport，endpoint 為 `/mcp`，以 `MCPAPP_SECRET` 環境變數作為 Bearer Token 驗證。

**理由**：HTTP 模式讓 AI Agent 可從任何地方呼叫，不需要 SSH 或本地部署。Bearer Token 是 MCP HTTP 規範的標準認證方式，實作成本低。

**替代方案考慮**：
- stdio 模式：僅適合本機 Claude Desktop，不適合遠端 Agent
- API Key 自製 Middleware：重複造輪子，不如用標準 Bearer Token

---

### D2：`create_novel` 採 slug-based upsert 語意

**決策**：`create_novel` MCP Tool 在 slug 已存在時，返回現有小說資料（含 `novel_id`），而不是拋出錯誤。Response 加入 `created: false` 欄位告知 AI。

**理由**：AI Agent 可能在重試或多輪對話中重複呼叫。若直接拋錯，AI 必須額外判斷錯誤類型；若靜默 upsert，AI 可繼續使用返回的 `novel_id` 上傳章節，流程不中斷。

**替代方案考慮**：
- 直接報 422 錯誤：AI 需要額外錯誤處理邏輯
- 不做保護：slug 衝突導致 DB 500 Error，AI 無法恢復

**Response 結構範例**：
```json
{
  "novel_id": 1,
  "slug": "yi-shi-jie-chuan-yue",
  "created": false,
  "message": "Novel with this slug already exists. Use novel_id to add chapters."
}
```

---

### D3：章節字數在 MCP Tool 層驗證，word_count 由 Model Observer 自動計算

**決策**：
- `create_chapter` Tool 在呼叫時驗證 `mb_strlen($content)` 在 5,000 ~ 15,000 字之間，超出返回清晰錯誤訊息
- `Chapter` Model 的 `word_count` 欄位透過 `creating` / `updating` Observer 事件自動計算（`mb_strlen(strip_tags($content))`）

**理由**：MCP Tool 是 AI 的邊界，在此驗證可讓 AI 立即知道需要分章。Observer 確保不論從後台或 MCP 寫入，word_count 永遠準確。

**字數計算**：`mb_strlen(strip_tags($content))` 去除 HTML tag 後計算，符合「中文字數」的直覺。

---

### D4：`view_count` 使用 `increment()` 避免競態條件

**決策**：章節頁 Controller 使用 `Chapter::where('id', $id)->increment('view_count')` 和 `Novel::where('id', $novel_id)->increment('view_count')`，不讀取後寫入。

**理由**：`increment()` 轉為 SQL `UPDATE ... SET view_count = view_count + 1`，資料庫層面原子操作，避免高並發下的資料遺失。

---

### D5：`settings` 表查詢結果使用 Cache::remember

**決策**：`Setting::get($key)` helper 使用 `Cache::remember("setting:{$key}", 3600, ...)` 快取 1 小時。後台 SettingsPage 儲存後觸發 `Cache::forget("setting:{$key}")`。

**理由**：GA4 ID、網站名稱等設定幾乎不變動，每次 Blade 渲染都打 DB 浪費資源。

---

### D6：章節 slug 在同一小說內唯一

**決策**：`chapters` 表建立 `UNIQUE(novel_id, slug)` 複合唯一索引，而非全域唯一。

**理由**：不同小說的第一章都叫「第一章：開始」是合理的，全域唯一會造成不必要的命名衝突。路由 `/novels/{novel-slug}/{chapter-slug}` 先定位小說再定位章節，複合唯一即可保證正確性。

---

### D7：需新增 `laravel/mcp` 與 `spatie/laravel-sitemap`

**決策**：透過 `composer require` 安裝這兩個套件。

**現有 composer.json 缺少**：
- `laravel/mcp`：MCP HTTP Server
- `spatie/laravel-sitemap`：自動生成 sitemap.xml

## Risks / Trade-offs

| 風險 | 緩解措施 |
|------|----------|
| AI Agent 上傳超長章節（>15,000 字）→ MCP Tool 拋錯，AI 可能陷入重試循環 | Tool description 明確說明字數限制與分章建議，返回錯誤時附上分段提示 |
| MCP Bearer Token 洩漏 → 任何人可寫入小說 | Token 存放於 `.env`，不進版控；可考慮加入 IP 白名單 Middleware |
| 大量章節的 sitemap 生成耗時 → 影響 web 請求 | `spatie/laravel-sitemap` 支援排程生成，使用 `php artisan sitemap:generate` 每日定時執行 |
| Filament v5 仍在快速演進，部分 API 可能有 breaking change | 固定在 `^5.0`，升版前先查 changelog |
| `settings` Cache 在部署後未清除 → 舊設定殘留 | 在 `php artisan config:cache` 或 `php artisan optimize:clear` 時一併清除 |

## Open Questions

- `spatie/laravel-sitemap` 是否已在 vendor 中？（目前 composer.json 未列出）→ 需要在 D7 實作時確認
- MCP endpoint 路徑：使用 `/mcp` 還是 `/api/mcp`？建議 `/mcp` 獨立路徑，避免與 API 版本管理混淆
- 封面圖 WebP 自動轉換的時機：Filament 上傳時用 Intervention Image，還是 Observer？→ 建議 Filament FileUpload component 的 `->saveUploadedFileUsing()` callback 處理
