@php
    $columns = $widget->data['columns'] ?? 3;
    $items = $widget->data['items'] ?? [];
    
    $gridClasses = 'grid gap-12';
    if ($columns == 1) $gridClasses .= ' grid-cols-1';
    elseif ($columns == 2) $gridClasses .= ' grid-cols-1 sm:grid-cols-2';
    elseif ($columns == 3) $gridClasses .= ' grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
    elseif ($columns == 4) $gridClasses .= ' grid-cols-2 lg:grid-cols-4';
    elseif ($columns == 6) $gridClasses .= ' grid-cols-2 sm:grid-cols-3 lg:grid-cols-6';
    else $gridClasses .= ' grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
@endphp

@if(!empty($items))
    <div class="{{ $gridClasses }}">
        @foreach($items as $item)
            @if(!empty($item['image']) || !empty($item['text']))
                <div class="flex flex-col items-center text-center group">
                    @if(!empty($item['image']))
                        <div class="mb-5 relative">
                            <div class="absolute -inset-2 bg-indigo-50 opacity-0 group-hover:transition-opacity duration-300"></div>
                            <img src="{{ asset($item['image']) }}" alt="Icon" class="h-16 w-16 object-contain relative transition-transform duration-300 group-hover:scale-110">
                        </div>
                    @endif
                    @php
                        $text = (app()->getLocale() === 'en' && !empty($item['text_en'])) ? $item['text_en'] : ($item['text'] ?? '');
                    @endphp
                    @if(!empty($text))
                        <div class="prose prose-sm prose-indigo text-gray-600 max-w-none">
                            {!! $text !!}
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
@else
    <p class="text-gray-500 italic text-center">Nessun blocco inserito.</p>
@endif
