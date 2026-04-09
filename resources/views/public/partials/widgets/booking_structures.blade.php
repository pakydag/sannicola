@php
    $limit = $widget->data['limit'] ?? 3;
    $columns = $widget->data['columns'] ?? 3;
    
    $structures = \App\Models\BookingStructure::where('attivo', true)
        ->with('photos')
        ->limit($limit)
        ->get();
        
    $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
    if ($columns == 1) $gridClass = 'grid-cols-1';
    elseif ($columns == 2) $gridClass = 'grid-cols-1 md:grid-cols-2';
    elseif ($columns == 3) $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
    elseif ($columns == 4) $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
@endphp

@if($structures->count() > 0)
    <div class="grid {{ $gridClass }} gap-8">
        @foreach($structures as $s)
            @include('public.partials.booking.structure_card', ['structure' => $s])
        @endforeach
    </div>
@else
    <p class="text-gray-500 italic text-center">Nessuna struttura da visualizzare.</p>
@endif
