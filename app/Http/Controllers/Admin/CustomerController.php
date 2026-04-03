<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $cleanSearch = preg_replace('/[^0-9]/', '', $search);
            
            $query->where(function($q) use ($search, $cleanSearch) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
                
                if (strlen($cleanSearch) >= 6) {
                    $lastDigits = substr($cleanSearch, -10);
                    $q->orWhere('phone', 'like', "%{$lastDigits}")
                      ->orWhere('mobile', 'like', "%{$lastDigits}");
                } else {
                    $q->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('mobile', 'like', "%{$search}%");
                }
            });
        }

        $customers = $query->latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|unique:contacts,email',
            'phone'        => 'nullable|string|max:20',
            'mobile'       => 'nullable|string|max:20',
        ]);

        $customer = Contact::create(array_merge($validated, [
            'is_active' => true,
        ]));

        return redirect()->route('admin.customers.index')->with('success', 'Contatto creato correttamente.');
    }

    public function show($id)
    {
        $customer = Contact::with(['shopOrders', 'bookings.structure', 'b2bOrders'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Contact::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Contact::findOrFail($id);
        
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|unique:contacts,email,' . $id,
            'phone'        => 'nullable|string|max:20',
            'mobile'       => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $id)->with('success', 'Dati del contatto aggiornati correttamente.');
    }

    public function addTag(Request $request, $id)
    {
        $customer = Contact::findOrFail($id);
        
        $request->validate([
            'tag' => 'required|string|max:50',
        ]);

        $tags = $customer->tags ?? [];
        $newTag = trim($request->tag);
        
        if ($newTag && !in_array($newTag, $tags)) {
            $tags[] = $newTag;
            $customer->update(['tags' => array_values($tags)]);
        }

        return back()->with('success', 'Tag aggiunto correttamente.');
    }

    public function removeTag(Request $request, $id)
    {
        $customer = Contact::findOrFail($id);
        
        $request->validate([
            'tag' => 'required|string',
        ]);

        $tags = $customer->tags ?? [];
        $tagToRemove = $request->tag;

        $tags = array_filter($tags, function($t) use ($tagToRemove) {
            return $t !== $tagToRemove;
        });

        $customer->update(['tags' => array_values($tags)]);

        return back()->with('success', 'Tag rimosso.');
    }

    public function destroy($id)
    {
        $customer = Contact::findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Contatto eliminato correttamente.');
    }

    public function exportCsv()
    {
        $fileName = 'crm_export_' . now()->format('Y-m-d_H-i') . '.csv';
        $customers = Contact::all();

        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Nome', 'Cognome', 'Azienda', 'Email', 'Telefono', 'Cellulare', 'Tags', 'Creato il');

        $callback = function() use($customers, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            fputcsv($file, $columns, ';');

            foreach ($customers as $customer) {
                $row['Nome']       = $customer->first_name;
                $row['Cognome']    = $customer->last_name;
                $row['Azienda']    = $customer->company_name;
                $row['Email']      = $customer->email;
                $row['Telefono']   = $customer->phone;
                $row['Cellulare']  = $customer->mobile;
                $row['Tags']       = is_array($customer->tags) ? implode(', ', $customer->tags) : '';
                $row['Creato il']  = $customer->created_at->format('d/m/Y H:i');

                fputcsv($file, array(
                    $row['Nome'], 
                    $row['Cognome'], 
                    $row['Azienda'], 
                    $row['Email'], 
                    $row['Telefono'], 
                    $row['Cellulare'], 
                    $row['Tags'], 
                    $row['Creato il']
                ), ';');
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }
}
