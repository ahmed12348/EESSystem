<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Fetch random vendor users (assuming vendors have role 'vendor')
        $vendors = User::whereHas('roles', function($query) {
            $query->where('name', 'vendor');
        })->pluck('id')->toArray();

        if (empty($vendors)) {
            $this->command->warn('No vendors found. Make sure you seed vendors first!');
            return;
        }

        // Create sample products
        Product::factory()->count(10)->create([
            'vendor_id' => $vendors[array_rand($vendors)] // Assign products to random vendors
        ]);
    }
}

