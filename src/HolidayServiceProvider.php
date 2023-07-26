<?php

namespace Balea\Holiday;


use Balea\Holiday\App\Console\Commands\Holiday;
use Illuminate\Support\ServiceProvider;


class HolidayServiceProvider extends ServiceProvider
{
    protected $commands = [
        Holiday::class
    ];
    
    public function register()
    {

        $this->commands($this->commands);

        $this->mergeConfigFrom(__DIR__ . '/config/errors.php', 'errors');
        
        $this->mergeConfigFrom(__DIR__ . '/config/config_holiday.php', 'Holiday');
    }

    public function boot()
    {
        $this->loadDependencies()
            ->publishDependencies();
    }

    private function loadDependencies()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        return $this;
    }

    private function publishDependencies()
    {

        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('/migrations')
        ], 'holiday-migration');

        $this->publishes([
            __DIR__ . '/config/errors.php' => config_path('errors.php'),
        ], 'holiday-config');
        
        $this->publishes([
            __DIR__ . '/config/config_holiday.php' => config_path('config_holiday.php'),
        ], 'holiday-config');
    }


}
