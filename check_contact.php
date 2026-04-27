<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contact;

$phone = '3290952802';
$contact = Contact::where('phone', 'like', "%{$phone}")
    ->orWhere('mobile', 'like', "%{$phone}")
    ->first();

if ($contact) {
    echo "Contact found: #{$contact->id} - {$contact->first_name} {$contact->last_name}\n";
    echo "Phone: '{$contact->phone}'\n";
    echo "Mobile: '{$contact->mobile}'\n";
} else {
    echo "Contact NOT found for phone like %{$phone}\n";
    
    // List some contacts to see format
    echo "\nSample contacts:\n";
    foreach (Contact::limit(5)->get() as $c) {
        echo "ID: {$c->id}, Name: {$c->first_name}, Phone: '{$c->phone}', Mobile: '{$c->mobile}'\n";
    }
}
