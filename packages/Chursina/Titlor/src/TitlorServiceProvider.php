<?php

 namespace Chursina\Titlor;

 use Illuminate\Support\ServiceProvider;

 class TitlorServiceProvider extends ServiceProvider
 {

     public function register()
     {
         $this->mergeConfigFrom(__DIR__ . '/../config/titlor.php', 'titlor');

         $this->app->bind('titlor', function () {
             return new Titlor;
         });
     }

     public function boot()
     {
         $this->publishes([__DIR__ . '/../config/titlor.php' => config_path('titlor.php')], 'config');

         $this->publishes([
             __DIR__ . '/../database/migrations' => $this->app->databasePath() . '/migrations'
         ], 'migrations');

         $this->loadViewsFrom(__DIR__ . '/../resources/views', 'titlor');

         require __DIR__ . '/Http/routes.php';
     }

 }