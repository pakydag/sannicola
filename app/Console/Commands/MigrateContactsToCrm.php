<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\BookingCustomer;
use App\Models\B2bCustomer;
use App\Models\ContactRequest;
use App\Models\ShopOrder;
use App\Models\Booking;
use App\Models\B2bOrder;

class MigrateContactsToCrm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-contacts-to-crm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates data from disparate customer tables to the unified contacts CRM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting migration to unified CRM...");

        // 1. Shop Customers
        $this->info("Migrating Shop Customers...");
        Customer::all()->each(function ($c) {
            $contact = Contact::updateOrCreate(
                ['email' => $c->email],
                [
                    'first_name' => $c->nome,
                    'last_name' => $c->cognome,
                    'company_name' => $c->ragione_sociale,
                    'phone' => $c->telefono,
                    'mobile' => $c->cellulare,
                    'password' => $c->password,
                    'tax_id' => $c->codice_fiscale,
                    'vat_number' => $c->partita_iva,
                    'sdi_code' => $c->sdi,
                    'pec' => $c->pec,
                    'address' => $c->indirizzo,
                    'zip_code' => $c->cap,
                    'city' => $c->citta,
                    'province' => $c->provincia,
                    'country' => $c->nazione,
                    'is_shop_customer' => true,
                    'is_active' => $c->attivo,
                    'tags' => ['Shop'],
                ]
            );

            // Link existing ShopOrders
            ShopOrder::where('customer_id', $c->id)->update(['contact_id' => $contact->id]);
        });

        // 2. Booking Customers
        $this->info("Migrating Booking Customers...");
        BookingCustomer::all()->each(function ($bc) {
            $contact = Contact::where('email', $bc->email)->first();
            if (!$contact) {
                $contact = Contact::create([
                    'first_name' => $bc->nome,
                    'last_name' => $bc->cognome,
                    'email' => $bc->email,
                    'phone' => $bc->telefono,
                    'password' => $bc->password,
                    'city' => $bc->citta,
                    'country' => $bc->nazione ?? 'Italia',
                    'is_booking_customer' => true,
                    'is_active' => $bc->attivo,
                    'tags' => ['Booking'],
                ]);
            } else {
                $currentTags = $contact->tags ?? [];
                if (!in_array('Booking', $currentTags)) {
                    $currentTags[] = 'Booking';
                    $contact->update(['is_booking_customer' => true, 'tags' => $currentTags]);
                }
            }

            // Link existing Bookings
            Booking::where('customer_id', $bc->id)->update(['contact_id' => $contact->id]);
        });

        // 3. B2B Customers
        $this->info("Migrating B2B Customers...");
        B2bCustomer::all()->each(function ($b2b) {
            $contact = null;
            if ($b2b->email) {
                $contact = Contact::where('email', $b2b->email)->first();
            }

            if (!$contact) {
                $contact = Contact::create([
                    'first_name' => $b2b->contact_name,
                    'last_name' => $b2b->contact_surname,
                    'company_name' => $b2b->business_name,
                    'email' => $b2b->email ?? "b2b_" . uniqid() . "@example.com",
                    'vat_number' => $b2b->vat_number,
                    'phone' => $b2b->phone,
                    'is_b2b_customer' => true,
                    'tags' => ['B2B'],
                ]);
            } else {
                $currentTags = $contact->tags ?? [];
                if (!in_array('B2B', $currentTags)) {
                    $currentTags[] = 'B2B';
                    $contact->update([
                        'is_b2b_customer' => true,
                        'vat_number' => $contact->vat_number ?? $b2b->vat_number,
                        'company_name' => $contact->company_name ?? $b2b->business_name,
                        'tags' => $currentTags
                    ]);
                }
            }

            // Link existing B2bOrders
            B2bOrder::where('b2b_customer_id', $b2b->id)->update(['contact_id' => $contact->id]);
        });

        // 4. Contact Requests (Leads)
        $this->info("Migrating Contact Requests (Leads)...");
        ContactRequest::all()->each(function ($cr) {
            $contact = Contact::where('email', $cr->email)->first();
            if ($contact) {
                $currentTags = $contact->tags ?? [];
                if (!in_array('Lead', $currentTags)) {
                    $currentTags[] = 'Lead';
                    $contact->update(['is_lead' => true, 'tags' => $currentTags]);
                }
            } else {
                Contact::create([
                    'first_name' => $cr->nome,
                    'last_name' => $cr->cognome,
                    'company_name' => $cr->ragione_sociale,
                    'email' => $cr->email,
                    'phone' => $cr->telefono,
                    'is_lead' => true,
                    'tags' => ['Lead'],
                ]);
            }
        });

        $this->info("Migration completed successfully!");
    }
}
