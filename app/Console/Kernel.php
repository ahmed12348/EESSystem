<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('carts:expire')->hourly();  // Run every hour to check for expired carts
    }
  
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
