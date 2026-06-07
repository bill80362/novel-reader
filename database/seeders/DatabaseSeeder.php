<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Test Admin',
                'password' => 'password',
            ],
        );

        foreach ($this->categories() as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }

        foreach ($this->tags() as $tag) {
            Tag::query()->updateOrCreate(
                ['slug' => $tag['slug']],
                $tag,
            );
        }
    }

    /**
     * @return array<int, array{name: string, slug: string, description: string}>
     */
    private function categories(): array
    {
        return [
            [
                'name' => '玄幻',
                'slug' => 'xuanhuan',
                'description' => '以修煉、血脈、宗門和高武世界為主的奇幻類小說。',
            ],
            [
                'name' => '仙俠',
                'slug' => 'xianxia',
                'description' => '以修仙、飛升、門派與法寶為核心的東方仙俠故事。',
            ],
            [
                'name' => '武俠',
                'slug' => 'wuxia',
                'description' => '以江湖、門派、俠義與武功對決為主的傳統武俠題材。',
            ],
            [
                'name' => '都市',
                'slug' => 'urban',
                'description' => '現代城市背景下的職場、生活、創業與爽感故事。',
            ],
            [
                'name' => '言情',
                'slug' => 'romance',
                'description' => '以感情線、互動張力與情感成長為主的戀愛題材。',
            ],
            [
                'name' => '懸疑推理',
                'slug' => 'mystery',
                'description' => '以案件、線索、反轉與解謎為核心的故事類型。',
            ],
            [
                'name' => '科幻',
                'slug' => 'sci-fi',
                'description' => '結合未來科技、太空探索、人工智慧與想像世界的作品。',
            ],
            [
                'name' => '歷史',
                'slug' => 'historical',
                'description' => '以古代王朝、權謀、戰爭與人物傳記為背景的小說。',
            ],
            [
                'name' => '輕小說',
                'slug' => 'light-novel',
                'description' => '節奏較輕快，常見於校園、冒險、異世界與角色向作品。',
            ],
            [
                'name' => '遊戲競技',
                'slug' => 'game-competitive',
                'description' => '圍繞遊戲、電競、賽事與團隊對抗展開的題材。',
            ],
            [
                'name' => '同人',
                'slug' => 'fanfiction',
                'description' => '基於既有作品、角色或世界觀延伸創作的內容。',
            ],
            [
                'name' => '校園',
                'slug' => 'campus',
                'description' => '以學校、青春、社團與成長為主的校園題材。',
            ],
        ];
    }

    /**
     * @return array<int, array{name: string, slug: string}>
     */
    private function tags(): array
    {
        return [
            ['name' => '系統', 'slug' => 'system'],
            ['name' => '穿越', 'slug' => 'transmigration'],
            ['name' => '重生', 'slug' => 'rebirth'],
            ['name' => '升級流', 'slug' => 'leveling'],
            ['name' => '爽文', 'slug' => 'power-fantasy'],
            ['name' => '熱血', 'slug' => 'hot-blooded'],
            ['name' => '輕鬆', 'slug' => 'lighthearted'],
            ['name' => '治癒', 'slug' => 'healing'],
            ['name' => '甜寵', 'slug' => 'sweet'],
            ['name' => '虐戀', 'slug' => 'angst'],
            ['name' => '後宮', 'slug' => 'harem'],
            ['name' => '種田', 'slug' => 'farming'],
            ['name' => '無限流', 'slug' => 'infinite'],
            ['name' => '冒險', 'slug' => 'adventure'],
            ['name' => '戰鬥', 'slug' => 'combat'],
            ['name' => '權謀', 'slug' => 'political-intrigue'],
            ['name' => '校園', 'slug' => 'campus'],
            ['name' => '異能', 'slug' => 'supernatural'],
            ['name' => '末世', 'slug' => 'apocalypse'],
            ['name' => '慢熱', 'slug' => 'slow-burn'],
        ];
    }
}
