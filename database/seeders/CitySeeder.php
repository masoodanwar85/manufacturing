<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryCities = [
            ['cityName' => 'Quetta'],
            ['cityName' => 'Khuzdar']
        ];

        foreach ($aryCities as $city) {
            DB::table('city')->insert([
				'cityName' => $city['cityName'],
				'createdByUserID' => 1
			]);
        }
    }
}
