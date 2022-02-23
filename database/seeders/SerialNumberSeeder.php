<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SerialNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arySerialNumbers = [
            ['english' => 'one','urdu' => null],
            ['english' => 'two','urdu' => null],
            ['english' => 'three','urdu' => null],
            ['english' => 'four','urdu' => null],
            ['english' => 'five','urdu' => null],
            ['english' => 'six','urdu' => null],
            ['english' => 'seven','urdu' => null],
            ['english' => 'eight','urdu' => null],
            ['english' => 'nine','urdu' => null],
            ['english' => 'ten','urdu' => null],
            ['english' => 'eleven','urdu' => null],
            ['english' => 'twelve','urdu' => null],
            ['english' => 'thirteen','urdu' => null],
            ['english' => 'fourteen','urdu' => null],
            ['english' => 'fifteen','urdu' => null],
            ['english' => 'sixteen','urdu' => null],
            ['english' => 'seventeen','urdu' => null],
            ['english' => 'eighteen','urdu' => null],
            ['english' => 'nineteen','urdu' => null],
            ['english' => 'twenty','urdu' => null],
            ['english' => 'twenty one','urdu' => null],
            ['english' => 'twenty two','urdu' => null],
            ['english' => 'twenty three','urdu' => null],
            ['english' => 'twenty four','urdu' => null],
            ['english' => 'twenty five','urdu' => null],
            ['english' => 'twenty six','urdu' => null],
            ['english' => 'twenty seven','urdu' => null],
            ['english' => 'twenty eight','urdu' => null],
            ['english' => 'twenty nine','urdu' => null],
            ['english' => 'thirty','urdu' => null],
            ['english' => 'thirty one','urdu' => null],
            ['english' => 'thirty two','urdu' => null],
            ['english' => 'thirty three','urdu' => null],
            ['english' => 'thirty four','urdu' => null],
            ['english' => 'thirty five','urdu' => null],
            ['english' => 'thirty six','urdu' => null],
            ['english' => 'thirty seven','urdu' => null],
            ['english' => 'thirty eight','urdu' => null],
            ['english' => 'thirty nine','urdu' => null],
            ['english' => 'forty','urdu' => null],
            ['english' => 'forty one','urdu' => null],
            ['english' => 'forty two','urdu' => null],
            ['english' => 'forty three','urdu' => null],
            ['english' => 'forty four','urdu' => null],
            ['english' => 'forty five','urdu' => null],
            ['english' => 'forty six','urdu' => null],
            ['english' => 'forty seven','urdu' => null],
            ['english' => 'forty eight','urdu' => null],
            ['english' => 'forty nine','urdu' => null],
            ['english' => 'fifty','urdu' => null],
            ['english' => 'fifty one','urdu' => null],
            ['english' => 'fifty two','urdu' => null],
            ['english' => 'fifty three','urdu' => null],
            ['english' => 'fifty four','urdu' => null],
            ['english' => 'fifty five','urdu' => null],
            ['english' => 'fifty six','urdu' => null],
            ['english' => 'fifty seven','urdu' => null],
            ['english' => 'fifty eight','urdu' => null],
            ['english' => 'fifty nine','urdu' => null],
            ['english' => 'sixty','urdu' => null],
            ['english' => 'sixty one','urdu' => null],
            ['english' => 'sixty two','urdu' => null],
            ['english' => 'sixty three','urdu' => null],
            ['english' => 'sixty four','urdu' => null],
            ['english' => 'sixty five','urdu' => null],
            ['english' => 'sixty six','urdu' => null],
            ['english' => 'sixty seven','urdu' => null],
            ['english' => 'sixty eight','urdu' => null],
            ['english' => 'sixty nine','urdu' => null],
            ['english' => 'seventy','urdu' => null],
            ['english' => 'seventy one','urdu' => null],
            ['english' => 'seventy two','urdu' => null],
            ['english' => 'seventy three','urdu' => null],
            ['english' => 'seventy four','urdu' => null],
            ['english' => 'seventy five','urdu' => null],
            ['english' => 'seventy six','urdu' => null],
            ['english' => 'seventy seven','urdu' => null],
            ['english' => 'seventy eight','urdu' => null],
            ['english' => 'seventy nine','urdu' => null],
            ['english' => 'eighty','urdu' => null],
            ['english' => 'eighty one','urdu' => null],
            ['english' => 'eighty two','urdu' => null],
            ['english' => 'eighty three','urdu' => null],
            ['english' => 'eighty four','urdu' => null],
            ['english' => 'eighty five','urdu' => null],
            ['english' => 'eighty six','urdu' => null],
            ['english' => 'eighty seven','urdu' => null],
            ['english' => 'eighty eight','urdu' => null],
            ['english' => 'eighty nine','urdu' => null],
            ['english' => 'ninety','urdu' => null],
            ['english' => 'ninety one','urdu' => null],
            ['english' => 'ninety two','urdu' => null],
            ['english' => 'ninety three','urdu' => null],
            ['english' => 'ninety four','urdu' => null],
            ['english' => 'ninety five','urdu' => null],
            ['english' => 'ninety six','urdu' => null],
            ['english' => 'ninety seven','urdu' => null],
            ['english' => 'ninety eight','urdu' => null],
            ['english' => 'ninety nine','urdu' => null],
            ['english' => 'Hundred','urdu' => null]
        ];
        foreach ($arySerialNumbers as $serialNumber) {
            DB::table('serialNumber')->insert(
                [
                    'english' => Str::title($serialNumber['english']),
                    'urdu' => $serialNumber['urdu']
                ]
            );
        }

        for ($ctr = 101; $ctr <= 10000; $ctr++) {
            DB::table('serialNumber')->insert(['english' => null,'urdu' => null]);
        }
    }
}
