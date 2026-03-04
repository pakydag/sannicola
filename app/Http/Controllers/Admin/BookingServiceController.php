<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingServiceCategory;
use App\Models\BookingService;
use Illuminate\Http\Request;

class BookingServiceController extends Controller
{
    public function index()
    {
        $categories = BookingServiceCategory::with('services')->orderBy('ordine')->get();
        return view('admin.booking.services.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'icona' => 'required|string|max:10',
        ]);

        $maxOrdine = BookingServiceCategory::max('ordine') ?? 0;

        BookingServiceCategory::create([
            'nome' => $request->nome,
            'icona' => $request->icona,
            'ordine' => $maxOrdine + 1,
        ]);

        return back()->with('success', 'Categoria creata con successo.');
    }

    public function updateCategory(Request $request, BookingServiceCategory $category)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'icona' => 'required|string|max:10',
        ]);

        $category->update([
            'nome' => $request->nome,
            'icona' => $request->icona,
        ]);

        return back()->with('success', 'Categoria aggiornata con successo.');
    }

    public function destroyCategory(BookingServiceCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Categoria eliminata con successo.');
    }

    public function storeService(Request $request, BookingServiceCategory $category)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $maxOrdine = $category->services()->max('ordine') ?? 0;

        $category->services()->create([
            'nome' => $request->nome,
            'ordine' => $maxOrdine + 1,
        ]);

        return back()->with('success', 'Servizio aggiunto con successo.');
    }

    public function destroyService(BookingService $service)
    {
        $service->delete();
        return back()->with('success', 'Servizio eliminato con successo.');
    }
}
