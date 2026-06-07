<meta name="description" content="{{ $description ?? config('app.description', '個人小說發佈平台') }}">
<meta name="keywords" content="{{ $keywords ?? '' }}">
<meta property="og:title" content="{{ $ogTitle ?? $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $ogDescription ?? $description ?? '' }}">
@if($ogImage ?? null)
<meta property="og:image" content="{{ $ogImage }}">
@endif
<meta property="og:type" content="{{ $ogType ?? 'website' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $twitterTitle ?? $ogTitle ?? $title ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $twitterDescription ?? $ogDescription ?? $description ?? '' }}">
@if($ogImage ?? null)
<meta name="twitter:image" content="{{ $ogImage }}">
@endif
