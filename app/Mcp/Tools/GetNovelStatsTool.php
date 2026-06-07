<?php

namespace App\Mcp\Tools;

use App\Models\Novel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get statistics for a novel including total view count and top 10 chapters by views. Use to assess reception and decide content direction.')]
class GetNovelStatsTool extends Tool
{
    public function handle(Request $request): Response
    {
        $novelId = $request->input('novel_id');
        if (empty($novelId)) {
            return Response::error('Missing required field: novel_id');
        }

        $novel = Novel::find($novelId);
        if (! $novel) {
            return Response::error("Novel not found (novel_id: {$novelId}).");
        }

        $chapters = $novel->chapters()
            ->orderByDesc('view_count')
            ->limit(10)
            ->select('chapter_number', 'title', 'view_count')
            ->get();

        return Response::text(json_encode([
            'novel_id' => $novel->id,
            'title' => $novel->title,
            'total_view_count' => $novel->view_count,
            'chapters_count' => $novel->chapters()->count(),
            'top_chapters' => $chapters->toArray(),
        ], JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
