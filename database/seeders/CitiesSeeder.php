<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            'Cairo', 'Alexandria', 'Giza', 'Shubra El-Kheima', 'Port Said', 'Suez',
            'Luxor', 'Asyut', 'Ismailia', 'Faiyum', 'Zagazig', 'Aswan', 'Damietta',
            'El-Mansoura', 'Tanta', 'Beni Suef', 'Hurghada', 'Minya', 'Qena', 'Sohag',
            'Shibin El Kom', 'Banha', 'Arish', 'Marsa Matruh', 'Kafr El Sheikh',
            'Damanhur', 'Al Kharga'
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert(['name' => $city]);
        }
    }
}

