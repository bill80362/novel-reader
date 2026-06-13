<?php

namespace App\Mcp\Tools;

use App\Models\Novel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Delete a novel and all its chapters. Use novel_id or slug to identify the novel. This action is irreversible.')]
class DeleteNovelTool extends Tool
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

        $novelId = $novel->id;
        $title = $novel->title;
        $chaptersCount = $novel->chapters()->count();

        // Delete chapters first (or let cascade handle it)
        $novel->chapters()->delete();
        $novel->tags()->detach();
        $novel->delete();

        return Response::text(json_encode([
            'novel_id' => $novelId,
            'title' => $title,
            'chapters_deleted' => $chaptersCount,
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
