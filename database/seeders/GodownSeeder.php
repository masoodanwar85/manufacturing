<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GodownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryGodowns = [
            ['name' => 'Godown 1', 'address' => 'House # 123, New City, Los Angeles','description' => 'The best godown'],
            ['name' => 'Godown 2', 'address' => 'House # 433, Old City, LA','description' => 'The best godown 2'],
            ['name' => 'Godown 3', 'address' => 'House # 555, My City, Washington','description' => 'The best godown 3'],
            ['name' => 'Godown 4', 'address' => 'House # 877, Test City, Ames','description' => 'The best godown 4'],
			['name' => 'Godown 5', 'address' => 'House # 347, Face City, IA','description' => 'The best godown 5']
        ];
        foreach ($aryGodowns as $godown) {
			$headID = DB::table('accountHead')->insertGetId([
				'parentHeadID' => \Config::get('constants.account_heads.godown_rent'),
                'rootHeadID' => \Config::get('constants.account_heads.expense'),
				'headName' => $godown['name'] . ' (Godown)',
				'isSystemGenerated' => 1,
				'isEditable' => 0,
				'isShowForPurchaseOrderExpense' => 0,
				'createdByUserID' => 1
			]);
            DB::table('godown')->insert([
				'name' => $godown['name'],
				'headID' => $headID,
				'address' => $godown['address'],
				'description' => $godown['description'],
				'createdByUserID' => 1
			]);
        }
    }
}
