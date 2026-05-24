<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
     ->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\NoCache::class);
})
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'etudiant.only' => \App\Http\Middleware\EtudiantOnly::class,
    ]);
})
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'is_enseignant' => \App\Http\Middleware\IsEnseignant::class,
    ]);
})
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'is_superadmin' => \App\Http\Middleware\IsSuperAdmin::class,
    ]);
})
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ]);
})
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'etudiant.only'       => \App\Http\Middleware\EtudiantOnly::class,
        'inscription.ouverte' => \App\Http\Middleware\InscriptionOuverte::class, // ✅ AJOUTÉ
    ]);
})

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
