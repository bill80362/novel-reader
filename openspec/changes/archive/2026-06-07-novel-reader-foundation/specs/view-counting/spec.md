## ADDED Requirements

### Requirement: 章節 word_count 自動計算
`Chapter` Model SHALL 透過 Eloquent Observer 在 `creating` 和 `updating` 事件中，自動計算並填入 `word_count = mb_strlen(strip_tags($content))`，不需手動填寫。

#### Scenario: 建立章節時 word_count 自動填入
- **WHEN** 任何途徑（後台 / MCP / 直接 Model 建立）新增章節
- **THEN** `chapters.word_count` 自動填入正確字數，無需呼叫端提供此值

#### Scenario: 更新章節內容後 word_count 自動更新
- **WHEN** 章節內容被修改
- **THEN** `chapters.word_count` 自動重新計算

### Requirement: 瀏覽計數使用原子 increment
章節 Controller 的瀏覽計數 SHALL 使用 `Model::where('id', $id)->increment('view_count')` 而非讀取後寫入，確保高並發安全。

#### Scenario: 並發請求不造成計數丟失
- **WHEN** 多個並發請求同時訪問同一章節頁
- **THEN** 每個請求都正確累加一次，不因競態條件丟失計數
