<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arySalesOrderStatuses = [
            ['salesOrderStatusID' => 1,'salesOrderStatus' => 'Ordered'],
            ['salesOrderStatusID' => 2,'salesOrderStatus' => 'Out for Delivery'],
            ['salesOrderStatusID' => 3,'salesOrderStatus' => 'Delivered']
        ];

        foreach ($arySalesOrderStatuses as $salesOrderStatus) {
            DB::table('salesOrderStatus')->insert([
                'salesOrderStatusID' => $salesOrderStatus['salesOrderStatusID'],
                'salesOrderStatus' => $salesOrderStatus['salesOrderStatus']
            ]);
        }
    }
}
