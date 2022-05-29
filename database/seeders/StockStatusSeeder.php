<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StockStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryStockStatus = [
            ['status' => 'Quetta Godown', 'isAvailableForSale' => 1],
			['status' => 'Good Sales Return', 'isAvailableForSale' => 1],
			['status' => 'Sold', 'isAvailableForSale' => 0],
			['status' => 'Bad Sales Return', 'isAvailableForSale' => 0],
			['status' => 'Damaged', 'isAvailableForSale' => 0],
            ['status' => 'Manufacturing', 'isAvailableForSale' => 0]
        ];
        foreach ($aryStockStatus as $stockStatus) {
            \Illuminate\Support\Facades\DB::table('stockStatus')->insert([
				'status' => $stockStatus['status'],
				'isAvailableForSale' => $stockStatus['isAvailableForSale']
			]);
        }
    }
}
