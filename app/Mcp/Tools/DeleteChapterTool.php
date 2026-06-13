<?php

namespace App\Mcp\Tools;

use App\Models\Chapter;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Delete a chapter. Use chapter_id to identify the chapter. This action is irreversible.')]
class DeleteChapterTool extends Tool
{
    public function handle(Request $request): Response
    {
        $data = $request->all();

        if (empty($data['chapter_id'])) {
            return Response::error('Missing required field: chapter_id');
        }

        $chapter = Chapter::find($data['chapter_id']);

        if (! $chapter) {
            return Response::error("Chapter not found (chapter_id: {$data['chapter_id']}).");
        }

        $chapterId = $chapter->id;
        $novelId = $chapter->novel_id;
        $title = $chapter->title;

        $chapter->delete();

        return Response::text(json_encode([
            'chapter_id' => $chapterId,
            'novel_id' => $novelId,
            'title' => $title,
            'deleted' => true,
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
