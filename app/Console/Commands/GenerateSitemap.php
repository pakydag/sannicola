<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Setting;
use App\Models\Section;
use App\Models\Article;
use App\Models\ShopProduct;
use App\Models\ShopCategory;
use App\Models\ShopCollection;
use App\Models\BookingStructure;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        // 1. Pagine Base
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create('/contatti')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        // 2. Sezioni (solo visibili e con mostra_nel_menu o altri criteri? Usiamo solo visibile)
        Section::where('visibile', true)->get()->each(function (Section $section) use ($sitemap) {
            $sitemap->add(Url::create("/{$section->slug}")
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        });

        // 3. Articoli (solo visibili e di sezioni visibili)
        Article::where('visibile', true)->with('section')->get()->each(function (Article $article) use ($sitemap) {
            if ($article->section && $article->section->visibile) {
                $sitemap->add(Url::create("/{$article->section->slug}/{$article->slug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        });

        // 4. Modulo Shop
        $shopEnabled = Setting::where('key', 'shop_enabled')->value('value') == '1';
        if ($shopEnabled) {
            $sitemap->add(Url::create('/shop')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
            
            // Categorie Shop
            ShopCategory::where('visibile', true)->get()->each(function (ShopCategory $category) use ($sitemap) {
                $sitemap->add(Url::create("/shop/categoria/{$category->slug}")->setPriority(0.8));
            });

            // Collezioni Shop
            ShopCollection::where('visibile', true)->get()->each(function (ShopCollection $collection) use ($sitemap) {
                $sitemap->add(Url::create("/shop/collezione/{$collection->slug}")->setPriority(0.8));
            });

            // Prodotti Shop (visibili)
            ShopProduct::where('visibile', true)->with('collection')->get()->each(function (ShopProduct $product) use ($sitemap) {
                // Secondo web.php, la rotta è /shop/{collezione_slug}/{prodotto_slug}
                // Se non ha collezione, in alcune logiche si usa 'tutti' o la rotta fallisce.
                if ($product->collection && $product->collection->visibile) {
                     $sitemap->add(Url::create("/shop/{$product->collection->slug}/{$product->slug}")->setPriority(0.8));
                }
            });
        }

        // 5. Modulo Booking
        $bookingEnabled = Setting::where('key', 'booking_enabled')->value('value') == '1';
        // Nel caso il setting non esista, controlliamo se ci sono strutture attive per sicurezza o ci basiamo solo sul setting
        // Visto che in questo progetto spesso i moduli sono abilitati/disabilitati via Setting, lo usiamo.
        // Ma aggiungiamo un fallback: se non c'è il setting, potremmo controllare se esistono rotte booking (assumiamo che il modulo c'è).
        // Il check su Setting è il più sicuro.
        if ($bookingEnabled) {
            $sitemap->add(Url::create('/booking')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
            
            BookingStructure::where('attivo', true)->get()->each(function (BookingStructure $structure) use ($sitemap) {
                $sitemap->add(Url::create("/booking/{$structure->id}")->setPriority(0.8));
            });
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generata con successo in public/sitemap.xml');
    }
}
