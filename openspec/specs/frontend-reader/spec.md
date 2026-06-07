# frontend-reader Specification

## Purpose
TBD - created by archiving change novel-reader-foundation. Update Purpose after archive.
## Requirements
### Requirement: 手機優先的章節閱讀頁
章節閱讀頁 SHALL 提供純閱讀體驗，包含：頂部閱讀進度條、底部固定導覽列（上一章/目錄/下一章）、字體大小調整（小/中/大，以 Alpine.js + localStorage 持久化）、深色/淺色模式切換（localStorage 持久化）、預估閱讀時間顯示（依字數 / 350 字每分鐘換算）。

#### Scenario: 字體大小設定在頁面重整後保留
- **WHEN** 使用者調整字體大小後重整頁面
- **THEN** 頁面載入時從 localStorage 讀取設定，字體大小保持上次設定值

#### Scenario: 底部導覽至下一章
- **WHEN** 使用者點擊底部「下一章」按鈕
- **THEN** 導覽至同小說的下一個 `chapter_number` 章節頁

#### Scenario: 最後一章隱藏「下一章」按鈕
- **WHEN** 使用者閱讀最後一章
- **THEN** 底部導覽列「下一章」按鈕不顯示或呈現禁用狀態

### Requirement: 閱讀進度記憶（localStorage）
系統 SHALL 在使用者進入章節頁時，以 `reading_progress_{novel_id}` 為 key 將當前 `chapter_id` 儲存至 localStorage。無需登入。

#### Scenario: 返回小說詳情頁顯示「繼續閱讀」按鈕
- **WHEN** 使用者之前已閱讀某章節，再次訪問該小說詳情頁
- **THEN** 頁面顯示「繼續閱讀第 N 章」按鈕，連結至上次閱讀章節

### Requirement: 小說列表頁篩選與排序
小說列表頁 `/novels` SHALL 支援：分類篩選、標籤篩選、排序（最新更新 / 最多閱讀），並以 URL query string 保存篩選狀態（可分享連結）。

#### Scenario: 分類篩選返回正確結果
- **WHEN** 使用者選擇「玄幻」分類
- **THEN** URL 更新為 `/novels?category=xuanhuan`，只顯示該分類的已發佈小說

### Requirement: 搜尋頁
`/search` SHALL 支援關鍵字搜尋小說標題與章節標題，顯示匹配結果（含小說封面縮圖與分類標籤），並高亮顯示匹配關鍵字。

#### Scenario: 搜尋返回相關小說
- **WHEN** 使用者輸入關鍵字並送出搜尋
- **THEN** 顯示 `title LIKE %keyword%` 的已發佈小說清單

### Requirement: 自建瀏覽計數
章節閱讀頁 Controller SHALL 在頁面載入時，使用 `increment()` 方法原子性地累加 `chapters.view_count` 與對應 `novels.view_count`。

#### Scenario: 章節頁載入後計數增加
- **WHEN** 使用者訪問章節閱讀頁
- **THEN** 對應章節的 `view_count` 增加 1，小說的 `view_count` 也增加 1

