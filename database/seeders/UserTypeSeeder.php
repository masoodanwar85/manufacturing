<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryUserTypes = [
            ['userTypeID' => 1, 'userType' => 'Super Admin'],
            ['userTypeID' => 2, 'userType' => 'App Admin'],
            ['userTypeID' => 3, 'userType' => 'Admin'],
            ['userTypeID' => 4, 'userType' => 'Employees']
        ];
        foreach ($aryUserTypes as $userType) {
            \Illuminate\Support\Facades\DB::table('userType')->insert(['userType' => $userType['userType'],'userTypeID' => $userType['userTypeID']]);
        }
    }
}
