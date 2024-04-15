<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         // Publier le fichier de configuration
         $this->publishes([
            __DIR__.'/../config/personalized-marketing.php' => config_path('personalized-marketing.php'),
        ], 'config');

        // Publier les vues
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/personalized-marketing'),
        ], 'views');

        // Publier les assets (CSS, JavaScript, images, etc.)
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/personalized-marketing'),
        ], 'personalized-marketing-assets');

        // Publier les fichiers de langage (traductions)
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/personalized-marketing'),
        ], 'personalized-marketing-lang');

        // Publier les migrations
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'migrations');
        }

        // Charger les migrations du package
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publier les seeds (données de base)
        $this->publishes([
            __DIR__.'/../database/seeds' => database_path('seeds'),
        ], 'personalized-marketing-seeds');

        // Publier les services
        $this->publishes([
            __DIR__.'/../Services' => app_path('Services'),
        ], 'personalized-marketing-services');

        // Publier les repositories
        $this->publishes([
            __DIR__.'/../Repositories' => app_path('Repositories'),
        ], 'personalized-marketing-repositories');

        // Publier les contrôleurs
        $this->publishes([
            __DIR__.'/../Controllers' => app_path('Http/Controllers'),
        ], 'personalized-marketing-controllers');

        // Publier les middleware
        $this->publishes([
            __DIR__.'/../Middleware' => app_path('Http/Middleware'),
        ], 'personalized-marketing-middleware');
    }
}
