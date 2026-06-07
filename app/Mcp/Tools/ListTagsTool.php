<?php

namespace App\Mcp\Tools;

use App\Models\Tag;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List all available tags. Returns id and name for each tag. Use these ids when creating novels.')]
class ListTagsTool extends Tool
{
    public function handle(Request $request): Response
    {
        $tags = Tag::select('id', 'name', 'slug')->get();

        return Response::text(json_encode($tags->toArray(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
