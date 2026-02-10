<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CompanyInfo;
use App\Models\Devis;
use App\Models\Client;
use App\Models\Product;
use App\Observers\DevisObserver;
use App\Observers\ClientObserver;
use App\Observers\ProductObserver;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

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
        View::share('companyInfo', CompanyInfo::first());
        
        Devis::observe(DevisObserver::class);
        Client::observe(ClientObserver::class);
        Product::observe(ProductObserver::class);

        Event::listen(function (Login $event) {
            if ($event->user) {
                Activity::create([
                    'user_id' => $event->user->id,
                    'description' => 'Connexion au système',
                ]);
            }
        });
    }
}
