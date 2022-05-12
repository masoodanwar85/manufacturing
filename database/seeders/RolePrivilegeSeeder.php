<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolePrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryRolePrivileges = [
            ['roleID' => 1,'privilegeID' => 1],
            ['roleID' => 1,'privilegeID' => 2],
            ['roleID' => 1,'privilegeID' => 3],
            ['roleID' => 1,'privilegeID' => 4],
            ['roleID' => 1,'privilegeID' => 5],
            ['roleID' => 1,'privilegeID' => 6],
            ['roleID' => 1,'privilegeID' => 7],
            ['roleID' => 1,'privilegeID' => 8],
			['roleID' => 1,'privilegeID' => 9],
            ['roleID' => 1,'privilegeID' => 10],
            ['roleID' => 1,'privilegeID' => 11],
            ['roleID' => 1,'privilegeID' => 12],
            ['roleID' => 1,'privilegeID' => 13],
            ['roleID' => 1,'privilegeID' => 14],
            ['roleID' => 1,'privilegeID' => 15],
            ['roleID' => 1,'privilegeID' => 16],
			['roleID' => 1,'privilegeID' => 17],
            ['roleID' => 1,'privilegeID' => 18],
            ['roleID' => 1,'privilegeID' => 19],
            ['roleID' => 1,'privilegeID' => 20],
			['roleID' => 1,'privilegeID' => 21],
            ['roleID' => 1,'privilegeID' => 22],
            ['roleID' => 1,'privilegeID' => 23],
            ['roleID' => 1,'privilegeID' => 24],
			['roleID' => 1,'privilegeID' => 25],
            ['roleID' => 1,'privilegeID' => 26],
            ['roleID' => 1,'privilegeID' => 27],
            ['roleID' => 1,'privilegeID' => 28],
			['roleID' => 1,'privilegeID' => 29],
            ['roleID' => 1,'privilegeID' => 30],
            ['roleID' => 1,'privilegeID' => 31],
            ['roleID' => 1,'privilegeID' => 32],
			['roleID' => 1,'privilegeID' => 33],
            ['roleID' => 1,'privilegeID' => 34],
            ['roleID' => 1,'privilegeID' => 35],
            ['roleID' => 1,'privilegeID' => 36],
			['roleID' => 1,'privilegeID' => 37],
            ['roleID' => 1,'privilegeID' => 38],
            ['roleID' => 1,'privilegeID' => 39],
            ['roleID' => 1,'privilegeID' => 40],
			['roleID' => 1,'privilegeID' => 41],
            ['roleID' => 1,'privilegeID' => 42],
            ['roleID' => 1,'privilegeID' => 43],
            ['roleID' => 1,'privilegeID' => 44],
			['roleID' => 1,'privilegeID' => 45],
            ['roleID' => 1,'privilegeID' => 46],
            ['roleID' => 1,'privilegeID' => 47],
            ['roleID' => 1,'privilegeID' => 48],
			['roleID' => 1,'privilegeID' => 49],
            ['roleID' => 1,'privilegeID' => 50],
			['roleID' => 1,'privilegeID' => 51],
            ['roleID' => 1,'privilegeID' => 52],
            ['roleID' => 1,'privilegeID' => 53],
            ['roleID' => 1,'privilegeID' => 54],
			['roleID' => 1,'privilegeID' => 55],
			['roleID' => 1,'privilegeID' => 56],
			['roleID' => 1,'privilegeID' => 57],
            ['roleID' => 1,'privilegeID' => 58],
			['roleID' => 1,'privilegeID' => 59],
			['roleID' => 1,'privilegeID' => 60],
			['roleID' => 1,'privilegeID' => 61],
            ['roleID' => 1,'privilegeID' => 62],
			['roleID' => 1,'privilegeID' => 63],
			['roleID' => 1,'privilegeID' => 64],
			['roleID' => 1,'privilegeID' => 65],
            ['roleID' => 1,'privilegeID' => 66],
			['roleID' => 1,'privilegeID' => 67],
            ['roleID' => 1,'privilegeID' => 68],
			['roleID' => 1,'privilegeID' => 69],
            ['roleID' => 1,'privilegeID' => 70],
			['roleID' => 1,'privilegeID' => 71],
            ['roleID' => 1,'privilegeID' => 72],
			['roleID' => 1,'privilegeID' => 73],
            ['roleID' => 1,'privilegeID' => 74],
			['roleID' => 1,'privilegeID' => 75],
            ['roleID' => 1,'privilegeID' => 76],
			['roleID' => 1,'privilegeID' => 77],
            ['roleID' => 1,'privilegeID' => 78],
			['roleID' => 1,'privilegeID' => 79]
        ];
        foreach ($aryRolePrivileges as $rolePrivilege) {
            \Illuminate\Support\Facades\DB::table('rolePrivilege')->insert(
				[
					'roleID' => $rolePrivilege['roleID'],
					'privilegeID' => $rolePrivilege['privilegeID']
				]
			);
        }
    }
}
