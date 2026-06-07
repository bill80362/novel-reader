<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;

class NovelController extends Controller
{
    public function home()
    {
        $featured = Novel::published()->where('is_featured', true)->limit(6)->get();
        $latest = Chapter::with('novel')
            ->orderByDesc('published_at')
            ->limit(10)
            ->get();

        return view('home', compact('featured', 'latest'));
    }

    public function index(Request $request)
    {
        $query = Novel::published();

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'latest') {
                $query->orderByDesc('updated_at');
            } elseif ($sort === 'popular') {
                $query->orderByDesc('view_count');
            }
        } else {
            $query->orderByDesc('updated_at');
        }

        $novels = $query->paginate(12);

        return view('novels.index', compact('novels'));
    }

    public function show($slug)
    {
        $novel = Novel::published()->where('slug', $slug)->firstOrFail();
        $chapters = $novel->chapters()->orderBy('chapter_number')->get();

        return view('novels.show', compact('novel', 'chapters'));
    }

    public function readChapter($novelSlug, $chapterSlug)
    {
        $novel = Novel::published()->where('slug', $novelSlug)->firstOrFail();
        $chapter = $novel->chapters()->where('slug', $chapterSlug)->firstOrFail();

        // Increment view count (atomic operation)
        Chapter::where('id', $chapter->id)->increment('view_count');
        Novel::where('id', $novel->id)->increment('view_count');

        $nextChapter = $novel->chapters()
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number')
            ->first();

        $prevChapter = $novel->chapters()
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderByDesc('chapter_number')
            ->first();

        return view('novels.read', compact('novel', 'chapter', 'nextChapter', 'prevChapter'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $results = [];

        if (! empty($query)) {
            $results = Novel::published()
                ->where('title', 'like', "%{$query}%")
                ->orWhereHas('chapters', function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%");
                })
                ->limit(20)
                ->get();
        }

        return view('search', compact('query', 'results'));
    }
}
