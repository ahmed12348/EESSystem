<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $electronics = Category::create(['name' => 'Electronics']);
        $mobiles = Category::create(['name' => 'Mobiles', 'parent_id' => $electronics->id]);
        $laptops = Category::create(['name' => 'Laptops', 'parent_id' => $electronics->id]);

        $fashion = Category::create(['name' => 'Fashion']);
        $men = Category::create(['name' => 'Men', 'parent_id' => $fashion->id]);
        $women = Category::create(['name' => 'Women', 'parent_id' => $fashion->id]);
    }
}

