<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>

    <x-seo-meta
        :title="$__env->yieldContent('title', config('app.name'))"
        :description="$__env->yieldContent('description', '個人小說發佈平台')"
        :ogTitle="$__env->yieldContent('og_title')"
        :ogDescription="$__env->yieldContent('og_description')"
        :ogImage="$__env->yieldContent('og_image')"
        :ogType="$__env->yieldContent('og_type', 'website')"
        :keywords="$__env->yieldContent('keywords')"
    />

    @yield('schema')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dark bg-slate-950">
    <nav class="bg-slate-900 border-b">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-white">{{ config('app.name') }}</a>
                <div class="flex gap-4">
                    <a href="{{ route('novels.index') }}" class="hover:text-blue-400 text-slate-300">小說列表</a>
                    <a href="{{ route('search') }}" class="hover:text-blue-400 text-slate-300">搜尋</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8 text-slate-300">
        @yield('content')
    </main>

    <footer class="bg-slate-800 mt-16 py-6">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm text-slate-400 space-y-2">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="flex justify-center gap-4">
                <button @click="$dispatch('open-disclaimer')" class="hover:text-slate-200 underline-offset-2 hover:underline">
                    免責聲明
                </button>
                <span class="text-slate-600">|</span>
                <a href="{{ route('about') }}" class="hover:text-slate-200 underline-offset-2 hover:underline">
                    關於本站
                </a>
            </div>
        </div>
    </footer>

    <!-- Disclaimer Modal (Alpine.js) -->
    <div x-data="{ open: false }"
         @open-disclaimer.window="open = true"
         @keydown.escape.window="open = false"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70"
         style="display:none">
        <div @click.outside="open = false" class="bg-slate-900 max-w-lg mx-4 p-6 rounded-lg shadow-xl text-left">
            <h2 class="text-lg font-semibold text-white mb-3">免責聲明</h2>
            <div class="text-slate-300 space-y-3 text-sm leading-relaxed">
                <p>本站所有小說內容均由 AI 自動生成，僅供娛樂與測試用途，不代表任何真實人物、事件或立場。</p>
                <p>小說中的人物、情節、地點均為虛構，如有雷同，純屬巧合。</p>
                <p>本站不對 AI 生成內容的準確性、合法性或商業用途做任何承諾。使用者請自行評估風險。</p>
            </div>
            <button @click="open = false" class="mt-5 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded">
                關閉
            </button>
        </div>
    </div>

    <!-- GA4 -->
    @if($ga4Id = \App\Models\Setting::get('ga4_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $ga4Id }}');
    </script>
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
