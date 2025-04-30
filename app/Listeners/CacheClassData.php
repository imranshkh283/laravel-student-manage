<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cache;
use App\Models\Classess as ClassDivision;

class CacheClassData
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $classes =  ClassDivision::getNameAndDivision() ?? [];

        Cache::put('classes', $classes, 1440);
    }
}
