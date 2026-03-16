<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class GalleryController extends Controller
{
    public function show()
    {
        // Recupera tutte le proprietà (modifica la query secondo le tue esigenze)
        $properties = Property::all();
        // Passa le proprietà alla vista
        return view('public.partials.widgets.gallery', compact('properties'));
    }
}
