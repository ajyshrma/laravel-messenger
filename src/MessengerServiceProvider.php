<?php

namespace Ajyshrma69\LaravelMessenger;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;


class MessengerServiceProvider extends ServiceProvider
{

    /**
     * Register a booted callback to be run after the "boot" method is called.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function boot()
    {
        $this->loadHelperFiles();
        $this->registerBladeDirective();
        $this->registerPublishables();
        $this->loadRoutesFrom(__DIR__ . "\\routes\\web.php");
        $this->loadViewsFrom(__DIR__ . '/resources', 'laravel-messenger');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }


    public function loadHelperFiles()
    {
        include(__DIR__ . '/helpers.php');
    }


    public function registerBladeDirective()
    {
        Blade::directive('messengerCss', function () {
            return '<?php echo messengerCss(); ?>';
        });

        Blade::directive('messengerJs', function () {
            return '<?php echo messengerJs(); ?>';
        });

        Blade::directive('messengerRoutes', function () {
            return '<?php echo messengerRoutes() ?>';
        });
    }

    /**
     * Register Laravel Messenger Publishable Assets
     *
     * @return void
     */
    public function registerPublishables()
    {
        $this->publishes([
            __DIR__ . '/config/messenger.php' => config_path('messenger.php'),
        ], 'messenger-config');

        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'messenger-config');

        $this->publishes([
            __DIR__ . '/resources/assets/css' => public_path('vendor/laravel-messenger/css'),
            __DIR__ . '/resources/assets/js' => public_path('vendor/laravel-messenger/js'),
        ], 'mesenger-assets');
    }
}
