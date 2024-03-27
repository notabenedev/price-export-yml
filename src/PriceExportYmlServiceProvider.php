<?php

namespace Notabenedev\PriceExportYml;

use Illuminate\Support\ServiceProvider;
use Notabenedev\PriceExportYml\Console\Commands\PriceExportYmlMakeCommand;

class PriceExportYmlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/price-export-yml.php', 'price-export-yml'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Публикация конфигурации
        $this->publishes([
            __DIR__.'/config/price-export-yml.php' => config_path('price-export-yml.php')
        ], 'config');

        //Подключаем роуты
        if (config("price-export-yml.siteRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/site/price-export-yml.php");
        }

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                PriceExportYmlMakeCommand::class,
            ]);
        }
    }

}
