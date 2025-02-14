<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\course_corn_job;

class Kernel_org extends ConsoleKernel
{

    protected $commands = [
        \App\Console\Commands\CertificatesCron::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        foreach (course_corn_job::where([['status','=','1'],['count','>','0']])->get() as $row) {
                  $schedule->command('certificates:cron',[$row->course_id,$row->count,implode(',',$row->languages)])->cron($row->frequency);
        }
        // $schedule->command('inspire')->hourly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
