@extends('layouts.app')

@section('title', '小說列表 - ' . config('app.name'))
@section('description', '瀏覽所有發佈的小說')

@section('content')
<x-breadcrumb :items="[['label' => '小說列表']]" />

<div class="mb-6">
    <h1 class="text-3xl font-bold mb-4">小說列表</h1>

    <div class="flex gap-4 flex-wrap">
        <form method="GET" action="{{ route('novels.index') }}" class="flex gap-2">
            <select name="category" class="px-3 py-2 border rounded dark:bg-slate-800">
                <option value="">全部分類</option>
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="sort" class="px-3 py-2 border rounded dark:bg-slate-800">
                <option value="latest" @selected(request('sort', 'latest') === 'latest')>最新更新</option>
                <option value="popular" @selected(request('sort') === 'popular')>最受歡迎</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">篩選</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
    @forelse($novels as $novel)
    <a href="{{ route('novels.show', $novel->slug) }}" class="group">
        <div class="bg-slate-200 dark:bg-slate-700 rounded aspect-[3/4] overflow-hidden mb-2">
            @if($novel->cover_image)
                <img src="{{ Storage::url($novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-400 dark:text-slate-300">無封面</div>
            @endif
        </div>
        <h3 class="font-semibold truncate group-hover:text-blue-600">{{ $novel->title }}</h3>
        <p class="text-xs text-slate-500">{{ $novel->category?->name }}</p>
        <p class="text-xs text-slate-400">{{ $novel->view_count }} 瀏覽</p>
    </a>
    @empty
    <p class="col-span-full text-center text-slate-500 py-8">暫無小說</p>
    @endforelse
</div>

{{ $novels->links() }}
@endsection
