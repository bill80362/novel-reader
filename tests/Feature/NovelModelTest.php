<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Novel;
use App\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NovelModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_scope_filters_correctly(): void
    {
        $published = Novel::factory()->create(['status' => 'published']);
        $completed = Novel::factory()->create(['status' => 'completed']);
        $draft = Novel::factory()->create(['status' => 'draft']);

        $results = Novel::published()->get();

        $this->assertTrue($results->contains($published));
        $this->assertTrue($results->contains($completed));
        $this->assertFalse($results->contains($draft));
    }

    public function test_novel_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $novel = Novel::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $novel->category);
        $this->assertEquals($category->id, $novel->category->id);
    }

    public function test_novel_has_many_chapters(): void
    {
        $novel = Novel::factory()->create();
        $novel->chapters()->createMany([
            ['chapter_number' => 1, 'title' => 'Ch 1', 'slug' => 'ch-1', 'content' => 'Content 1', 'word_count' => 100],
            ['chapter_number' => 2, 'title' => 'Ch 2', 'slug' => 'ch-2', 'content' => 'Content 2', 'word_count' => 200],
        ]);

        $this->assertEquals(2, $novel->chapters()->count());
    }

    public function test_novel_has_many_tags(): void
    {
        $novel = Novel::factory()->create();
        $tags = Tag::factory(3)->create();
        $novel->tags()->attach($tags);

        $this->assertEquals(3, $novel->tags()->count());
    }

    public function test_slug_is_unique(): void
    {
        Novel::factory()->create(['slug' => 'test-slug']);

        $this->expectException(QueryException::class);
        Novel::factory()->create(['slug' => 'test-slug']);
    }
}
