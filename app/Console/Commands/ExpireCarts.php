<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use Carbon\Carbon;

class ExpireCarts extends Command
{
    protected $signature = 'carts:expire';
    protected $description = 'Expire carts that have passed their expiration time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $carts = Cart::where('status', 1)  // Only active carts
                     ->where('expires_at', '<=', Carbon::now())
                     ->get();

        foreach ($carts as $cart) {
            $cart->update(['status' => 0]);  // Expire the cart
            $this->info("Cart ID {$cart->id} has been expired.");
        }
    }
}
