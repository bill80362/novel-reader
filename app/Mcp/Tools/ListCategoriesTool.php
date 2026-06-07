<?php

namespace App\Mcp\Tools;

use App\Models\Category;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List all novel categories. Returns id and name for each category. Use the id when calling create_novel.')]
class ListCategoriesTool extends Tool
{
    public function handle(Request $request): Response
    {
        $categories = Category::select('id', 'name', 'slug')->get();

        return Response::text(json_encode($categories->toArray(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
