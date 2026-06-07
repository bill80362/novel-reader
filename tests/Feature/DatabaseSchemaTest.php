<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_table_exists(): void
    {
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasTable('categories'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('categories', 'name'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('categories', 'slug'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('categories', 'description'));
    }

    public function test_tags_table_exists(): void
    {
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasTable('tags'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('tags', 'name'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('tags', 'slug'));
    }

    public function test_novels_table_exists(): void
    {
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasTable('novels'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('novels', 'title'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('novels', 'slug'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('novels', 'description'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('novels', 'category_id'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('novels', 'status'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('novels', 'view_count'));
    }

    public function test_chapters_table_exists(): void
    {
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasTable('chapters'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('chapters', 'novel_id'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('chapters', 'chapter_number'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('chapters', 'title'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('chapters', 'content'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('chapters', 'word_count'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('chapters', 'view_count'));
    }

    public function test_novel_tag_table_exists(): void
    {
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasTable('novel_tag'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('novel_tag', 'novel_id'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('novel_tag', 'tag_id'));
    }

    public function test_settings_table_exists(): void
    {
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasTable('settings'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('settings', 'key'));
        $this->assertTrue(DB::connection()->getSchemaBuilder()->hasColumn('settings', 'value'));
    }
}
