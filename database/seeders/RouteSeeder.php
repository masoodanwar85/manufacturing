<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryRoutes = [
            ['areaID' => 1,'route' => 'Cantt'],
            ['areaID' => 1,'route' => 'Shahbaz Town'],
            ['areaID' => 2,'route' => 'Liaquat Bazar'],
            ['areaID' => 2,'route' => 'Prince Road'],
            ['areaID' => 3,'route' => 'Satellite Town'],
            ['areaID' => 4,'route' => 'Spinni Road'],
            ['areaID' => 4,'route' => 'Brewery Road'],
            ['areaID' => 4,'route' => 'Khezi Road']
        ];

        foreach ($aryRoutes as $route) {
            DB::table('route')->insert([
				'areaID' => $route['areaID'],
                'route' => $route['route'],
				'createdByUserID' => 1
			]);
        }
    }
}
