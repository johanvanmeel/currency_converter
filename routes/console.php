<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:fetch-exchange-rates')->dailyAt('06:00');
