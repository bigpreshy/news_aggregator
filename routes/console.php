<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     /** @var ClosureCommand $this */
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');


// Schedule::call(function () {
//     Artisan::call('app:fetch-articles');
// })->everyMinute();


app()->booted(function () {
    $schedule = app(Schedule::class);

    $schedule->command('app:fetch-articles')->everyMinute();
});