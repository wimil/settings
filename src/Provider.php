<?php

namespace Wimil\Settings;

use Illuminate\Support\ServiceProvider;
use Wimil\Settings\Settings;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $timestamp = date('Y_m_d_His', time());
        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('settings.php'),
            __DIR__ . '/../migrations/create_settings_table.php' => database_path("migrations/{$timestamp}_create_settings_table.php")
        ]);

        $this->app->singleton('settings', function () {
            return new Settings();
        });

        $this->app->settings->sync();
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/settings.php',
            'settings'
        );
    }
}
