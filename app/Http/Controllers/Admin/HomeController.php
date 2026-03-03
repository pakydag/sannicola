<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBlock;
use App\Models\GlobalWidget;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function edit()
    {
        $homeBlocks = HomeBlock::with('globalWidget')->orderBy('ordine')->get();
        // Carica tutti i widget globali disponibili
        $globalWidgets = GlobalWidget::orderBy('titolo')->get();
        
        $settings = [
            'home_seo_title' => Setting::where('key', 'home_seo_title')->value('value'),
            'home_seo_description' => Setting::where('key', 'home_seo_description')->value('value'),
            'home_seo_image' => Setting::where('key', 'home_seo_image')->value('value'),
        ];
        
        return view('admin.home.edit', compact('homeBlocks', 'globalWidgets', 'settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'blocks' => 'nullable|array',
            'blocks.*' => 'exists:global_widgets,id'
        ]);

        // Pulisce la vecchia configurazione
        HomeBlock::query()->delete();

        // Salva il nuovo ordine
        if (!empty($validated['blocks'])) {
            foreach ($validated['blocks'] as $index => $globalWidgetId) {
                HomeBlock::create([
                    'global_widget_id' => $globalWidgetId,
                    'ordine' => $index
                ]);
            }
        }

        return redirect()->route('admin.home.edit')->with('success', 'Home Page aggiornata con successo.');
    }

    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'home_seo_title' => 'nullable|string|max:255',
            'home_seo_description' => 'nullable|string',
            'home_seo_image' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['success' => true, 'message' => 'Impostazioni SEO salvate correttamente.']);
    }

    public function preview(Request $request)
    {
        $blockIds = $request->input('blocks', '');
        $idsArray = array_filter(explode(',', $blockIds));
        
        $homeBlocks = collect();
        foreach($idsArray as $index => $id) {
            $widget = GlobalWidget::find($id);
            if ($widget) {
                // Creiamo un'istanza "fittizia" HomeBlock senza salvarla
                $block = new HomeBlock();
                $block->global_widget_id = $id;
                $block->ordine = $index;
                // Impostiamo manualmente la relazione usando il GlobalWidget esistente
                $block->setRelation('globalWidget', $widget);
                $homeBlocks->push($block);
            }
        }

        // Recuperiamo lo stesso set di articoli usati dalla home pubblica standard
        $ultimi_articoli = \App\Models\Article::where('visibile', true)->whereHas('section', function ($q) {
            $q->where('visibile', true);
        })->latest()->take(6)->get();

        return view('public.home', compact('ultimi_articoli', 'homeBlocks'));
    }
}
