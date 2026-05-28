<?php
$file = $argv[1];
$content = file_get_contents($file);

if (strpos($content, '$englishEnabled') === false) {
    $content = str_replace('<x-app-layout>', "<x-app-layout>\n@php\n    \$englishEnabled = \\App\\Models\\Setting::where('key', 'english_enabled')->value('value') == '1';\n@endphp", $content);
}

$pattern = '/(<div[^>]*>\s*<label[^>]*>(.*?)<\/label>\s*<(?:input|textarea)[^>]+name="([^"]+)"[^>]*>.*?<\/(?:div)>)/is';

$content = preg_replace_callback($pattern, function($matches) {
    $block = $matches[1];
    $label = $matches[2];
    $name = $matches[3];

    $allowed_names = ['titolo', 'data[subtitle]', 'data[message]', 'data[button_text]', 'data[testo]', 'data[descrizione]', 'data[title]'];
    
    if (in_array($name, $allowed_names)) {
        // Prevent recursive matching by checking if we already patched this
        if (strpos($block, '[INGLESE]') !== false) return $block;

        $en_name = str_replace([']', '['], ['_en]', '['], $name);
        if ($name === 'titolo') {
            $en_name = 'titolo_en';
        }
        
        $en_block = str_replace('name="' . $name . '"', 'name="' . $en_name . '"', $block);
        $en_block = str_replace('>'.$label.'</label>', '>'.$label.' [INGLESE]</label>', $en_block);
        $en_block = str_replace('required', '', $en_block);
        
        // visual cues
        $en_block = str_replace('class="mb-4', 'class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50', $en_block);
        
        return $block . "\n@if(\$englishEnabled)\n" . $en_block . "\n@endif\n";
    }
    return $block;
}, $content);

file_put_contents($file, $content);
echo "Patched $file\n";
