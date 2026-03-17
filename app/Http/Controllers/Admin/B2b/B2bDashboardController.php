<?php

namespace App\Http\Controllers\Admin\B2b;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2bDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'agents_count' => \App\Models\User::where('role', 'agent')->count(),
            'customers_count' => \App\Models\B2bCustomer::count(),
            'pending_orders_count' => \App\Models\B2bOrder::where('status', 'pending')->count(),
            'total_revenue' => \App\Models\B2bOrder::where('status', 'confirmed')->sum('total_amount'),
        ];

        $recent_orders = \App\Models\B2bOrder::with('agent', 'customer')->latest()->take(5)->get();

        return view('admin.b2b.dashboard.index', compact('stats', 'recent_orders'));
    }
}
