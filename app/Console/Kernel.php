<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
         \App\Console\Commands\SendFuncEmail::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // php /home/fadamedia/baldatayiba.com/artisan schedule:run > /dev/null 2>&1
        $schedule->command('send:func_email')->everyMinute(); // ->appendOutputTo('\logs\schedule.log');;
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
