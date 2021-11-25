<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Category::factory(5)->create();
        
        DB::table('category')->insert([
			'categoryName' => 'General',
			'createdByUserID' => 1
		]);
    }
}
