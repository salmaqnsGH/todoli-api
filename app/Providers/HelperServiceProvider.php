<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Load all helper files from the Helpers directory
        foreach (glob(app_path('Helpers/*.php')) as $file) {
            require_once $file;
        }
    }
}
