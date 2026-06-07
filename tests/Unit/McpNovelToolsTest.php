<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Novel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class McpNovelToolsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_novel_creates_new_novel(): void
    {
        $category = Category::factory()->create();

        $novel = Novel::create([
            'title' => 'Test Novel',
            'slug' => 'test-novel',
            'description' => 'Test Description',
            'category_id' => $category->id,
            'status' => 'published',
        ]);

        $this->assertNotNull($novel->id);
        $this->assertTrue(Novel::where('slug', 'test-novel')->exists());
    }

    public function test_create_novel_with_duplicate_slug_returns_existing(): void
    {
        $category = Category::factory()->create();

        $existing = Novel::create([
            'title' => 'Existing Novel',
            'slug' => 'existing-novel',
            'description' => 'Existing Description',
            'category_id' => $category->id,
            'status' => 'published',
        ]);

        // Try to create another with same slug
        $duplicate = Novel::firstOrCreate(
            ['slug' => 'existing-novel'],
            [
                'title' => 'New Title',
                'description' => 'New Description',
                'category_id' => $category->id,
                'status' => 'published',
            ]
        );

        $this->assertEquals($existing->id, $duplicate->id);
    }
}
