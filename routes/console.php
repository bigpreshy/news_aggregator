<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;



app()->booted(function () {
    $schedule = app(Schedule::class);

    $schedule->command('app:fetch-articles')->hourly();
});
