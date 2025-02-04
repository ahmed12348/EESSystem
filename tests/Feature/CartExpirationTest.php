<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartExpirationTest extends TestCase
{
    use RefreshDatabase;

    // Test 1: Cart expiration set manually by admin
    public function test_admin_can_set_cart_expiration()
    {
        // Create a user and an associated cart
        $user = User::factory()->create();
        $cart = Cart::create([
            'user_id' => $user->id,
            'expires_at' => Carbon::now()->addHour(), // Set an expiration 1 hour from now
        ]);

        // Simulate admin setting the expiration
        $newExpiration = Carbon::now()->addDays(1); // New expiration time, 1 day from now

        // Act as an admin to update the expiration time
        $response = $this->actingAsAdmin()->put(route('admin.users.update', $cart->id), [
            'expires_at' => $newExpiration,
        ]);

        // Check if the expiration date is updated successfully
        $cart->refresh(); // Refresh to get the latest cart data from the database
        $this->assertEquals($newExpiration->toDateTimeString(), $cart->expires_at->toDateTimeString());
        $response->assertRedirect(route('admin.users.index'));
    }

    // Test 2: Check if cart is expired
    public function test_cart_is_expired()
    {
        // Create a user and cart with an expiration time 1 hour ago
        $user = User::factory()->create();
        $cart = Cart::create([
            'user_id' => $user->id,
            'expires_at' => Carbon::now()->subHour(), // Set an expiration 1 hour ago
        ]);

        // Check if the cart is expired
        $this->assertTrue($cart->isExpired());
    }

    // Test 3: Check if cart is NOT expired
    public function test_cart_is_not_expired()
    {
        // Create a user and cart with an expiration time 1 hour from now
        $user = User::factory()->create();
        $cart = Cart::create([
            'user_id' => $user->id,
            'expires_at' => Carbon::now()->addHour(), // Set an expiration 1 hour from now
        ]);

        // Check if the cart is NOT expired
        $this->assertFalse($cart->isExpired());
    }

    // Test 4: User cannot checkout if cart is expired
    public function test_user_cannot_checkout_if_cart_is_expired()
    {
        // Create a user and an expired cart
        $user = User::factory()->create();
        $cart = Cart::create([
            'user_id' => $user->id,
            'expires_at' => Carbon::now()->subHour(), // Set an expiration 1 hour ago
        ]);

        // Simulate the user trying to checkout
        $response = $this->actingAs($user)->post(route('cart.checkout'));

        // Ensure they cannot proceed due to expiration
        $response->assertSessionHas('error', 'Cart has expired. Please add items again.');
        $response->assertRedirect(route('admin.users.index'));
    }

    // Test 5: Admin can see the expiration date in the dashboard
    public function test_admin_can_see_cart_expiration_on_dashboard()
    {
        // Create a user and a cart with an expiration time
        $user = User::factory()->create();
        $cart = Cart::create([
            'user_id' => $user->id,
            'expires_at' => Carbon::now()->addDays(1), // Set expiration for 1 day from now
        ]);

        // Admin visits the cart detail page
        $response = $this->actingAsAdmin()->get(route('admin.users.show', $cart->id));

        // Check if the expiration date is displayed
        $response->assertSee($cart->expires_at->toDateTimeString());
    }
}
