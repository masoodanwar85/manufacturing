<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MeasurementUnitConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryUnitConversions = [
            ['fromUnitID' => 2,'toUnitID' => 3, 'multiplyBy' => 1000],
            ['fromUnitID' => 2,'toUnitID' => 4, 'multiplyBy' => 1000000],
			['fromUnitID' => 3,'toUnitID' => 4, 'multiplyBy' => 1000],
        ];
        foreach ($aryUnitConversions as $aryUnitConversion) {
            DB::table('measurementUnitConversion')->insert(['fromUnitID' => $aryUnitConversion['fromUnitID'],'toUnitID' => $aryUnitConversion['toUnitID'],'multiplyBy' => $aryUnitConversion['multiplyBy']]);
        }
    }
}
