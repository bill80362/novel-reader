<?php

namespace App\Mcp\Tools;

use App\Models\Novel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Publish a novel (set status to published). This action is not reversible; you must manually set status back to draft if needed.')]
class PublishNovelTool extends Tool
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

        $novel->update(['status' => 'published']);

        return Response::text(json_encode([
            'novel_id' => $novel->id,
            'slug' => $novel->slug,
            'status' => $novel->status,
            'message' => 'Novel published successfully.',
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
