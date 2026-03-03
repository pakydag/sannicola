<?php

namespace App\Http\Controllers;

use App\Models\GlobalWidget;
use Illuminate\Http\Request;

class GlobalWidgetController extends Controller
{
    public function index()
    {
        $widgets = GlobalWidget::orderBy('id', 'desc')->paginate(20);
        return view('admin.global_widgets.index', compact('widgets'));
    }

    public function create()
    {
        return view('admin.global_widgets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titolo' => 'required|string|max:255',
            'tipo' => 'required|string|in:gallery,video,mirror_blocks,single_block,section_grid',
            'data' => 'nullable|array',
        ]);

        GlobalWidget::create([
            'titolo' => $validated['titolo'],
            'tipo' => $validated['tipo'],
            'data' => $validated['data'] ?? [],
        ]);

        return redirect()->route('admin.global-widgets.index')->with('success', 'Widget Globale creato con successo.');
    }

    public function edit(GlobalWidget $globalWidget)
    {
        return view('admin.global_widgets.edit', compact('globalWidget'));
    }

    public function update(Request $request, GlobalWidget $globalWidget)
    {
        $validated = $request->validate([
            'titolo' => 'required|string|max:255',
            'data' => 'nullable|array',
        ]);

        $globalWidget->update([
            'titolo' => $validated['titolo'],
            'data' => $validated['data'] ?? [],
        ]);

        return redirect()->route('admin.global-widgets.index')->with('success', 'Widget Globale aggiornato con successo.');
    }

    public function destroy(GlobalWidget $globalWidget)
    {
        $globalWidget->delete();
        return redirect()->route('admin.global-widgets.index')->with('success', 'Widget Globale eliminato.');
    }
}
