<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

#[Signature('app:generate-sitemap')]
#[Description('Generate XML sitemap for published novels and chapters')]
class GenerateSitemap extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        // Add homepage
        $sitemap->add(Url::create(route('home'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(1.0));

        // Add novels listing
        $sitemap->add(Url::create(route('novels.index'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));

        // Add search page
        $sitemap->add(Url::create(route('search'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5));

        // Add published novels
        Novel::published()->each(function (Novel $novel) use ($sitemap) {
            $sitemap->add(Url::create(route('novels.show', $novel->slug))
                ->setLastModificationDate($novel->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        });

        // Add chapters
        Chapter::whereHas('novel', fn ($q) => $q->published())
            ->each(function (Chapter $chapter) use ($sitemap) {
                $sitemap->add(Url::create(route('novels.read', [$chapter->novel->slug, $chapter->slug]))
                    ->setLastModificationDate($chapter->published_at ?? $chapter->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_NEVER)
                    ->setPriority(0.7));
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at '.public_path('sitemap.xml'));
    }
}
