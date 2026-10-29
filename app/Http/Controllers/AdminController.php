<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopProduct;
use App\Models\ShopOrder;
use App\Models\Customer;
use App\Models\ContactRequest;
use App\Models\Setting;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Metriche reali dal database
        $totalProducts = ShopProduct::count();
        $totalOrders = ShopOrder::count();
        $totalRevenue = ShopOrder::where('stato_pagamento', '!=', 'annullato')->sum('totale_ordine');
        $pendingOrders = ShopOrder::where('stato', 'nuovo')->count();
        $totalCustomers = Customer::count();
        $recentOrders = ShopOrder::with('customer')->latest()->take(5)->get();

        // Prodotti reali per arricchire la sezione "Prodotti più cercati"
        $dbProducts = ShopProduct::with(['category', 'variants'])
            ->where('visibile', true)
            ->take(8)
            ->get();

        // Simulazione dati sintetici Analytics & Ricerche
        $ga4Connected = !empty(Setting::where('key', 'tracking_ga_analytics')->value('value'));

        // Query di ricerca più frequenti nello shop
        $topSearchQueries = [
            [
                'query' => 'porchetta',
                'volume' => 1420,
                'ctr' => '88%',
                'risultati' => 14,
                'trend' => '+32%',
                'trovato' => true,
            ],
            [
                'query' => 'parmigiana',
                'volume' => 1180,
                'ctr' => '82%',
                'risultati' => 6,
                'trend' => '+24%',
                'trovato' => true,
            ],
            [
                'query' => 'panzerotti',
                'volume' => 950,
                'ctr' => '79%',
                'risultati' => 8,
                'trend' => '+18%',
                'trovato' => true,
            ],
            [
                'query' => 'pane ai cereali',
                'volume' => 820,
                'ctr' => '76%',
                'risultati' => 4,
                'trend' => '+15%',
                'trovato' => true,
            ],
            [
                'query' => 'pasta al forno',
                'volume' => 740,
                'ctr' => '71%',
                'risultati' => 5,
                'trend' => '+9%',
                'trovato' => true,
            ],
            [
                'query' => 'speck trentino',
                'volume' => 590,
                'ctr' => '84%',
                'risultati' => 2,
                'trend' => '+7%',
                'trovato' => true,
            ],
            [
                'query' => 'senza glutine',
                'volume' => 460,
                'ctr' => '22%',
                'risultati' => 0,
                'trend' => '+45%',
                'trovato' => false, // Suggerimento: opportunità di catalogo!
            ],
            [
                'query' => 'senza lattosio',
                'volume' => 310,
                'ctr' => '19%',
                'risultati' => 0,
                'trend' => '+28%',
                'trovato' => false,
            ],
        ];

        return view('dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'totalCustomers',
            'recentOrders',
            'dbProducts',
            'topSearchQueries',
            'ga4Connected'
        ));
    }
}
