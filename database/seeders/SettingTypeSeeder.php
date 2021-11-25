<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arySettingTypes = [
            ['settingTypeCode' => 'client', 'settingType' => 'Client'],
			['settingTypeCode' => 'user', 'settingType' => 'User']
        ];
        foreach ($arySettingTypes as $settingType) {
            \Illuminate\Support\Facades\DB::table('settingType')->insert([
				'settingTypeCode' => $settingType['settingTypeCode'],
				'settingType' => $settingType['settingType']
			]);
        }
    }
}
