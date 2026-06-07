<?php

namespace App\Mcp\Tools;

use App\Models\Novel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new novel or return existing if slug already exists. IMPORTANT: slug is the unique identifier for a novel. If slug exists, returns created: false with the existing novel_id.')]
class CreateNovelTool extends Tool
{
    public function handle(Request $request): Response
    {
        $data = $request->all();
        $required = ['title', 'slug', 'category_id'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return Response::error("Missing required field: {$field}");
            }
        }

        // Check if slug exists
        $existing = Novel::where('slug', $data['slug'])->first();
        if ($existing) {
            return Response::text(json_encode([
                'novel_id' => $existing->id,
                'slug' => $existing->slug,
                'title' => $existing->title,
                'created' => false,
                'message' => 'Novel with this slug already exists. Use novel_id to add chapters.',
            ], JSON_UNESCAPED_UNICODE));
        }

        $novel = Novel::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'],
            'status' => 'draft',
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
            'seo_keywords' => $data['seo_keywords'] ?? null,
        ]);

        if (! empty($data['tag_ids'])) {
            $novel->tags()->sync($data['tag_ids']);
        }

        return Response::text(json_encode([
            'novel_id' => $novel->id,
            'slug' => $novel->slug,
            'title' => $novel->title,
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
