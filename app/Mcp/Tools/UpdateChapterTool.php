<?php

namespace App\Mcp\Tools;

use App\Models\Chapter;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Update an existing chapter. Only provided fields will be updated. Use chapter_id to identify the chapter. Automatically recalculates word_count if content is updated.')]
class UpdateChapterTool extends Tool
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

        $fillable = [
            'chapter_number',
            'title',
            'slug',
            'content',
            'published_at',
        ];

        $updates = [];
        foreach ($fillable as $field) {
            if (array_key_exists($field, $data)) {
                $updates[$field] = $data[$field];
            }
        }

        if (empty($updates)) {
            return Response::error('No valid fields provided for update.');
        }

        // Handle content update with content_path
        if (empty($updates['content']) && ! empty($data['content_path'])) {
            $contentPath = $data['content_path'];
            if (! str_starts_with($contentPath, DIRECTORY_SEPARATOR) && ! preg_match('/^[A-Za-z]:[\\\\\/]/', $contentPath)) {
                $contentPath = base_path($contentPath);
            }

            if (! is_file($contentPath) || ! is_readable($contentPath)) {
                return Response::error("Content file not found or not readable ({$contentPath}).");
            }

            $content = file_get_contents($contentPath);
            if ($content === false) {
                return Response::error("Unable to read content file ({$contentPath}).");
            }

            $updates['content'] = $content;
        }

        $chapter->update($updates);

        return Response::text(json_encode([
            'chapter_id' => $chapter->id,
            'novel_id' => $chapter->novel_id,
            'chapter_number' => $chapter->chapter_number,
            'word_count' => $chapter->word_count,
            'updated_fields' => array_keys($updates),
            'updated' => true,
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
