<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        Blade::directive('currency', function ($expression) {
            return "<?php echo 'Rp ' . number_format($expression, 2, ',', '.'); ?>";
        });

        // Atau tambahkan helper function
        if (!function_exists('formatRupiah')) {
            function formatRupiah($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            }

        }

        // set waktu untuk default bahasa indonesia
        Carbon::setLocale('id'); // Set locale ke Indonesia
    }
}
