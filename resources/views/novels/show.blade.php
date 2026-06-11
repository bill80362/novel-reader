@extends('layouts.app')

@section('title', $novel->title . ' - ' . config('app.name'))
@section('description', $novel->seo_description ?? Str::limit($novel->description, 160))
@section('og_title', $novel->seo_title ?? $novel->title)
@section('og_description', $novel->seo_description ?? Str::limit($novel->description, 160))
@section('og_image', $novel->cover_image ? Storage::url($novel->cover_image) : '')
@section('og_type', 'book')

@section('schema')
@php
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'Book',
    'name' => $novel->title,
    'description' => $novel->seo_description ?? Str::limit($novel->description, 160),
    'author' => [
        '@type' => 'Organization',
        'name' => config('app.name'),
    ],
    'genre' => $novel->category?->name,
    'datePublished' => $novel->created_at->toIso8601String(),
    'dateModified' => $novel->updated_at->toIso8601String(),
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => 5,
        'bestRating' => 5,
        'ratingCount' => $novel->view_count,
    ],
];
if ($novel->cover_image) {
    $schemaData['image'] = Storage::url($novel->cover_image);
}
@endphp
<script type="application/ld+json">
{!! json_encode($schemaData) !!}
</script>
@endsection

@section('content')
<x-breadcrumb :items="[['label' => '小說列表', 'url' => route('novels.index')], ['label' => $novel->title]]" />

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div>
        @if($novel->cover_image)
            <img src="{{ Storage::url($novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full rounded shadow-lg mb-4">
        @else
            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded aspect-[3/4] flex items-center justify-center mb-4 dark:text-slate-300">無封面</div>
        @endif

        <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded">
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">
                <strong>狀態：</strong>
                <span class="@if($novel->status === 'published') text-green-600 @elseif($novel->status === 'completed') text-blue-600 @endif">
                    {{ match($novel->status) { 'published' => '連載中', 'completed' => '已完結', 'draft' => '草稿', default => $novel->status } }}
                </span>
            </p>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">
                <strong>分類：</strong> {{ $novel->category?->name }}
            </p>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">
                <strong>瀏覽次數：</strong> {{ number_format($novel->view_count) }}
            </p>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                <strong>章節數：</strong> {{ $chapters->count() }}
            </p>
        </div>
    </div>

    <div class="md:col-span-2">
        <h1 class="text-3xl font-bold mb-2">{{ $novel->title }}</h1>

        @if($novel->tags->count())
        <div class="mb-4 flex flex-wrap gap-2">
            @foreach($novel->tags as $tag)
                <span class="bg-slate-200 dark:bg-slate-700 px-3 py-1 rounded text-sm">{{ $tag->name }}</span>
            @endforeach
        </div>
        @endif

        <div class="prose prose-invert max-w-none mb-6">
            {!! nl2br(e($novel->description)) !!}
        </div>

        <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded mb-6">
            <p class="text-sm text-slate-600 dark:text-slate-400">
                更新於 {{ $novel->updated_at->format('Y-m-d H:i') }}
            </p>
        </div>

        <h2 class="text-2xl font-bold mb-4">章節列表</h2>

        <div class="space-y-2 max-h-96 overflow-y-auto">
            @forelse($chapters as $chapter)
            <a href="{{ route('novels.read', [$novel->slug, $chapter->slug]) }}"
               class="block p-3 border rounded hover:bg-blue-50 dark:hover:bg-slate-800 transition"
               onclick="localStorage.setItem('lastChapter_{{ $novel->id }}', '{{ $chapter->id }}'); localStorage.setItem('lastNovel', '{{ $novel->id }}');">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $chapter->chapter_number }}. {{ $chapter->title }}</p>
                        <p class="text-xs text-slate-500">{{ $chapter->word_count }} 字 • {{ $chapter->view_count }} 瀏覽</p>
                    </div>
                </div>
            </a>
            @empty
            <p class="text-slate-500">暫無章節</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
