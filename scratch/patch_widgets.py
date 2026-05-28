import re
import sys

def patch_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Assicurati che $englishEnabled sia definito all'inizio
    if '@php' not in content[:500]:
        content = re.sub(
            r'(<x-app-layout>)',
            r'\1\n@php\n    $englishEnabled = \\App\\Models\\Setting::where("key", "english_enabled")->value("value") == "1";\n@endphp',
            content
        )

    # Cerca i blocchi per titolo e duplicali con titolo_en
    # Regex per trovare: <div class="mb-4">...name="titolo"...</div>
    pattern_titolo = re.compile(
        r'(<div class="mb-4[^>]*>\s*<label[^>]*>(.*?Titolo.*?)</label>\s*<input[^>]+name="titolo"[^>]*>.*?</div>)',
        re.DOTALL
    )

    def replace_titolo(match):
        original_block = match.group(1)
        # Create English version
        en_block = original_block.replace('name="titolo"', 'name="titolo_en"')
        en_block = en_block.replace('Titolo', 'Titolo [INGLESE]')
        en_block = en_block.replace('required', '') # Not required for English
        
        replacement = f"""{original_block}
@if($englishEnabled)
    {en_block}
@endif"""
        return replacement

    content = pattern_titolo.sub(replace_titolo, content)

    # Now for other text fields in data: data[message], data[subtitle], data[button_text], data[testo], ecc.
    text_fields = [r'data\[message\]', r'data\[subtitle\]', r'data\[button_text\]', r'data\[testo\]', r'data\[descrizione\]']
    for field in text_fields:
        pattern_field = re.compile(
            rf'(<div[^>]*>\s*<label[^>]*>(.*?)</label>\s*<(?:input|textarea)[^>]+name="{field}"[^>]*>.*?</(?:div)>)',
            re.DOTALL
        )
        
        def replace_field(match):
            original_block = match.group(1)
            label = match.group(2)
            en_field = field.replace(r'\]', '_en]')
            en_field = en_field.replace(r'\[', '[')
            en_block = original_block.replace(f'name="{field.replace("\\", "")}"', f'name="{en_field}"')
            en_block = en_block.replace(label, f'{label} [INGLESE]')
            en_block = en_block.replace('required', '')
            
            replacement = f"""{original_block}
@if($englishEnabled)
    {en_block}
@endif"""
            return replacement

        content = pattern_field.sub(replace_field, content)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

patch_file(sys.argv[1])
