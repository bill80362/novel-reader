@extends('layouts.app')

@section('title', config('app.name') . ' - 首頁')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <div class="md:col-span-2">
        <h2 class="text-3xl font-bold mb-6">精選推薦</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @forelse($featured as $novel)
            <a href="{{ route('novels.show', $novel->slug) }}" class="group">
                <div class="bg-slate-200 dark:bg-slate-700 rounded aspect-[3/4] overflow-hidden mb-2">
                    @if($novel->cover_image)
                        <img src="{{ Storage::url($novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400 dark:text-slate-300">無封面</div>
                    @endif
                </div>
                <h3 class="font-semibold truncate group-hover:text-blue-600">{{ $novel->title }}</h3>
            </a>
            @empty
            <p class="text-slate-500">暫無精選小說</p>
            @endforelse
        </div>
    </div>

    <div>
        <h3 class="text-xl font-bold mb-4">最新更新</h3>
        <div class="space-y-3">
            @forelse($latest as $chapter)
            <a href="{{ route('novels.read', [$chapter->novel->slug, $chapter->slug]) }}" class="block p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded transition">
                <p class="font-semibold text-sm truncate">{{ $chapter->novel->title }}</p>
                <p class="text-xs text-slate-500 truncate">{{ $chapter->title }}</p>
                <p class="text-xs text-slate-400">{{ $chapter->published_at?->diffForHumans() }}</p>
            </a>
            @empty
            <p class="text-slate-500">暫無章節</p>
            @endforelse
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ route('novels.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">瀏覽全部小說</a>
</div>
@endsection
