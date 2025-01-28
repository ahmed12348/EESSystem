<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsSeeder extends Seeder
{
    public function run()
    {
        $regions = [
            ['name' => 'Maadi', 'city_id' => 1], // Cairo
            ['name' => 'Mokatam', 'city_id' => 1], // Cairo
            ['name' => 'Stanley', 'city_id' => 2], // Alexandria
            ['name' => 'Smouha', 'city_id' => 2], // Alexandria
            ['name' => 'Dokki', 'city_id' => 3], // Giza
            ['name' => 'Mohandessin', 'city_id' => 3], // Giza
        ];

        DB::table('regions')->insert($regions);
    }
}
