<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 5, 500),
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? 1, // Assign random category
            'vendor_id' => \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'vendor'))->inRandomOrder()->first()?->id ?? 1, 
        ];
    }
}

