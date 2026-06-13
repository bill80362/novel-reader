<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\CreateChapterTool;
use App\Mcp\Tools\CreateNovelTool;
use App\Mcp\Tools\DeleteChapterTool;
use App\Mcp\Tools\DeleteNovelTool;
use App\Mcp\Tools\GetNovelStatsTool;
use App\Mcp\Tools\ListCategoriesTool;
use App\Mcp\Tools\ListTagsTool;
use App\Mcp\Tools\PublishChapterTool;
use App\Mcp\Tools\PublishNovelTool;
use App\Mcp\Tools\UpdateChapterTool;
use App\Mcp\Tools\UpdateNovelTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Novel Server')]
#[Version('1.0.0')]
#[Instructions('Novel publishing MCP Server. Tools for AI agents to create and publish novels with chapters. All tools require Bearer token authentication.')]
class NovelServer extends Server
{
    protected array $tools = [
        ListCategoriesTool::class,
        ListTagsTool::class,
        CreateNovelTool::class,
        CreateChapterTool::class,
        UpdateNovelTool::class,
        UpdateChapterTool::class,
        DeleteNovelTool::class,
        DeleteChapterTool::class,
        PublishChapterTool::class,
        PublishNovelTool::class,
        GetNovelStatsTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
