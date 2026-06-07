# mcp-novel-tools Specification

## Purpose
TBD - created by archiving change novel-reader-foundation. Update Purpose after archive.
## Requirements
### Requirement: MCP HTTP Server 啟動
系統 SHALL 透過 `laravel/mcp` 提供 HTTP transport MCP Server，endpoint 為 `/mcp`，並以 Bearer Token 進行認證，Token 來自 `MCPAPP_SECRET` 環境變數。

#### Scenario: 有效 Bearer Token 可存取工具
- **WHEN** AI Agent 以正確 Bearer Token 發送 MCP 請求至 `/mcp`
- **THEN** 系統返回 200 並執行對應工具

#### Scenario: 無效 Token 被拒絕
- **WHEN** 請求不含 Authorization header 或 Token 錯誤
- **THEN** 系統返回 401 Unauthorized

### Requirement: create_novel 工具的 slug upsert 語意
`create_novel` MCP Tool SHALL 在接收到的 `slug` 已存在時，返回現有小說資料而非拋出錯誤，並在 response 中包含 `"created": false` 欄位告知 AI。

#### Scenario: slug 不存在時新建小說
- **WHEN** AI 呼叫 `create_novel` 且 slug 不存在
- **THEN** 系統新建小說並返回 `{"novel_id": N, "slug": "...", "created": true}`

#### Scenario: slug 已存在時返回現有資料
- **WHEN** AI 呼叫 `create_novel` 且 slug 已存在
- **THEN** 系統返回現有小說 `{"novel_id": N, "slug": "...", "created": false, "message": "Novel with this slug already exists. Use novel_id to add chapters."}`，不修改任何資料

### Requirement: create_chapter 工具的字數驗證
`create_chapter` MCP Tool SHALL 驗證 `content` 的字數（`mb_strlen(strip_tags($content))`）在 5,000 至 15,000 字之間，超出範圍時返回清晰錯誤訊息並拒絕寫入。

#### Scenario: 字數在合法範圍內成功建立
- **WHEN** AI 呼叫 `create_chapter` 且 content 字數在 5,000~15,000 之間
- **THEN** 章節成功建立，`word_count` 自動填入計算值

#### Scenario: 字數超過上限被拒絕
- **WHEN** AI 呼叫 `create_chapter` 且 content 字數超過 15,000 字
- **THEN** 返回錯誤：`{"error": "Content exceeds 15,000 characters (current: N). Please split into multiple chapters."}`

#### Scenario: 字數低於下限被拒絕
- **WHEN** AI 呼叫 `create_chapter` 且 content 字數少於 5,000 字
- **THEN** 返回錯誤：`{"error": "Content is too short (current: N, minimum: 5,000). Please combine with adjacent content."}`

### Requirement: publish_novel 工具
`publish_novel` MCP Tool SHALL 將指定 `novel_id` 的小說狀態更新為 `published`，並在 Tool description 說明此操作不可逆（需手動改回草稿）。

#### Scenario: 成功發佈小說
- **WHEN** AI 呼叫 `publish_novel(novel_id: N)`
- **THEN** 系統將 `novels.status` 更新為 `published` 並返回確認訊息

#### Scenario: novel_id 不存在時返回錯誤
- **WHEN** AI 呼叫 `publish_novel` 且指定的 novel_id 不存在
- **THEN** 返回 `{"error": "Novel not found."}`

### Requirement: get_novel_stats 工具
`get_novel_stats` MCP Tool SHALL 返回指定小說的瀏覽統計，包含總瀏覽量及各章節瀏覽量排行（前 10），供 AI 判斷接受度。

#### Scenario: 取得小說統計資料
- **WHEN** AI 呼叫 `get_novel_stats(novel_id: N)`
- **THEN** 返回 `{total_view_count, chapters: [{chapter_number, title, view_count}]}`（依 view_count 降序，最多 10 筆）

