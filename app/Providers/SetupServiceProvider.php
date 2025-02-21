<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\AppSettings;

class SetupServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        view()->composer('*', function ($view) {
            $view->with('isFirstInstall', AppSettings::isFirstInstall());
        });
    }
}