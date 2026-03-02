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

        // Blade directive for WebP conversion
        Blade::directive('webp', function ($expression) {
            return "<?php
                \$path = trim($expression, \"'\");
                \$fullPath = public_path(\$path);
                if (File::exists(\$fullPath)) {
                    \$extension = File::extension(\$fullPath);
                    \$webpPath = str_replace('.' . \$extension, '.webp', \$path);
                    \$fullWebpPath = public_path(\$webpPath);

                    if (!File::exists(\$fullWebpPath) || File::lastModified(\$fullPath) > File::lastModified(\$fullWebpPath)) {
                        try {
                            if (\$extension === 'jpg' || \$extension === 'jpeg') {
                                \$image = imagecreatefromjpeg(\$fullPath);
                            } elseif (\$extension === 'png') {
                                \$image = imagecreatefrompng(\$fullPath);
                            }
                            if (isset(\$image)) {
                                imagewebp(\$image, \$fullWebpPath, 80); // 80 quality for compression
                                imagedestroy(\$image);
                            }
                        } catch (\Exception \$e) {
                            // Fallback to original
                        }
                    }
                    echo asset(File::exists(\$fullWebpPath) ? \$webpPath : \$path);
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
