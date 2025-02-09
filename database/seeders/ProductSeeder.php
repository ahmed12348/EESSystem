<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Fetch vendor users
        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->pluck('id')->toArray();

        if (empty($vendors)) {
            $this->command->warn('No vendors found. Make sure you seed vendors first!');
            return;
        }

        // Create sample products and distribute among vendors
        Product::factory(10)->create()->each(function ($product) use ($vendors) {
            $product->update([
                'vendor_id' => $vendors[array_rand($vendors)], // Random vendor assignment
            ]);
        });
    }
}

