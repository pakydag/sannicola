<?php
$files = [
    'resources/views/admin/articoli/edit.blade.php',
    'resources/views/admin/global_widgets/create.blade.php',
    'resources/views/admin/global_widgets/edit.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // Look for:
    // <label class="block text-gray-700 text-xs font-bold mb-1">Testo del blocco (Accetta HTML)</label>
    // <textarea name="data[items][{{ $index }}][text]" ...>...</textarea>
    
    // Pattern to match label + textarea + closing tag
    // We want to capture the name attribute and the textarea content
    $pattern = '/(<label class="block text-gray-700 text-xs font-bold mb-1">Testo del blocco \(Accetta HTML\)<\/label>\s*<textarea name="([^"]+)text([^"]*)"[^>]*>.*?<\/textarea>)/s';

    $content = preg_replace_callback($pattern, function($matches) {
        $originalBlock = $matches[1];
        
        // Find the full name attribute inside the original block
        preg_match('/name="([^"]+text[^"]*)"/', $originalBlock, $nameMatch);
        $nameAttr = $nameMatch[1];
        
        // Replace 'text' with 'text_en' in the name attribute
        $enNameAttr = str_replace('[text]', '[text_en]', $nameAttr);
        if ($enNameAttr === $nameAttr) {
             // Try another format if it's different
             $enNameAttr = str_replace('text', 'text_en', $nameAttr);
        }

        // Get the value of the text_en if it's using {{ $block['text'] ?? '' }}
        $enBlockValue = '';
        if (strpos($originalBlock, "{{ \$block['text'] ?? '' }}") !== false) {
            $enBlockValue = "{{ \$block['text_en'] ?? '' }}";
        } elseif (strpos($originalBlock, 'x-model="item.text"') !== false) {
            $enBlockValue = '';
        }
        
        // Replace x-model if present
        $enTextarea = preg_replace('/x-model="[^"]+"/', 'x-model="item.text_en"', $originalBlock);
        
        // Build the english block
        $enHtml = "\n" . '                                                @if($englishEnabled)' . "\n";
        $enHtml .= '                                                    <label class="block text-gray-700 text-xs font-bold mb-1 mt-2">Testo del blocco (Accetta HTML) [INGLESE]</label>' . "\n";
        
        // Extract the textarea tag from the original to modify it
        preg_match('/(<textarea[^>]+>)(.*?)<\/textarea>/s', $enTextarea, $taMatch);
        $textareaTag = $taMatch[1];
        
        // Replace name
        $textareaTag = preg_replace('/name="[^"]+"/', 'name="'.$enNameAttr.'"', $textareaTag);
        
        // We can add a background color class to the english field to distinguish it
        $textareaTag = str_replace('class="', 'class="bg-indigo-50/30 ', $textareaTag);
        
        $enHtml .= '                                                    ' . $textareaTag . $enBlockValue . '</textarea>' . "\n";
        $enHtml .= '                                                @endif';
        
        return $originalBlock . $enHtml;
        
    }, $content);

    // Some places might have `englishEnabled` missing, but edit and create now have it.
    
    file_put_contents($file, $content);
    echo "Patched $file\n";
}
