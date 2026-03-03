<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Show the form for editing the settings.
     */
    public function edit()
    {
        // Peschiamo tutti i settings dal database
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update the settings in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_logo' => 'nullable|string',
            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
            'stripe_key' => 'nullable|string',
            'stripe_secret' => 'nullable|string',
            'paypal_client_id' => 'nullable|string',
            'paypal_secret' => 'nullable|string',
            'paypal_mode' => 'nullable|string',
            'bonifico_intestazione' => 'nullable|string',
            'bonifico_banca' => 'nullable|string',
            'bonifico_iban' => 'nullable|string',
        ]);

        if (auth()->user()->email === 'admin@admin.com') {
            $validated['shop_enabled'] = $request->has('shop_enabled') ? '1' : '0';
            $validated['booking_enabled'] = $request->has('booking_enabled') ? '1' : '0';
        }

        // Handle Checkboxes individually since unchecked checkboxes are not sent in request
        $validated['payment_stripe_enabled'] = $request->has('payment_stripe_enabled') ? '1' : '0';
        $validated['payment_bonifico_enabled'] = $request->has('payment_bonifico_enabled') ? '1' : '0';
        $validated['payment_contrassegno_enabled'] = $request->has('payment_contrassegno_enabled') ? '1' : '0';
        $validated['payment_paypal_enabled'] = $request->has('payment_paypal_enabled') ? '1' : '0';

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Configurazione aggiornata con successo.');
    }
}
