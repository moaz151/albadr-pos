<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Sale;
use Illuminate\Support\Facades\Storage;

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
        Paginator::useBootstrapFour();

        $modelFiles = Storage::disk('app')->files('Models');
        foreach ($modelFiles as $modelFile) {
            $model = str_replace(['.php', 'Models/'], '', $modelFile); // Sale.php => Sale
            $modelClass = 'App\Models\\' . $model;        // App\Models\Sale
            Relation::enforceMorphMap([
                (string) $model => $modelClass, // Sale => App\Models\Sale
            ]);
        }

        // Relation::enforceMorphMap([
        //     'sale' => Sale::class,
        //     \App\Models\Sale.php
        // ]);
    }
}
