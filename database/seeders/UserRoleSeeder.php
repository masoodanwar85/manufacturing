<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryUserRoles = [
            ['userID' => 1,'roleID' => 1]
        ];
        foreach ($aryUserRoles as $userRole) {
            \Illuminate\Support\Facades\DB::table('userRole')->insert(['roleID' => $userRole['roleID'],'userID' => $userRole['userID']]);
        }
    }
}
