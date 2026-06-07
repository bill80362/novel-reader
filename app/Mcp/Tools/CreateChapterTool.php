<?php

namespace App\Mcp\Tools;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a chapter for a novel. IMPORTANT: content must be between 5,000 and 15,000 characters (Chinese characters). word_count is automatically calculated.')]
class CreateChapterTool extends Tool
{
    public function handle(Request $request): Response
    {
        $data = $request->input();
        $required = ['novel_id', 'chapter_number', 'title', 'slug', 'content'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return Response::error("Missing required field: {$field}");
            }
        }

        // Check novel exists
        $novel = Novel::find($data['novel_id']);
        if (! $novel) {
            return Response::error("Novel not found (novel_id: {$data['novel_id']}).");
        }

        // Validate content length
        $contentLength = mb_strlen(strip_tags((string) $data['content']));
        if ($contentLength < 5000) {
            return Response::error(
                "Content too short (current: {$contentLength} characters, minimum: 5,000). "
                .'Please combine with adjacent content or expand this chapter.'
            );
        }
        if ($contentLength > 15000) {
            return Response::error(
                "Content too long (current: {$contentLength} characters, maximum: 15,000). "
                .'Please split into multiple chapters.'
            );
        }

        $chapter = Chapter::create([
            'novel_id' => $data['novel_id'],
            'chapter_number' => (int) $data['chapter_number'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'published_at' => $data['published_at'] ?? null,
        ]);

        return Response::text(json_encode([
            'chapter_id' => $chapter->id,
            'novel_id' => $chapter->novel_id,
            'chapter_number' => $chapter->chapter_number,
            'word_count' => $chapter->word_count,
            'created' => true,
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
