<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryModules = [
            ['moduleID' => 1, 'moduleCode' => 'USER', 'moduleName' => 'Users Module'],
            ['moduleID' => 2, 'moduleCode' => 'ROLES','moduleName' => 'Roles Module'],
			['moduleID' => 3, 'moduleCode' => 'PRODUCT','moduleName' => 'Products Module'],
			['moduleID' => 4, 'moduleCode' => 'PRODUCT_CATEGORY','moduleName' => 'Product Category Module'],
			['moduleID' => 5, 'moduleCode' => 'SUPPLIER','moduleName' => 'Supplier Module'],
			['moduleID' => 6, 'moduleCode' => 'BATCH','moduleName' => 'Batch Module'],
			['moduleID' => 7, 'moduleCode' => 'PURCHASE_ORDER','moduleName' => 'Purchase Order Module'],
			['moduleID' => 8, 'moduleCode' => 'ACCOUNT_HEAD','moduleName' => 'Account Heads Module'],
			['moduleID' => 9, 'moduleCode' => 'BANK_ACCOUNT','moduleName' => 'Bank Accounts Module'],
			['moduleID' => 10, 'moduleCode' => 'GODOWN','moduleName' => 'Godowns Module'],
			['moduleID' => 11, 'moduleCode' => 'TRANSPORT','moduleName' => 'Transport Module'],
			['moduleID' => 12, 'moduleCode' => 'STAFF','moduleName' => 'Staff Module'],
			['moduleID' => 13, 'moduleCode' => 'STOCK','moduleName' => 'Stock Module'],
			['moduleID' => 14, 'moduleCode' => 'SALES','moduleName' => 'Sales Order Module'],
			['moduleID' => 15, 'moduleCode' => 'CUSTOMER','moduleName' => 'Customers Module'],
			['moduleID' => 16, 'moduleCode' => 'TRANSACTION','moduleName' => 'Transactions Module'],
			['moduleID' => 17, 'moduleCode' => 'REPORT','moduleName' => 'Reports Module'],
			['moduleID' => 18, 'moduleCode' => 'SETTING','moduleName' => 'Settings Module'],
            ['moduleID' => 19, 'moduleCode' => 'INVOICE_BOOKS','moduleName' => 'Invoice Books Module']
        ];
        foreach ($aryModules as $module) {
            \Illuminate\Support\Facades\DB::table('modules')->insert(['moduleCode' => $module['moduleCode'],'moduleName' => $module['moduleName'],'moduleID' => $module['moduleID']]);
        }
    }
}
