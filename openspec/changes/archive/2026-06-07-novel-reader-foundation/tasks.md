## 1. 套件安裝與環境設定

- [x] 1.1 安裝 `laravel/mcp`：`composer require laravel/mcp`
- [x] 1.2 安裝 `spatie/laravel-sitemap`：`composer require spatie/laravel-sitemap`
- [x] 1.3 在 `.env` 加入 `MCPAPP_SECRET` 變數（MCP Bearer Token）
- [x] 1.4 確認 `filament/filament ^5.0` 已正確安裝並可執行 `php artisan filament:install --panels`

## 2. 資料庫 Migrations 與 Models

- [x] 2.1 建立 `categories` 資料表 migration（id, name, slug UNIQUE, description, cover_image, timestamps）
- [x] 2.2 建立 `tags` 資料表 migration（id, name, slug UNIQUE, timestamps）
- [x] 2.3 建立 `novels` 資料表 migration（含所有欄位：title, slug UNIQUE, description, cover_image, category_id FK, status ENUM, is_featured, seo_title, seo_description, seo_keywords, view_count, timestamps）
- [x] 2.4 建立 `chapters` 資料表 migration（novel_id FK, chapter_number, title, slug, content MEDIUMTEXT, word_count, view_count, published_at, timestamps；UNIQUE index: novel_id + slug）
- [x] 2.5 建立 `novel_tag` pivot 資料表 migration（novel_id FK, tag_id FK）
- [x] 2.6 建立 `settings` 資料表 migration（key VARCHAR UNIQUE, value TEXT NULL, timestamps）
- [x] 2.7 執行所有 migrations：`php artisan migrate`
- [x] 2.8 建立 `Category` Model（含 `novels` HasMany 關聯）與 Factory/Seeder
- [x] 2.9 建立 `Tag` Model（含 `novels` BelongsToMany 關聯）與 Factory/Seeder
- [x] 2.10 建立 `Novel` Model（含 `category` BelongsTo、`chapters` HasMany、`tags` BelongsToMany 關聯；`published` scope；`status` enum cast）與 Factory/Seeder
- [x] 2.11 建立 `Chapter` Model（含 `novel` BelongsTo 關聯）與 Factory/Seeder
- [x] 2.12 建立 `Setting` Model，實作靜態 `get($key, $default)` 與 `set($key, $value)` 方法，含 `Cache::remember` / `Cache::forget`
- [x] 2.13 建立 `ChapterObserver`，在 `creating` / `updating` 事件自動計算 `word_count = mb_strlen(strip_tags($content))`，並在 `AppServiceProvider` 中註冊

## 3. Filament Admin Panel

- [x] 3.1 執行 `php artisan filament:install --panels --no-interaction` 建立 AdminPanelProvider
- [x] 3.2 建立 `CategoryResource`（含列表、建立、編輯、刪除；slug 自動從 name 生成）
- [x] 3.3 建立 `TagResource`（含列表、建立、編輯、刪除）
- [x] 3.4 建立 `NovelResource`（含列表、建立、編輯；封面圖上傳 WebP 轉換；slug 自動生成（onBlur live）；分類 BelongsTo Select；標籤 BelongsToMany MultiSelect；狀態 Select；SEO Fieldset；is_featured Toggle；批次發佈/下架 BulkAction）
- [x] 3.5 建立 `ChapterResource`（含列表、建立、編輯；所屬小說 Select；Rich Text 編輯器；word_count 唯讀 TextInput；published_at DateTimePicker）
- [x] 3.6 建立 `SettingsPage`（Filament Page；表單欄位：ga4_id、site_name、social_line、social_twitter、social_facebook；儲存後清除相關 Cache）
- [x] 3.7 建立 `Dashboard` 頁面（Stats Overview：總瀏覽量；Top 10 小說/章節 Table Widgets；最新更新章節；分類統計圖表）
- [x] 3.8 執行 `php artisan filament:make-user --no-interaction` 建立測試管理員帳號

## 4. MCP HTTP Server

- [x] 4.1 建立 MCP Server 類別（`php artisan make:mcp-server NovelMcpServer`）並在 `mcp.php` 或 `AppServiceProvider` 設定 HTTP transport
- [x] 4.2 實作 `list_categories` Tool（返回所有分類 id 與 name）
- [x] 4.3 實作 `list_tags` Tool（返回所有標籤 id 與 name）
- [x] 4.4 實作 `create_novel` Tool（slug upsert 語意：slug 已存在返回現有資料 + `created: false`；slug 不存在時新建；Tool description 說明 slug 代表小說唯一身份）
- [x] 4.5 實作 `create_chapter` Tool（`mb_strlen(strip_tags($content))` 驗證 5,000~15,000 字；超出返回清晰錯誤；Tool description 說明字數限制與分章建議）
- [x] 4.6 實作 `publish_novel` Tool（更新 status 為 published；novel_id 不存在返回錯誤）
- [x] 4.7 實作 `get_novel_stats` Tool（返回總 view_count + 章節排行前 10）
- [x] 4.8 設定 MCP endpoint `/mcp` 的 Bearer Token 認證 Middleware（從 `MCPAPP_SECRET` env 讀取）
- [x] 4.9 驗證 MCP Server 可正常啟動：`php artisan mcp:start` 或 HTTP 端點測試

## 5. 前台路由與頁面

- [x] 5.1 建立前台 layout（`layouts/app.blade.php`）：GA4 動態注入、基礎 HTML 結構、Alpine.js、Tailwind
- [x] 5.2 建立首頁 `/`（精選 Banner、最新更新章節、分類快速導覽）
- [x] 5.3 建立小說列表頁 `/novels`（分類/標籤篩選、排序切換、URL query string 狀態保持）
- [x] 5.4 建立小說詳情頁 `/novels/{slug}`（封面、簡介、標籤、章節列表；localhost localStorage「繼續閱讀」按鈕）
- [x] 5.5 建立章節閱讀頁 `/novels/{slug}/{chapter-slug}`（純閱讀頁；瀏覽計數 increment；字體大小切換；深/淺色模式；閱讀進度條；底部固定導覽列；localStorage 進度記憶）
- [x] 5.6 建立搜尋頁 `/search`（標題關鍵字搜尋；高亮匹配結果）
- [x] 5.7 在 `routes/web.php` 註冊所有前台路由（named routes）
- [x] 5.8 草稿小說前台路由返回 404（Novel scope 過濾）

## 6. SEO 基礎設施

- [x] 6.1 建立 SEO meta Blade component（`<x-seo :title="$title" :description="$desc" :image="$image" />`）
- [x] 6.2 在各前台頁面套用 SEO component，小說/章節頁使用對應的 seo_title / seo_description
- [x] 6.3 加入 Open Graph 與 Twitter Card meta 標籤
- [x] 6.4 章節閱讀頁與小說詳情頁加入 Breadcrumb + Schema.org JSON-LD（Book / Article）
- [x] 6.5 建立 sitemap 生成指令（`spatie/laravel-sitemap`），包含已發佈小說與章節 URL
- [x] 6.6 在 `routes/console.php` 或 `app/Console/Kernel.php` 設定每日排程執行 sitemap 生成
- [x] 6.7 確認 `public/robots.txt` 正確引用 sitemap URL

## 7. 測試

- [x] 7.1 建立 `DatabaseSchemaTest`：驗證六張資料表結構正確建立
- [x] 7.2 建立 `NovelModelTest`：驗證 `published` scope、slug uniqueness、關聯
- [x] 7.3 建立 `ChapterObserverTest`：驗證 word_count 在 create/update 時自動計算
- [x] 7.4 建立 `SettingModelTest`：驗證 Cache::remember / Cache::forget 行為
- [x] 7.5 建立 `McpNovelToolsTest`：驗證 create_novel upsert 語意（重複 slug 返回現有資料）
- [x] 7.6 建立 `McpChapterToolsTest`：驗證字數驗證（低於下限 / 超過上限 / 合法範圍）
- [x] 7.7 建立 `McpAuthTest`：驗證無效 Bearer Token 返回 401
- [x] 7.8 建立 `FrontendRouteTest`：驗證草稿小說返回 404、已發佈小說返回 200
- [x] 7.9 建立 `ViewCountTest`：驗證章節頁瀏覽後 view_count 遞增
- [x] 7.10 執行全部測試：`php artisan test --compact`
