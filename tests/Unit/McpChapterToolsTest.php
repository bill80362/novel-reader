<?php

namespace Tests\Unit;

use App\Mcp\Servers\NovelServer;
use App\Mcp\Tools\CreateChapterTool;
use App\Mcp\Tools\PublishChapterTool;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class McpChapterToolsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_chapter_allows_long_content_as_a_draft(): void
    {
        $user = User::factory()->create();
        $novel = Novel::factory()->create();
        $content = str_repeat('word ', 4000);

        $response = NovelServer::actingAs($user)->tool(CreateChapterTool::class, [
            'novel_id' => $novel->id,
            'chapter_number' => 1,
            'title' => 'Test Chapter',
            'slug' => 'test-chapter',
            'content' => $content,
        ]);

        $response->assertHasNoErrors()
            ->assertSee('"created":true');

        $this->assertDatabaseHas(Chapter::class, [
            'novel_id' => $novel->id,
            'chapter_number' => 1,
            'slug' => 'test-chapter',
        ]);

        $chapter = Chapter::where('novel_id', $novel->id)->where('slug', 'test-chapter')->firstOrFail();

        $this->assertNull($chapter->published_at);
        $this->assertSame(mb_strlen($content), $chapter->word_count);
    }

    public function test_publish_chapter_marks_a_long_draft_as_published(): void
    {
        $user = User::factory()->create();
        $novel = Novel::factory()->create();
        $content = str_repeat('word ', 20000);

        $created = NovelServer::actingAs($user)->tool(CreateChapterTool::class, [
            'novel_id' => $novel->id,
            'chapter_number' => 2,
            'title' => 'Long Draft Chapter',
            'slug' => 'long-draft-chapter',
            'content' => $content,
        ]);

        $created->assertHasNoErrors();

        $chapter = Chapter::where('novel_id', $novel->id)->where('slug', 'long-draft-chapter')->firstOrFail();

        $published = NovelServer::actingAs($user)->tool(PublishChapterTool::class, [
            'chapter_id' => $chapter->id,
        ]);

        $published->assertHasNoErrors()
            ->assertSee('"published":true');

        $chapter->refresh();

        $this->assertNotNull($chapter->published_at);

        $this->assertDatabaseMissing(Chapter::class, [
            'novel_id' => $novel->id,
            'slug' => 'missing-chapter',
        ]);
    }

    public function test_create_chapter_can_publish_long_content_from_a_file_path(): void
    {
        $user = User::factory()->create();
        $novel = Novel::factory()->create();
        $content = str_repeat('word ', 20000);
        $contentPath = storage_path('app/mcp-test-long-chapter.txt');

        file_put_contents($contentPath, $content);

        try {
            $response = NovelServer::actingAs($user)->tool(CreateChapterTool::class, [
                'novel_id' => $novel->id,
                'chapter_number' => 3,
                'title' => 'File Based Long Chapter',
                'slug' => 'file-based-long-chapter',
                'content_path' => $contentPath,
                'published_at' => now()->toIso8601String(),
            ]);

            $response->assertHasNoErrors()
                ->assertSee('"created":true');

            $chapter = Chapter::where('novel_id', $novel->id)->where('slug', 'file-based-long-chapter')->firstOrFail();

            $this->assertNotNull($chapter->published_at);
            $this->assertSame(mb_strlen($content), $chapter->word_count);
        } finally {
            if (file_exists($contentPath)) {
                unlink($contentPath);
            }
        }
    }
}
