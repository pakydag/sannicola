@php
    $sourceSectionId = $widget->data['source_section_id'] ?? null;
    $limit = $widget->data['limit'] ?? 3;

    $specchioArticles = collect();
    if ($sourceSectionId) {
        $specchioArticles = \App\Models\Article::where('section_id', $sourceSectionId)
            ->where('visibile', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
@endphp

@if($specchioArticles->count() > 0)
    <div class="space-y-12">
        @foreach($specchioArticles as $index => $item)
            @php
                // index 0 -> left, index 1 -> right, index 2 -> left...
                // So flex-row-reverse should apply when index is odd (index % 2 !== 0)
                $isReversed = ($index % 2 !== 0);
            @endphp

            <div class="flex flex-col md:flex-row {{ $isReversed ? 'md:flex-row-reverse' : '' }} gap-8 items-center bg-white p-6 md:p-10 shadow-sm border border-gray-100 rounded-lg mt-10">

                <!-- Image Section -->
                <div class="w-full md:w-1/2 photo overflow-hidden h-[200px] lg:h-[300px]">
                    @if($item->hasMedia('foto'))
                        <a href="{{ route('public.articolo', ['sezione_slug' => $item->section->slug ?? $item->section->id.'-it', 'articolo_slug' => $item->slug ?? $item->id.'-it']) }}" class="block">
                            <img src="{{ $item->getFirstMediaUrl('foto', 'thumb') }}" alt="{{ $item->titolo }}" class="w-full h-full object-cover">
                        </a>
                    @else
                        <div class="w-full h-64 md:h-80 bg-gray-200 flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>

                <!-- Text Section -->
                <div class="titoli w-full md:w-1/2 flex flex-col justify-center">
                    <h4 class="text-2xl text-primary mb-2">
                        <a href="{{ route('public.articolo', ['sezione_slug' => $item->section->slug ?? $item->section->id.'-it', 'articolo_slug' => $item->slug ?? $item->id.'-it']) }}"">
                            {{ $item->titolo }}
                        </a>
                    </h4>

                    @if($item->sottotitolo)
                        <h5 class="text-lg font-medium text-secondary mb-4">{{ $item->sottotitolo }}</h5>
                    @endif

                    <div class="text-gray-600 mb-6 line-clamp-3">
                        {!! strip_tags($item->descrizione) !!}
                    </div>

                    <div>
                        <a href="{{ route('public.articolo', ['sezione_slug' => $item->section->slug ?? $item->section->id.'-it', 'articolo_slug' => $item->slug ?? $item->id.'-it']) }}" class="btn inline-flex">
                            Leggi di più
                            <svg class="w-4 h-4 ml-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
@else
    <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg border border-yellow-200">
        <p>Nessun articolo trovato per questa sezione sorgente.</p>
    </div>
@endif
