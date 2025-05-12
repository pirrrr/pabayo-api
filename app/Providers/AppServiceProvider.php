<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

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
        ini_set('default_charset', 'UTF-8');
    mb_internal_encoding("UTF-8");

    $handler = new StreamHandler(storage_path('logs/laravel.log'), \Monolog\Logger::DEBUG);
    $handler->setFormatter(new LineFormatter(null, null, true, true));

    Log::setHandlers([$handler]);

        Relation::morphMap([
        'user' => \App\Models\User::class,
    ]);
    }
}
