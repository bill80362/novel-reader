@extends('layouts.app')

@section('title', '搜尋 - ' . config('app.name'))
@section('description', '搜尋小說和章節')

@section('content')
<x-breadcrumb :items="[['label' => '搜尋']]" />

<div>
    <h1 class="text-3xl font-bold mb-6">搜尋小說</h1>

    <form method="GET" action="{{ route('search') }}" class="mb-8">
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ $query }}" placeholder="搜尋標題或章節..." class="flex-1 px-4 py-2 border rounded dark:bg-slate-800 dark:text-white">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">搜尋</button>
        </div>
    </form>

    @if(!empty($query))
        @if($results->count())
        <div>
            <h2 class="text-xl font-bold mb-4">找到 {{ $results->count() }} 筆結果</h2>
            <div class="space-y-4">
                @foreach($results as $novel)
                <a href="{{ route('novels.show', $novel->slug) }}" class="block p-4 border rounded hover:border-blue-600 dark:hover:border-blue-400 transition">
                    <h3 class="font-bold text-lg">{{ $novel->title }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ $novel->description }}</p>
                    <p class="text-xs text-slate-500 mt-2">
                        {{ $novel->category?->name }} •
                        {{ $novel->chapters()->count() }} 章 •
                        {{ $novel->view_count }} 瀏覽
                    </p>
                </a>
                @endforeach
            </div>
        </div>
        @else
        <p class="text-slate-500">未找到相關結果</p>
        @endif
    @else
        <p class="text-slate-500">輸入關鍵字開始搜尋</p>
    @endif
</div>
@endsection
