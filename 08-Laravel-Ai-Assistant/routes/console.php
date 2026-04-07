<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('assistant:run-tasks')->everyMinute();
