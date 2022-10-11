<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['blade.compiler']->directive('money', function ($amount) {            
            return "<?php echo 'Rp' . number_format($amount, 0); ?>";
        });
    }
}
