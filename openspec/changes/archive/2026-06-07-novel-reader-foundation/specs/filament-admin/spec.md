## ADDED Requirements

### Requirement: Filament Admin Panel 設定
系統 SHALL 透過 Filament Panel Provider 建立後台管理面板，路徑為 `/admin`，僅允許認證用戶存取。

#### Scenario: 未登入用戶被重導至登入頁
- **WHEN** 未認證用戶訪問 `/admin`
- **THEN** 系統重導至 `/admin/login`

### Requirement: NovelResource CRUD
`NovelResource` SHALL 提供小說的完整 CRUD，包含：標題、slug（自動從標題生成可手動覆寫）、簡介、封面圖上傳（自動轉 WebP）、分類選擇、標籤多選、狀態切換、SEO 欄位、精選開關、批次發佈/下架。

#### Scenario: 建立新小說時 slug 自動生成
- **WHEN** 管理員輸入標題後游標離開（onBlur）
- **THEN** slug 欄位自動填入 `Str::slug($title)` 的值，可手動修改

#### Scenario: 批次發佈選取小說
- **WHEN** 管理員在列表頁選取多筆小說並執行「批次發佈」
- **THEN** 所有選取小說的 status 更新為 `published`

### Requirement: ChapterResource CRUD
`ChapterResource` SHALL 提供章節的完整 CRUD，包含：所屬小說選擇、章節序號、標題、slug、Rich Text 編輯器內容、字數自動顯示（唯讀）、發佈時間設定。

#### Scenario: 章節儲存後 word_count 自動更新
- **WHEN** 管理員儲存章節
- **THEN** `word_count` 欄位顯示 `mb_strlen(strip_tags($content))` 的計算值

### Requirement: Dashboard 統計頁
Filament Dashboard SHALL 顯示：網站總瀏覽量、Top 10 小說排行（依 view_count）、Top 10 章節排行、最新更新章節列表、各分類小說數量統計圖表。

#### Scenario: Dashboard 顯示總瀏覽量
- **WHEN** 管理員訪問 `/admin`
- **THEN** 首頁卡片顯示 `novels.view_count` 總和

### Requirement: SettingsPage 系統設定
`SettingsPage` SHALL 提供表單介面設定：GA4 Measurement ID、網站名稱、社群連結（LINE/Twitter-X/Facebook）。儲存後自動清除相關 Cache。

#### Scenario: 儲存 GA4 ID 後前台立即生效
- **WHEN** 管理員儲存新的 GA4 Measurement ID
- **THEN** `setting:ga4_id` Cache 被清除，下一次頁面渲染使用新 ID
