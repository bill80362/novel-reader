<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_200(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    public function test_novels_list_page_returns_200(): void
    {
        $response = $this->get(route('novels.index'));
        $response->assertStatus(200);
    }

    public function test_search_page_returns_200(): void
    {
        $response = $this->get(route('search'));
        $response->assertStatus(200);
    }

    public function test_published_novel_detail_returns_200(): void
    {
        $novel = Novel::factory()->create(['status' => 'published']);
        $response = $this->get(route('novels.show', $novel->slug));
        $response->assertStatus(200);
    }

    public function test_draft_novel_detail_returns_404(): void
    {
        $novel = Novel::factory()->create(['status' => 'draft']);
        $response = $this->get(route('novels.show', $novel->slug));
        $response->assertStatus(404);
    }

    public function test_chapter_read_returns_200(): void
    {
        $novel = Novel::factory()->create(['status' => 'published']);
        $chapter = Chapter::factory()->create(['novel_id' => $novel->id]);

        $response = $this->get(route('novels.read', [$novel->slug, $chapter->slug]));
        $response->assertStatus(200);
    }

    public function test_draft_novel_chapter_returns_404(): void
    {
        $novel = Novel::factory()->create(['status' => 'draft']);
        $chapter = Chapter::factory()->create(['novel_id' => $novel->id]);

        $response = $this->get(route('novels.read', [$novel->slug, $chapter->slug]));
        $response->assertStatus(404);
    }
}
