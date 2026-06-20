<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Artisan;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Regenerate the XML sitemap (sitemap.xml) for SEO. Call after publishing or updating novels/chapters to ensure search engines see the latest URLs.')]
class GenerateSitemapTool extends Tool
{
    public function handle(Request $request): Response
    {
        try {
            Artisan::call('app:generate-sitemap');
            $output = Artisan::output();
            $path = public_path('sitemap.xml');

            return Response::text(json_encode([
                'status' => 'success',
                'message' => 'Sitemap regenerated successfully.',
                'path' => $path,
                'output' => trim($output),
            ], JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            return Response::error('Failed to generate sitemap: '.$e->getMessage());
        }
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
