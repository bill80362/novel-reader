<?php

namespace App\Mcp\Tools;

use App\Models\Chapter;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Publish a draft chapter by setting published_at to now. This does not change the chapter content.')]
class PublishChapterTool extends Tool
{
    public function handle(Request $request): Response
    {
        $chapterId = $request->get('chapter_id');

        if (empty($chapterId)) {
            return Response::error('Missing required field: chapter_id');
        }

        $chapter = Chapter::find($chapterId);

        if (! $chapter) {
            return Response::error("Chapter not found (chapter_id: {$chapterId}).");
        }

        $chapter->update([
            'published_at' => now(),
        ]);

        return Response::text(json_encode([
            'chapter_id' => $chapter->id,
            'novel_id' => $chapter->novel_id,
            'chapter_number' => $chapter->chapter_number,
            'published_at' => $chapter->published_at?->toIso8601String(),
            'created' => false,
            'published' => true,
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
