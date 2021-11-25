<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryTransports = [
            ['name' => 'Truck 1', 'owner' => 'Truck 1 Owner','vehicleNumber' => 'ABC-123','description' => 'The best truck'],
            ['name' => 'Truck 2', 'owner' => 'Truck 2 Owner','vehicleNumber' => 'CDA-321','description' => 'The best truck 2'],
            ['name' => 'Truck 3', 'owner' => 'Truck 3 Owner','vehicleNumber' => 'DKD-456','description' => 'The best truck 3'],
            ['name' => 'Truck 4', 'owner' => 'Truck 4 Owner','vehicleNumber' => 'TKD-492','description' => 'The best truck 4'],
			['name' => 'Container', 'owner' => 'Container Owner','vehicleNumber' => 'CON-828','description' => 'The best container']
        ];
        foreach ($aryTransports as $transport) {
			$headID = DB::table('accountHead')->insertGetId([
				'parentHeadID' => \Config::get('constants.account_heads.transport_expense'),
                'rootHeadID' => \Config::get('constants.account_heads.expense'),
				'headName' => $transport['name'] . '-' . $transport['vehicleNumber'] . ' (' . $transport['owner'] . ')' . ' (Transport)',
				'isSystemGenerated' => 1,
				'isEditable' => 0,
				'isShowForPurchaseOrderExpense' => 0,
				'createdByUserID' => 1
			]);
            DB::table('transport')->insert([
				'headID' => $headID,
				'name' => $transport['name'],
				'owner' => $transport['owner'],
				'vehicleNumber' => $transport['vehicleNumber'],
				'description' => $transport['description'],
				'createdByUserID' => 1
			]);
        }
    }
}
