# Novel Reader — 個人小說平台企劃

> 個人小說發佈平台，透過 AI 大量生產並上架，以手機閱讀體驗為核心，整合 GA4 與自建統計，追蹤高流量小說以調整創作方向。

---

## 技術棧

| 層級 | 技術 | 版本 |
|------|------|------|
| 後端框架 | Laravel | 13（最新） |
| 後台管理 | Filament | v5（最新） |
| 前台模板 | Blade + Alpine.js | — |
| AI 自動上架 | Laravel MCP | v0.7.x（最新） |
| 資料庫 | MySQL | 8.x |
| 流量分析 | GA4 + 自建瀏覽計數 | — |
| PHP | PHP | 8.4+ |

---

## 資料庫設計

### 主要資料表

#### `novels`
| 欄位 | 型別 | 說明 |
|------|------|------|
| id | BIGINT UNSIGNED | PK |
| title | VARCHAR(255) | 小說標題 |
| slug | VARCHAR(255) UNIQUE | URL 識別碼 |
| description | TEXT | 簡介（~2 萬中文字上限） |
| cover_image | VARCHAR(500) | 封面圖路徑 |
| category_id | BIGINT UNSIGNED FK | 分類 |
| status | ENUM(draft, published, completed) | 狀態 |
| is_featured | BOOLEAN | 精選推薦 |
| seo_title | VARCHAR(60) | SEO 標題（Google 上限） |
| seo_description | VARCHAR(160) | SEO 描述（Google 上限） |
| seo_keywords | VARCHAR(255) | SEO 關鍵字 |
| view_count | BIGINT UNSIGNED | 累計瀏覽數 |
| timestamps | — | created_at / updated_at |

#### `chapters`
| 欄位 | 型別 | 說明 |
|------|------|------|
| id | BIGINT UNSIGNED | PK |
| novel_id | BIGINT UNSIGNED FK | 所屬小說 |
| chapter_number | INT UNSIGNED | 章節序號 |
| title | VARCHAR(255) | 章節標題 |
| slug | VARCHAR(255) | URL 識別碼 |
| content | MEDIUMTEXT | 正文（上限 ~16MB / 500 萬中文字） |
| word_count | INT UNSIGNED | 字數（自動計算） |
| view_count | BIGINT UNSIGNED | 章節瀏覽數 |
| published_at | TIMESTAMP NULL | 發佈時間 |
| timestamps | — | created_at / updated_at |

> **建議單章字數**：Application 層驗證 5,000 ~ 15,000 字，超過會影響手機閱讀體驗與跳出率。

#### `categories`
| 欄位 | 型別 |
|------|------|
| id | BIGINT UNSIGNED |
| name | VARCHAR(100) |
| slug | VARCHAR(100) UNIQUE |
| description | TEXT NULL |
| cover_image | VARCHAR(500) NULL |

#### `tags` + `novel_tag`（多對多）
```
tags: id, name, slug
novel_tag: novel_id, tag_id
```

#### `settings`（通用鍵值設定表）
```
key   VARCHAR(100) UNIQUE
value TEXT NULL
```
用於存放：GA4 Measurement ID、網站名稱、社群連結等。

---

## 後台（Filament v5）

### Resources
| Resource | 功能 |
|----------|------|
| `NovelResource` | 小說 CRUD、封面上傳（WebP 自動壓縮）、狀態切換、批次發佈/下架 |
| `ChapterResource` | 章節 CRUD（巢狀於小說下）、Rich Text 編輯器、字數自動計算 |
| `CategoryResource` | 分類管理（含封面圖）|
| `TagResource` | 標籤管理 |

### Pages
| Page | 功能 |
|------|------|
| `SettingsPage` | 系統設定：GA4 Measurement ID、網站名稱、社群連結 |
| `Dashboard` | 儀表板：總瀏覽量、Top 10 小說/章節排行、最新更新、分類統計圖表 |

---

## 前台（Blade + Alpine.js）

| 路由 | 頁面 | 說明 |
|------|------|------|
| `/` | 首頁 | 精選 Banner、最新更新章節、分類快速導覽 |
| `/novels` | 小說列表 | 分類/標籤篩選、排序（最新 / 最多閱讀） |
| `/novels/{slug}` | 小說詳情 | 封面、簡介、標籤、章節列表 |
| `/novels/{slug}/{chapter-slug}` | 章節閱讀 | 純閱讀頁，無多餘干擾 |
| `/search` | 搜尋 | 關鍵字搜小說標題與章節 |

---

## 手機閱讀體驗優化（Mobile-First）

- **字體大小調整**：Alpine.js + localStorage 記憶個人設定
- **深色 / 淺色模式**：localStorage 持久化
- **底部固定導覽列**：上一章 / 目錄 / 下一章
- **閱讀進度條**：頁面頂部細條，顯示目前閱讀進度
- **閱讀時間估算**：依字數換算顯示預估分鐘數
- **閱讀進度記憶**：localStorage 記錄每本讀到哪章，不需登入

---

## SEO 策略

- 每本小說、每個章節獨立 `slug` 與 canonical URL
- 動態 `<meta>` title / description / keywords（後台個別填寫，草稿自動加 `noindex`）
- Open Graph + Twitter Card（社群分享顯示封面與簡介）
- `sitemap.xml` 自動生成（`spatie/laravel-sitemap`，含所有已發佈小說與章節）
- `robots.txt` 管理（後台可設定，草稿/未發佈自動排除）
- Breadcrumb 導覽列 + Schema.org `BreadcrumbList` 結構化資料
- 小說詳情頁加入 `Book` Schema，章節頁加入 `Article` Schema
- Core Web Vitals 優化：封面圖上傳時自動轉為 WebP、lazy loading

---

## 統計整合

### 自建瀏覽計數
- 章節閱讀頁載入時，透過 Queue Job 非同步累加 `chapters.view_count`
- 同步累加對應小說的 `novels.view_count`
- 後台儀表板顯示 Top 10 排行，輔助 GA4 深度分析

### GA4
- `settings` 表存放 GA4 Measurement ID
- `layouts/app.blade.php` 動態注入 `gtag.js`
- 後台 SettingsPage 提供輸入框，隨時更換 ID

---

## MCP Server（AI 自動上架）

使用 `laravel/mcp` v0.7.x 建立 MCP HTTP Server，提供以下工具供 AI Agent 呼叫：

| 工具名稱 | 功能 |
|----------|------|
| `list_categories` | 取得所有分類（含 ID，供建立小說時選擇） |
| `list_tags` | 取得所有標籤 |
| `create_novel` | 建立新小說（title、slug、description、category_id、seo 欄位） |
| `create_chapter` | 新增章節到指定小說（支援批次上傳） |
| `publish_novel` | 將小說狀態改為 published |
| `get_novel_stats` | 取得小說/章節瀏覽數據，讓 AI 判斷接受度 |

---

## 額外功能規劃

| 功能 | 說明 |
|------|------|
| RSS Feed (`/feed.xml`) | 讓讀者訂閱更新，同時有助 Google 收錄新章節 |
| 社群分享按鈕 | LINE / Twitter-X / Facebook，放在章節結尾 |
| 相關小說推薦 | 同分類隨機 3~5 本，降低跳出率 |
| 字數統計顯示 | 小說總字數、章節字數，前台顯示給讀者參考 |
| 封面 WebP 壓縮 | 上傳時自動轉換，提升手機載入速度 |

---

## 驗收標準

1. Lighthouse 行動版：Performance ≥ 80、SEO = 100
2. GA4 Realtime 面板可看到章節瀏覽事件
3. MCP 工具呼叫後，後台可見新建小說/章節
4. `sitemap.xml` 包含所有已發佈小說與章節 URL
5. 閱讀頁字體大小切換後重整頁面，設定仍保留
6. 草稿小說在前台回傳 404，不被搜尋引擎收錄
