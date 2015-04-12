<?php
namespace GabeMiller\Settings;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

/**
 *
 */

class SettingsServiceProvider extends ServiceProvider {


    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__.'/config/settings.php' => config_path('settings.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/config/settings.php', 'settings'
        );

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('settings', function($app)
        {
            return new Settings(new File);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['settings'];
    }
}