<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    private function stripDomain($url)
    {
        if (empty($url) || !is_string($url)) return $url;
        $baseUrl = config('app.url');
        return str_replace($baseUrl, '', $url);
    }

    private function processDataRecursive($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->processDataRecursive($value);
            }
        } elseif (is_string($data)) {
            return $this->stripDomain($data);
        }
        return $data;
    }

    public function store(Request $request, Article $articolo)
    {
        $validated = $request->validate([
            'titolo' => 'nullable|string|max:255',
            'tipo' => 'required|string|in:gallery,video,mirror_blocks,global_widget,single_block,section_grid,info_blocks,booking_structures',
            'data' => 'nullable|array',
        ]);

        $maxOrdine = $articolo->widgets()->max('ordine') ?? 0;

        $articolo->widgets()->create([
            'titolo' => $validated['titolo'] ?? null,
            'tipo' => $validated['tipo'],
            'data' => $this->processDataRecursive($validated['data'] ?? []),
            'ordine' => $maxOrdine + 1,
        ]);

        return redirect()->back()->with('success', 'Widget aggiunto con successo.');
    }

    public function destroy(Widget $widget)
    {
        $widget->delete();
        return redirect()->back()->with('success', 'Widget eliminato con successo.');
    }

    // Optional: method to reorder widgets via AJAX
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:widgets,id',
        ]);

        foreach ($request->order as $index => $widgetId) {
            Widget::where('id', $widgetId)->update(['ordine' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
