<?php

use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\Section;
use App\Models\GlobalWidget;
use App\Models\Widget;
use App\Models\BookingPhoto;
use App\Models\ShopProduct;
use App\Models\ShopVariant;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function sanitize($value) {
    if (is_string($value) && (str_contains($value, 'http://185.56.170.138/') || str_contains($value, 'http://web.eyukka.it/'))) {
        $value = preg_replace('/^https?:\/\/[^\/]+/', '', $value);
        echo "   [FIXED] $value\n";
    }
    return $value;
}

function sanitizeRecursive(&$data) {
    if (is_array($data)) {
        foreach ($data as $key => &$val) {
            if (is_array($val)) {
                sanitizeRecursive($val);
            } else {
                $val = sanitize($val);
            }
        }
    }
}

echo "Avvio sanificazione URL nel database...\n";

// 1. Articles
echo "\n--- Sanificazione Articoli ---\n";
Article::all()->each(function($article) {
    $originalFoto = $article->foto;
    $originalVideo = $article->video;
    $originalSeo = $article->seo_image;
    
    $article->foto = sanitize($article->foto);
    $article->video = sanitize($article->video);
    $article->seo_image = sanitize($article->seo_image);
    
    if ($article->isDirty()) {
        echo "Articolo ID {$article->id} aggiornato.\n";
        $article->save();
    }
});

// 2. Sections
echo "\n--- Sanificazione Sezioni ---\n";
Section::all()->each(function($section) {
    $section->seo_image = sanitize($section->seo_image);
    if ($section->isDirty()) {
        echo "Sezione ID {$section->id} aggiornata.\n";
        $section->save();
    }
});

// 3. Global Widgets
echo "\n--- Sanificazione Widget Globali ---\n";
GlobalWidget::all()->each(function($gw) {
    $data = $gw->data;
    sanitizeRecursive($data);
    $gw->data = $data;
    if ($gw->isDirty()) {
        echo "Global Widget ID {$gw->id} aggiornato.\n";
        $gw->save();
    }
});

// 4. Widgets (inline in articles)
echo "\n--- Sanificazione Widget Articoli ---\n";
Widget::all()->each(function($w) {
    $data = $w->data;
    sanitizeRecursive($data);
    $w->data = $data;
    if ($w->isDirty()) {
        echo "Widget ID {$w->id} aggiornato.\n";
        $w->save();
    }
});

// 5. Booking Photos
echo "\n--- Sanificazione Foto Booking ---\n";
BookingPhoto::all()->each(function($bp) {
    $bp->path = sanitize($bp->path);
    if ($bp->isDirty()) {
        echo "Booking Photo ID {$bp->id} aggiornata.\n";
        $bp->save();
    }
});

// 6. Shop Products
echo "\n--- Sanificazione Prodotti Shop ---\n";
ShopProduct::all()->each(function($p) {
    $data = $p->foto_aggiuntive;
    sanitizeRecursive($data);
    $p->foto_aggiuntive = $data;
    if ($p->isDirty()) {
        echo "Prodotto ID {$p->id} aggiornato.\n";
        $p->save();
    }
});

// 7. Shop Variants
echo "\n--- Sanificazione Varianti Shop ---\n";
ShopVariant::all()->each(function($v) {
    $v->foto = sanitize($v->foto);
    if ($v->isDirty()) {
        echo "Variante ID {$v->id} aggiornata.\n";
        $v->save();
    }
});

echo "\nFine sanificazione.\n";
