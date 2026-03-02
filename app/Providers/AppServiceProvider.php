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
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

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
         if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
         }

        if (!app()->runningInConsole()) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('company_infos')) {
                    View::share('companyInfo', CompanyInfo::first());
                }
            } catch (\Exception $e) {
                // Ignore errors during migration or if table doesn't exist yet
            }
        }
        
        Devis::observe(DevisObserver::class);
        Client::observe(ClientObserver::class);
        Product::observe(ProductObserver::class);

        // Optimized Blade directive for WebP
        Blade::directive('webp', function ($expression) {
            return "<?php
                \$path = trim($expression, \"'\");
                \$extension = pathinfo(\$path, PATHINFO_EXTENSION);
                \$webpPath = str_replace('.' . \$extension, '.webp', \$path);
                
                // Only return WebP if it actually exists on disk
                if (\Illuminate\Support\Facades\File::exists(public_path(\$webpPath))) {
                    echo asset(\$webpPath);
                } else {
                    echo asset(\$path);
                }
            ?>";
        });

        Event::listen(function (Login $event) {
            try {
                if ($event->user) {
                    Activity::create([
                        'user_id' => $event->user->id,
                        'description' => 'Connexion au système',
                    ]);
                }
            } catch (\Exception $e) {
                // Prevent login failure if activity logging fails
                \Illuminate\Support\Facades\Log::error('Activity logging failed: ' . $e->getMessage());
            }
        });
    }
}
