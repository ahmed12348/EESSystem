<?php

namespace App\Jobs;

use App\Models\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CartItem;
use Carbon\Carbon;

class ExpireCartItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cart;

  
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function handle()
    {
        // Check if the cart is expired
        if ($this->cart->expires_at <= Carbon::now()) {
            // $this->cart->items()->delete();
           
            $this->cart->items()->update(['status' => 'expired']);

        }
    }
}

