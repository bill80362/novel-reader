@extends('layouts.app')

@section('title', '關於本站')

@section('content')
<article class="prose prose-invert max-w-2xl mx-auto">
    <h1>關於本站</h1>

    <p>{{ config('app.name') }} 是一個個人小說閱讀平台，作為 AI 文字生成技術的實驗與展示。</p>

    <h2>作者</h2>
    <p>
        本站由 <strong>Bill Chen</strong> 建立與維護。
        一個喜歡寫程式、也喜歡看小說的開發者。
    </p>
    <p>
        想了解更多背景與經歷，歡迎查看
        <a href="http://resume.0921515408.com/" target="_blank" rel="noopener" class="text-blue-400 hover:text-blue-300">
            我的線上履歷 →
        </a>
    </p>

    <h2>關於內容</h2>
    <p>
        本站收錄的所有小說章節皆由 AI 自動生成，可能包含錯誤、不準確或冒犯性的內容，
        僅作為技術展示，<strong>不應視為正式創作</strong>。
    </p>
    <p>
        詳細說明請參考
        <button @click="$dispatch('open-disclaimer')" class="text-blue-400 hover:text-blue-300 underline-offset-2 hover:underline cursor-pointer">
            免責聲明
        </button>。
    </p>

    <p class="text-sm text-slate-500 mt-8">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </p>
</article>
@endsection
