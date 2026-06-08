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

    <footer class="bg-slate-800 mt-16 py-8">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm text-slate-400">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>

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
