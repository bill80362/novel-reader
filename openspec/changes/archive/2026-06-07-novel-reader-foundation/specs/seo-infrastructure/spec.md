## ADDED Requirements

### Requirement: 動態 SEO Meta 標籤
每個前台頁面 SHALL 在 `<head>` 中動態注入對應的 `<title>`、`<meta name="description">`、`<meta name="keywords">`、Open Graph（og:title / og:description / og:image）、Twitter Card 標籤。草稿小說頁面 SHALL 加入 `<meta name="robots" content="noindex">`。

#### Scenario: 已發佈小說詳情頁具有正確 meta
- **WHEN** 爬蟲訪問已發佈小說的詳情頁
- **THEN** `<title>` 為 `novels.seo_title`（或 fallback `novels.title`），`description` 為 `novels.seo_description`

#### Scenario: 草稿小說加入 noindex
- **WHEN** 爬蟲（或直接訪問）到達 `status=draft` 小說的 canonical URL
- **THEN** 系統返回 404（前台不渲染草稿）

### Requirement: Sitemap XML 自動生成
系統 SHALL 透過 `spatie/laravel-sitemap` 生成 `/sitemap.xml`，包含所有已發佈小說與章節的 URL，並透過排程每日自動更新。

#### Scenario: sitemap 包含已發佈小說 URL
- **WHEN** 執行 `php artisan sitemap:generate`
- **THEN** `public/sitemap.xml` 包含所有 `status IN (published, completed)` 小說的 canonical URL

#### Scenario: sitemap 不包含草稿
- **WHEN** 執行 sitemap 生成
- **THEN** `status=draft` 的小說 URL 不出現在 sitemap.xml 中

### Requirement: Schema.org 結構化資料
小說詳情頁 SHALL 注入 `Book` Schema，章節閱讀頁 SHALL 注入 `Article` Schema，以 JSON-LD 格式嵌入。兩個頁面 SHALL 包含 `BreadcrumbList` Schema。

#### Scenario: 小說詳情頁含 Book Schema
- **WHEN** 使用者（或爬蟲）訪問小說詳情頁
- **THEN** HTML 包含 `<script type="application/ld+json">` 且內容為合法的 `Book` Schema

### Requirement: GA4 動態注入
前台 layout SHALL 從 `Setting::get('ga4_id')` 讀取 GA4 Measurement ID，若不為空則動態注入 `gtag.js` 追蹤碼。

#### Scenario: 設定 GA4 ID 後前台頁面載入追蹤碼
- **WHEN** `settings` 表中 `ga4_id` 有值，使用者訪問任意前台頁面
- **THEN** HTML 的 `<head>` 包含 `gtag('config', 'G-XXXXXXXX')` 腳本

#### Scenario: 未設定 GA4 ID 時不注入追蹤碼
- **WHEN** `settings` 表中 `ga4_id` 為空
- **THEN** HTML 中不出現 gtag 相關腳本
