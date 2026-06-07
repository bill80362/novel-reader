<?php

namespace Tests\Unit;

use App\Mcp\Servers\NovelServer;
use App\Mcp\Tools\CreateChapterTool;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class McpChapterToolsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_chapter_allows_content_shorter_than_the_previous_minimum(): void
    {
        $user = User::factory()->create();
        $novel = Novel::factory()->create();

        $response = NovelServer::actingAs($user)->tool(CreateChapterTool::class, [
            'novel_id' => $novel->id,
            'chapter_number' => 1,
            'title' => 'Test Chapter',
            'slug' => 'test-chapter',
            'content' => '<p>Short content</p>',
            'published_at' => now()->toIso8601String(),
        ]);

        $response->assertHasNoErrors()
            ->assertSee('"created":true');

        $this->assertDatabaseHas(Chapter::class, [
            'novel_id' => $novel->id,
            'chapter_number' => 1,
            'slug' => 'test-chapter',
        ]);

        $chapter = Chapter::where('novel_id', $novel->id)->where('slug', 'test-chapter')->firstOrFail();

        $this->assertSame(mb_strlen('Short content'), $chapter->word_count);
    }

    public function test_create_chapter_still_rejects_content_over_the_maximum_length(): void
    {
        $user = User::factory()->create();
        $novel = Novel::factory()->create();

        $content = str_repeat('word ', 20000);

        $response = NovelServer::actingAs($user)->tool(CreateChapterTool::class, [
            'novel_id' => $novel->id,
            'chapter_number' => 2,
            'title' => 'Too Long Chapter',
            'slug' => 'too-long-chapter',
            'content' => $content,
        ]);

        $response->assertHasErrors(['Content too long']);

        $this->assertDatabaseMissing(Chapter::class, [
            'novel_id' => $novel->id,
            'slug' => 'too-long-chapter',
        ]);
    }
}
