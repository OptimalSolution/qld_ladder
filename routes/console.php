<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cron:update-ladder')
    ->daily()
    ->between('4:00', '6:00')
    ->when(fn () => now()->minute === rand(0, 119))
    ->withoutOverlapping();

