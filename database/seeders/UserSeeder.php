<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'name' => 'Masood Anwar',
            'email' => 'masoodanwar85@gmail.com',
            'userTypeID' => 1,
            'statusID' => 1,
			'clientID' => 1,
            'password' => \Illuminate\Support\Facades\Hash::make('masoodanwar85'),
        ]);
    }
}
