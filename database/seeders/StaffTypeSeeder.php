<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StaffTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryStaffTypes = [
            ['staffType' => 'Employee'],
			['staffType' => 'Manager'],
			['staffType' => 'Accountant'],
			['staffType' => 'Sales Agent'],
			['staffType' => 'Purchase Agent']
        ];
        foreach ($aryStaffTypes as $staffType) {
            \Illuminate\Support\Facades\DB::table('staffType')->insert([
				'staffType' => $staffType['staffType'],
				'createdByUserID' => 1
			]);
        }
    }
}
