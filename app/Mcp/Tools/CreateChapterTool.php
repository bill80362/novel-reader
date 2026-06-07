<?php

namespace App\Mcp\Tools;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a chapter for a novel. Use content_path for long body text to avoid large tool arguments. word_count is automatically calculated.')]
class CreateChapterTool extends Tool
{
    public function handle(Request $request): Response
    {
        $data = $request->all();
        $required = ['novel_id', 'chapter_number', 'title', 'slug'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return Response::error("Missing required field: {$field}");
            }
        }

        $content = $data['content'] ?? null;
        $contentPath = $data['content_path'] ?? null;

        if (empty($content) && empty($contentPath)) {
            return Response::error('Missing required field: content or content_path');
        }

        // Check novel exists
        $novel = Novel::find($data['novel_id']);
        if (! $novel) {
            return Response::error("Novel not found (novel_id: {$data['novel_id']}).");
        }

        $publishedAt = $data['published_at'] ?? null;

        if (! empty($contentPath)) {
            if (! str_starts_with($contentPath, DIRECTORY_SEPARATOR) && ! preg_match('/^[A-Za-z]:[\\\/]/', $contentPath)) {
                $contentPath = base_path($contentPath);
            }

            if (! is_file($contentPath) || ! is_readable($contentPath)) {
                return Response::error("Content file not found or not readable ({$contentPath}).");
            }

            $content = file_get_contents($contentPath);

            if ($content === false) {
                return Response::error("Unable to read content file ({$contentPath}).");
            }
        }

        $content = (string) $content;

        // Validate direct tool arguments only when content is passed inline.
        $contentLength = mb_strlen(strip_tags($content));
        if (empty($contentPath) && ! empty($publishedAt) && $contentLength > 15000) {
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
            'content' => $content,
            'published_at' => $publishedAt,
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
