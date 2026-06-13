<?php

namespace App\Mcp\Tools;

use App\Models\Novel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Update an existing novel. Only provided fields will be updated. Use novel_id or slug to identify the novel.')]
class UpdateNovelTool extends Tool
{
    public function handle(Request $request): Response
    {
        $data = $request->all();

        $novel = null;

        if (! empty($data['novel_id'])) {
            $novel = Novel::find($data['novel_id']);
        } elseif (! empty($data['slug'])) {
            $novel = Novel::where('slug', $data['slug'])->first();
        }

        if (! $novel) {
            return Response::error('Novel not found. Provide novel_id or slug.');
        }

        $fillable = [
            'title',
            'slug',
            'description',
            'cover_image',
            'category_id',
            'status',
            'is_featured',
            'seo_title',
            'seo_description',
            'seo_keywords',
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

        // Check slug uniqueness if changing slug
        if (isset($updates['slug']) && $updates['slug'] !== $novel->slug) {
            $existing = Novel::where('slug', $updates['slug'])->where('id', '!=', $novel->id)->first();
            if ($existing) {
                return Response::error("Slug '{$updates['slug']}' is already taken by another novel.");
            }
        }

        $novel->update($updates);

        // Sync tags if provided
        if (! empty($data['tag_ids'])) {
            $novel->tags()->sync($data['tag_ids']);
        }

        return Response::text(json_encode([
            'novel_id' => $novel->id,
            'slug' => $novel->slug,
            'title' => $novel->title,
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
