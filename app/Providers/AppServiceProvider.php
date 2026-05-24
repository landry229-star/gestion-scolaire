<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Matieres;
use App\Models\Enseignants;
use App\Models\Etudiants;
use App\Models\Notes;
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
        // On partage le nombre de matières en attente avec toutes les vues
        View::composer('layouts.app', function ($view) {
            $pendingCount = \App\Models\Matieres::whereHas('notes', function($query) {
                $query->where('statut', 'soumis');
            })->distinct()->count();

            $view->with('pendingNotesCount', $pendingCount);
        });
    }
}
?>
