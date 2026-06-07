<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewCountTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_count_increments_on_chapter_read(): void
    {
        $novel = Novel::factory()->create(['status' => 'published']);
        $chapter = Chapter::factory()->create([
            'novel_id' => $novel->id,
            'view_count' => 0,
        ]);

        // Simulate reading the chapter
        $this->get(route('novels.read', [$novel->slug, $chapter->slug]));

        $chapter->refresh();
        $this->assertEquals(1, $chapter->view_count);

        // Read again
        $this->get(route('novels.read', [$novel->slug, $chapter->slug]));
        $chapter->refresh();
        $this->assertEquals(2, $chapter->view_count);
    }

    public function test_novel_view_count_increments_on_chapter_read(): void
    {
        $novel = Novel::factory()->create(['status' => 'published', 'view_count' => 0]);
        $chapter = Chapter::factory()->create([
            'novel_id' => $novel->id,
            'view_count' => 0,
        ]);

        $this->get(route('novels.read', [$novel->slug, $chapter->slug]));

        $novel->refresh();
        $this->assertEquals(1, $novel->view_count);
    }
}
