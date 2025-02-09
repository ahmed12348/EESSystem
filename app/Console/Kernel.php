<?php

namespace App\Console;

use App\Jobs\ExpireCartItems;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   

    // protected function schedule(Schedule $schedule)
    // {
    //     $schedule->command('carts:expire')->hourly();  // Run every hour to check for expired carts
    // }
  
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    protected function schedule(Schedule $schedule)
    {
        // $schedule->call(function () {
        //     Cart::where('expires_at', '<=', Carbon::now())
        //         ->where('status', 1) // Only update active carts
        //         ->update(['status' => 0]);
        // })->everyFiveMinutes();

        $schedule->job(new ExpireCartItems)->everyMinute();
        
    }
}
