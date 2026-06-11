@extends('layouts.app')

@section('title', $chapter->title . ' - ' . config('app.name'))
@section('description', Str::limit(strip_tags($chapter->content), 160))
@section('og_title', $chapter->title . ' - ' . $novel->title)
@section('og_description', Str::limit(strip_tags($chapter->content), 160))
@section('og_image', $novel->cover_image ? Storage::url($novel->cover_image) : '')
@section('og_type', 'article')

@section('schema')
@php
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $chapter->title,
    'description' => Str::limit(strip_tags($chapter->content), 160),
    'articleSection' => $novel->title,
    'author' => [
        '@type' => 'Organization',
        'name' => config('app.name'),
    ],
    'datePublished' => $chapter->published_at?->toIso8601String(),
    'dateModified' => $chapter->updated_at->toIso8601String(),
    'wordCount' => $chapter->word_count,
];
@endphp
<script type="application/ld+json">
{!! json_encode($schemaData) !!}
</script>
@endsection

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ fontSize: localStorage.getItem('fontSize') || 1 }">
    <x-breadcrumb :items="[['label' => '小說列表', 'url' => route('novels.index')], ['label' => $novel->title, 'url' => route('novels.show', $novel->slug)], ['label' => '第 ' . $chapter->chapter_number . ' 章']]" />

    <div class="bg-slate-100 dark:bg-slate-800 dark:text-white p-4 rounded mb-4 flex justify-between items-center">
        <div>
            <a href="{{ route('novels.show', $novel->slug) }}" class="text-blue-600 dark:text-blue-400 hover:underline">← {{ $novel->title }}</a>
            <h1 class="text-2xl font-bold">第 {{ $chapter->chapter_number }} 章：{{ $chapter->title }}</h1>
        </div>
        <div class="flex gap-2">
            <button @click="fontSize = Math.max(0.8, fontSize - 0.1); localStorage.setItem('fontSize', fontSize)" class="px-2 py-1 border rounded dark:text-white dark:border-slate-500">A−</button>
            <button @click="fontSize = Math.min(1.5, fontSize + 0.1); localStorage.setItem('fontSize', fontSize)" class="px-2 py-1 border rounded dark:text-white dark:border-slate-500">A+</button>
        </div>
    </div>

    <div class="prose prose-invert max-w-none leading-relaxed" :style="{ fontSize: fontSize + 'rem' }">
        {!! $chapter->content !!}
    </div>

    <div class="mt-8 flex gap-4 justify-between">
        @if($prevChapter)
            <a href="{{ route('novels.read', [$novel->slug, $prevChapter->slug]) }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 rounded hover:bg-slate-300 dark:hover:bg-slate-600">
                ← 上一章：{{ $prevChapter->title }}
            </a>
        @else
            <div></div>
        @endif

        @if($nextChapter)
            <a href="{{ route('novels.read', [$novel->slug, $nextChapter->slug]) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                下一章：{{ $nextChapter->title }} →
            </a>
        @else
            <a href="{{ route('novels.show', $novel->slug) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                返回目錄 →
            </a>
        @endif
    </div>

    <div class="mt-4 text-xs text-slate-500 text-center">
        {{ $chapter->word_count }} 字 • {{ $chapter->published_at?->format('Y-m-d H:i') }}
    </div>
</div>
@endsection
