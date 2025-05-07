<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('App\Student\StudentServiceInterface', 'App\Student\Services\StudentService');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Collection::macro('getGrade', function ($level, $mark) {
            $grades = collect(Config::get("grade.$level", []))
                ->sortKeysDesc();

            return $grades->first(function ($value, $key) use ($mark) {
                return $mark >= (int) $key;
            });
        });
    }
}
