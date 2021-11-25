<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccessLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryAccessLevels = [
            ['accessLevelID' => 1, 'accessLevel' => 'Create'],
            ['accessLevelID' => 2, 'accessLevel' => 'Read'],
            ['accessLevelID' => 3, 'accessLevel' => 'Update'],
            ['accessLevelID' => 4, 'accessLevel' => 'Delete']
        ];
        foreach ($aryAccessLevels as $accessLevel) {
            \Illuminate\Support\Facades\DB::table('accessLevel')->insert(['accessLevel' => $accessLevel['accessLevel'],'accessLevelID' => $accessLevel['accessLevelID']]);
        }
    }
}
