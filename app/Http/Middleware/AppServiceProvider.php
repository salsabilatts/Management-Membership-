<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Use Bootstrap pagination
        Paginator::useBootstrap();

        // Custom Blade directives
        Blade::directive('currency', function ($expression) {
            return "Rp <?php echo number_format($expression, 0, ',', '.'); ?>";
        });

        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp ' . number_format($expression, 0, ',', '.'); ?>";
        });
    }
}
