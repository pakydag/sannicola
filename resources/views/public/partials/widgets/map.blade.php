@php
    $embedCode = $widget->data['embed_code'] ?? '';
    $height = $widget->data['height'] ?? 450;
    
    // Cerchiamo di rendere l'iframe responsivo e di applicare l'altezza scelta
    // Rimuoviamo eventuali attributi width/height fissi e aggiungiamo stile inline
    if (strpos($embedCode, '<iframe') !== false) {
        // Pulizia attributi esistenti che potrebbero andare in conflitto
        $embedCode = preg_replace('/width="\d+"/', '', $embedCode);
        $embedCode = preg_replace('/height="\d+"/', '', $embedCode);
        
        // Iniezione stili
        $embedCode = str_replace('<iframe', '<iframe style="border:0; width:100%; height:'.$height.'px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"', $embedCode);
    }
@endphp

<div class="map-container w-full overflow-hidden">
    {!! $embedCode !!}
</div>
