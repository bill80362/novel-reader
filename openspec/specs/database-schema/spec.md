# database-schema Specification

## Purpose
TBD - created by archiving change novel-reader-foundation. Update Purpose after archive.
## Requirements
### Requirement: 資料表結構完整建立
系統 SHALL 包含 `novels`、`chapters`、`categories`、`tags`、`novel_tag`、`settings` 六張資料表，並建立對應的 Eloquent Model 及關聯方法。

#### Scenario: 建立 Novel Model 關聯
- **WHEN** 開發者呼叫 `$novel->chapters`
- **THEN** 返回該小說所有章節的集合（HasMany）

#### Scenario: 建立 Novel 與 Category 關聯
- **WHEN** 開發者呼叫 `$novel->category`
- **THEN** 返回該小說的分類（BelongsTo）

#### Scenario: 建立 Novel 與 Tag 多對多關聯
- **WHEN** 開發者呼叫 `$novel->tags`
- **THEN** 透過 `novel_tag` pivot 表返回標籤集合（BelongsToMany）

### Requirement: 章節 slug 在同一小說內唯一
`chapters` 表 SHALL 建立 `UNIQUE(novel_id, slug)` 複合唯一索引，確保同一小說內章節 slug 不重複，但不同小說可有相同 slug。

#### Scenario: 同小說重複 slug 被拒絕
- **WHEN** 插入 `chapter_slug="chapter-1"` 且該小說已有相同 slug 的章節
- **THEN** 資料庫拋出唯一約束違反錯誤

#### Scenario: 不同小說允許相同 slug
- **WHEN** 兩本不同小說各自插入 `chapter_slug="chapter-1"`
- **THEN** 兩筆資料均成功儲存

### Requirement: Novel 狀態機
`novels.status` SHALL 只允許 `draft`、`published`、`completed` 三個值，且 `draft` 狀態的小說不應在前台顯示。

#### Scenario: 草稿小說前台不可見
- **WHEN** 使用者訪問 `status=draft` 小說的 slug URL
- **THEN** 系統返回 404

#### Scenario: Novel scope 自動過濾草稿
- **WHEN** 前台查詢呼叫 `Novel::published()->get()`
- **THEN** 只返回 `status IN (published, completed)` 的小說

### Requirement: settings 表鍵值儲存
`settings` 表 SHALL 提供 `Setting::get($key, $default)` 與 `Setting::set($key, $value)` 靜態方法，並自動快取查詢結果 1 小時。

#### Scenario: 讀取設定值命中快取
- **WHEN** 第一次呼叫 `Setting::get('ga4_id')` 後再次呼叫
- **THEN** 第二次呼叫不發出 SQL 查詢，從 Cache 返回

#### Scenario: 寫入設定值清除快取
- **WHEN** 呼叫 `Setting::set('ga4_id', 'G-XXXXXXXX')`
- **THEN** 對應的 Cache key 被清除，下次讀取重新查詢 DB

