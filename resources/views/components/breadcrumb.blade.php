<nav aria-label="麵包屑" class="mb-4">
    <ol class="flex flex-wrap gap-2 text-sm text-slate-600 dark:text-slate-400">
        <li>
            <a href="{{ route('home') }}" class="hover:text-blue-600">首頁</a>
        </li>
        @foreach($items as $item)
            <li class="before:content-['/'] before:mr-2">
                @if($loop->last)
                    <span>{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="hover:text-blue-600">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
