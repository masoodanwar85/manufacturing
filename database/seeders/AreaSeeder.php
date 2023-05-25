<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryAreas = [
            ['cityID' => 1,'area' => 'Quetta Cantt'],
            ['cityID' => 1,'area' => 'Bazar'],
            ['cityID' => 1,'area' => 'Eastern ByPass Area'],
            ['cityID' => 1,'area' => 'Western ByPass Area']
        ];

        foreach ($aryAreas as $area) {
            DB::table('area')->insert([
				'cityID' => $area['cityID'],
                'area' => $area['area'],
				'createdByUserID' => 1
			]);
        }
    }
}
