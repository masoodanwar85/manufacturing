<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MeasurementUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryUnits = [
            ['unitID' => 1,'unitName' => 'Default', 'symbol' => 'Qty','unitType' => 'Item'],
            ['unitID' => 2,'unitName' => 'Kilogram', 'symbol' => 'Kg','unitType' => 'mass'],
            ['unitID' => 3,'unitName' => 'Gram', 'symbol' => 'g','unitType' => 'mass'],
            ['unitID' => 4,'unitName' => 'MilliGram', 'symbol' => 'mg','unitType' => 'mass'],
            ['unitID' => 5,'unitName' => 'Meter', 'symbol' => 'm','unitType' => 'length'],
            ['unitID' => 6,'unitName' => 'Yard', 'symbol' => 'y','unitType' => 'length'],
            ['unitID' => 7,'unitName' => 'Millimeter', 'symbol' => 'mm','unitType' => 'length'],
            ['unitID' => 8,'unitName' => 'Centimeter', 'symbol' => 'cm','unitType' => 'length'],
            ['unitID' => 9,'unitName' => 'Liter', 'symbol' => 'l','unitType' => 'volume'],
            ['unitID' => 10,'unitName' => 'MilliLiter', 'symbol' => 'ml','unitType' => 'volume']
        ];
        foreach ($aryUnits as $unit) {
            DB::table('measurementUnit')->insert(['unitID' => $unit['unitID'],'unitName' => $unit['unitName'],'symbol' => $unit['symbol'],'unitType' => $unit['unitType']]);
        }
    }
}
