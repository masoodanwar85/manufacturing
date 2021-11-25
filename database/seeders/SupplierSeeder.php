<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arySuppliers = [
            ['supplierName' => 'Supplier 1', 'phone' => '123456789', 'address' => 'House # 123, New City, Los Angeles','description' => 'The best supplier'],
            ['supplierName' => 'Supplier 2', 'phone' => '764389756', 'address' => 'House # 433, Old City, LA','description' => 'The best supplier 2'],
            ['supplierName' => 'Supplier 3', 'phone' => '456942778', 'address' => 'House # 555, My City, Washington','description' => 'The best supplier 3'],
            ['supplierName' => 'Supplier 4', 'phone' => '686876769', 'address' => 'House # 877, Test City, Ames','description' => 'The best supplier 4'],
			['supplierName' => 'Supplier 5', 'phone' => '456788758', 'address' => 'House # 347, Face City, IA','description' => 'The best supplier 5']
        ];

        foreach ($arySuppliers as $supplier) {
			$headID = DB::table('accountHead')->insertGetId([
				'parentHeadID' => \Config::get('constants.account_heads.supplier'),
                'rootHeadID' => \Config::get('constants.account_heads.supplier'),
				'headName' => $supplier['supplierName'] . ' (Supplier)',
				'isSystemGenerated' => 1,
				'isEditable' => 0,
				'isShowForPurchaseOrderExpense' => 0,
				'createdByUserID' => 1
			]);

            DB::table('supplier')->insert([
				'supplierName' => $supplier['supplierName'],
				'headID' => $headID,
				'phone' => $supplier['phone'],
				'address' => $supplier['address'],
				'description' => $supplier['description'],
				'createdByUserID' => 1
			]);
        }
    }
}
