<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            // Se le impostazioni esistono nel filesystem o db, carichiamo la conf mail
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
                \Illuminate\Support\Facades\View::share('global_settings', $settings);

                if (!empty($settings['mail_host'])) {
                    config([
                        'mail.mailers.smtp.host'       => $settings['mail_host'] ?? config('mail.mailers.smtp.host'),
                        'mail.mailers.smtp.port'       => $settings['mail_port'] ?? config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.encryption' => !empty($settings['mail_encryption']) ? $settings['mail_encryption'] : null,
                        'mail.mailers.smtp.username'   => $settings['mail_username'] ?? config('mail.mailers.smtp.username'),
                        'mail.mailers.smtp.password'   => $settings['mail_password'] ?? config('mail.mailers.smtp.password'),
                        'mail.from.address'            => $settings['mail_from_address'] ?? config('mail.from.address'),
                        'mail.from.name'               => $settings['mail_from_name'] ?? config('mail.from.name'),
                    ]);
                    
                    if (!empty($settings['mail_mailer'])) {
                        config(['mail.default' => $settings['mail_mailer']]);
                    }
                    
                    // Forza il ricaricamento del mailer con la nuova configurazione
                    \Illuminate\Support\Facades\Mail::purge();
                }
            }
        } catch (\Exception $e) {
            // Ignora errori di boot se ad esempio il DB non è ancora migrato
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('sections')) {
                \Illuminate\Support\Facades\View::composer(['public.partials.header', 'public.partials.footer'], function ($view) {
                    $view->with('shared_sezioni', \App\Models\Section::where('visibile', true)->orderBy('ordine')->get());
                });
            }
        } catch (\Exception $e) {
            // Ignora
        }
    }
}
