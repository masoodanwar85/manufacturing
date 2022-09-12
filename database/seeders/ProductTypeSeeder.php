<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryProductTypes = [
            ['productType' => 'Raw Material'],
			['productType' => 'Storable Product'],
			['productType' => 'Service'],
			['productType' => 'Manufactured Product']
        ];
        foreach ($aryProductTypes as $productType) {
            DB::table('productType')->insert([
				'productType' => $productType['productType'],
				'createdByUserID' => 1
			]);
        }
    }
}
