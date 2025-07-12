<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cron:update-ladder')
    ->dailyAt('9:' . str_pad((crc32(config('app.key') . date('Y-m-d')) % 60), 2, '0', STR_PAD_LEFT))
    ->withoutOverlapping();

