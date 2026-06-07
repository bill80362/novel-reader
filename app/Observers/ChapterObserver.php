<?php

namespace App\Observers;

use App\Models\Chapter;

class ChapterObserver
{
    private function calculateWordCount(Chapter $chapter): void
    {
        $chapter->word_count = mb_strlen(strip_tags((string) $chapter->content));
    }

    /**
     * Handle the Chapter "creating" event.
     */
    public function creating(Chapter $chapter): void
    {
        $this->calculateWordCount($chapter);
    }

    /**
     * Handle the Chapter "updating" event.
     */
    public function updating(Chapter $chapter): void
    {
        if ($chapter->isDirty('content')) {
            $this->calculateWordCount($chapter);
        }
    }
}
