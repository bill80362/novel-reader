<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapterObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_word_count_is_calculated_on_create(): void
    {
        $novel = Novel::factory()->create();
        $content = '<p>This is a test content with some words in it.</p>';

        $chapter = Chapter::create([
            'novel_id' => $novel->id,
            'chapter_number' => 1,
            'title' => 'Test Chapter',
            'slug' => 'test-chapter',
            'content' => $content,
        ]);

        // Word count should be the stripped text length
        $this->assertGreaterThan(0, $chapter->word_count);
    }

    public function test_word_count_is_recalculated_on_update(): void
    {
        $novel = Novel::factory()->create();
        $chapter = Chapter::factory()->create([
            'novel_id' => $novel->id,
            'content' => '<p>Short</p>',
        ]);

        $oldWordCount = $chapter->word_count;

        // Update with longer content
        $chapter->update(['content' => '<p>This is much longer content with more words added</p>']);

        $this->assertNotEquals($oldWordCount, $chapter->word_count);
    }
}
