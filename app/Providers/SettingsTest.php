<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsTest extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // sharing data with all views 

        $setting = [
            'name'=>'ahmed',
            'age'=>'20'
        ];
        View::share('setting' ,$setting);
        
    }
}
