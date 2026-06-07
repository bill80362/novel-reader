## Why

這是一個全新的個人小說發佈平台，目前環境僅有 Laravel 初始化骨架。需要從零建立完整的資料庫結構、後台管理（Filament v5）、前台閱讀體驗、SEO 基礎設施，以及 MCP HTTP Server 供 AI Agent 自動上架小說內容。

## What Changes

- **新增** `novels`、`chapters`、`categories`、`tags`、`novel_tag`、`settings` 六張資料表與對應 Eloquent Model
- **新增** Filament v5 Admin Panel，含 NovelResource、ChapterResource、CategoryResource、TagResource、SettingsPage、Dashboard
- **新增** 前台 Blade 路由與頁面：首頁、小說列表、小說詳情、章節閱讀、搜尋
- **新增** MCP HTTP Server（`laravel/mcp`），提供 6 個 AI 上架工具，slug 衝突時返回現有資料（upsert 語意），章節強制字數驗證
- **新增** SEO 基礎設施：動態 meta、Open Graph、sitemap.xml、robots.txt
- **新增** 手機閱讀體驗優化：字體大小切換、深/淺色模式、閱讀進度條、底部導覽列
- **新增** 自建瀏覽計數（`increment` 避免競態條件）+ GA4 動態注入
- **新增** `settings` 表查詢結果 Cache，避免每次渲染都打 DB

## Capabilities

### New Capabilities

- `database-schema`: 資料庫資料表設計與 Eloquent Model 關聯
- `filament-admin`: Filament v5 後台管理介面（Resources + Pages + Dashboard）
- `frontend-reader`: 前台 Blade 頁面與手機閱讀體驗
- `mcp-novel-tools`: MCP HTTP Server 與 AI 上架工具（含 slug 保護與字數驗證）
- `seo-infrastructure`: SEO meta、sitemap、robots、Schema.org 結構化資料
- `view-counting`: 自建瀏覽計數與 GA4 整合

### Modified Capabilities

（無，全新建立）

## Impact

- **資料庫**：新增 6 張資料表，需依序執行 migrations
- **Composer 套件**：已有 `filament/filament`、`laravel/mcp`；需確認 `spatie/laravel-sitemap` 是否已安裝
- **Routes**：`routes/web.php` 新增前台路由；`routes/mcp.php` 或透過 `laravel/mcp` 設定 HTTP endpoint
- **後台**：Filament Panel Provider 需設定 Admin Panel 路徑
- **前台**：`resources/views/` 新增 layout 與各頁面 Blade 元件
- **MCP**：`/mcp` HTTP endpoint 需加入 Bearer Token 保護，避免未授權呼叫
