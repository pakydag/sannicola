<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$section = \App\Models\Section::where('slug', '2-it')->first();
echo "Section 2-it: ";
if ($section) {
    echo "Exists. Visibile: " . $section->visibile . " ID: " . $section->id . "\n";
} else {
    echo "Not found\n";
}

$article = \App\Models\Article::where('slug', 'come-eravano-at')->first();
echo "Article come-eravano-at: ";
if ($article) {
    echo "Exists. Section ID: " . $article->section_id . " ID: " . $article->id . "\n";
} else {
    echo "Not found\n";
}
