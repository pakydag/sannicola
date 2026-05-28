@php
    $sourceSectionId = $widget->data['source_section_id'] ?? null;
    $limit = $widget->data['limit'] ?? 6;
    $columnsCount = $widget->data['columns'] ?? 3;
    $articles = [];

    if ($sourceSectionId) {
        $section = \App\Models\Section::with(['articles' => function($query) use ($limit) {
            $query->where('visibile', true)->orderBy('created_at', 'desc')->take($limit);
        }])->find($sourceSectionId);

        if ($section) {
            $articles = $section->articles;
        }
    }

    // Determine Tailwind grid columns class based on user choice
    $gridClass = 'grid-cols-1 md:grid-cols-2';
    if ($columnsCount == 3) {
        $gridClass .= ' lg:grid-cols-3';
    } elseif ($columnsCount == 4) {
        $gridClass .= ' lg:grid-cols-4';
    } elseif ($columnsCount == 1) {
        $gridClass = 'grid-cols-1';
    }
@endphp

@if(count($articles) > 0)
    <div class="mb-16">
        @if(!empty($widget->titolo))
            <div class="titoli text-center mb-8">
                <h3 class="text-3xl leading-tight">{{ $widget->titolo }}</h3>
            </div>
        @endif

        <div class="grid {{ $gridClass }} gap-8">
            @foreach($articles as $article)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow flex flex-col h-full border border-gray-100">
                    <a href="{{ url($section->slug . '/' . $article->slug) }}" class="block relative h-48 overflow-hidden group">
                        @if($article->hasMedia('foto'))
                            <img src="{{ $article->getFirstMediaUrl('foto', 'thumb') }}" alt="{{ $article->titolo }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </a>

                    <div class="p-6 flex-1 flex flex-col titoli">
                        <a href="{{ url($section->slug . '/' . $article->slug) }}" class="block mt-2">
                            <h3 class="text-xl mb-2 line-clamp-2">
                                {{ $article->titolo }}
                            </h3>
                        </a>

                        @if($article->sottotitolo)
                            <p class="text-sm font-medium mb-3">{{ $article->sottotitolo }}</p>
                        @endif

                        <div class="text-gray-600 text-sm mb-4 line-clamp-3 ext-content flex-1">
                            {!! strip_tags($article->descrizione) !!}
                        </div>

                        <div class="mt-4">
                            <a href="{{ url($section->slug . '/' . $article->slug) }}" class="btn">
                                {{ app()->getLocale() === 'en' ? 'Read more' : 'Leggi tutto' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
