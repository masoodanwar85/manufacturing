<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

	public function run()
    {
        $aryCustomers = [
            ['customerName' => 'Customer 1', 'shopName' => 'Customer 1 Enterprises', 'phone' => '123456789', 'address' => 'House # 123, New City, Los Angeles','description' => 'The best customer'],
            ['customerName' => 'Customer 2', 'shopName' => 'Customer 2 Enterprises', 'phone' => '764389756', 'address' => 'House # 433, Old City, LA','description' => 'The best customer 2'],
            ['customerName' => 'Customer 3', 'shopName' => 'Customer 3 Enterprises', 'phone' => '456942778', 'address' => 'House # 555, My City, Washington','description' => 'The best customer 3'],
            ['customerName' => 'Customer 4', 'shopName' => 'Customer 4 Enterprises', 'phone' => '686876769', 'address' => 'House # 877, Test City, Ames','description' => 'The best customer 4'],
			['customerName' => 'Customer 5', 'shopName' => 'Customer 5 Enterprises', 'phone' => '456788758', 'address' => 'House # 347, Face City, IA','description' => 'The best customer 5']
        ];

        foreach ($aryCustomers as $customer) {
			$headID = DB::table('accountHead')->insertGetId([
				'parentHeadID' => \Config::get('constants.account_heads.customer'),
				'rootHeadID' => \Config::get('constants.account_heads.customer'),
				'headName' => $customer['customerName'] . ' (' . $customer['shopName'] . ')' . ' (Customer)',
				'isSystemGenerated' => 1,
				'isEditable' => 0,
				'isShowForPurchaseOrderExpense' => 0,
				'createdByUserID' => 1
			]);

			DB::table('customer')->insert([
				'customerName' => $customer['customerName'],
				'headID' => $headID,
				'shopName' => $customer['shopName'],
				'phone' => $customer['phone'],
				'address' => $customer['address'],
				'description' => $customer['description'],
				'createdByUserID' => 1
			]);
        }
    }
}
