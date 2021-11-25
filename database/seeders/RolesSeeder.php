<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryRoles = [
            ['roleID' => 1, 'roleName' => 'Super Admin', 'description' => 'Role for Super Admin']
        ];
        foreach ($aryRoles as $role) {
            \Illuminate\Support\Facades\DB::table('roles')->insert(['roleID' => $role['roleID'],'roleName' => $role['roleName'],'description' => $role['description']]);
        }
    }
}
