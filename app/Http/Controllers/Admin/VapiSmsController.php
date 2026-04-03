<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VapiSms;
use Illuminate\Http\Request;

class VapiSmsController extends Controller
{
    public function index()
    {
        $messages = VapiSms::with('contact')
            ->orderBy('received_at', 'desc')
            ->paginate(20);

        return view('admin.vapi.sms.index', compact('messages'));
    }

    public function destroy(VapiSms $sms)
    {
        $sms->delete();
        return redirect()->back()->with('success', 'Messaggio eliminato.');
    }
}
