<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryPrivileges = [
            ['moduleID' => 1, 'accessLevelID' => 1, 'privilegeCode' => 'USER', 'privilegeName' => 'Users Create'],
			['moduleID' => 1, 'accessLevelID' => 2, 'privilegeCode' => 'USER', 'privilegeName' => 'Users Read'],
			['moduleID' => 1, 'accessLevelID' => 3, 'privilegeCode' => 'USER', 'privilegeName' => 'Users Update'],
			['moduleID' => 1, 'accessLevelID' => 4, 'privilegeCode' => 'USER', 'privilegeName' => 'Users Delete'],
            ['moduleID' => 2, 'accessLevelID' => 1, 'privilegeCode' => 'ROLES','privilegeName' => 'Roles Create'],
			['moduleID' => 2, 'accessLevelID' => 2, 'privilegeCode' => 'ROLES','privilegeName' => 'Roles Read'],
			['moduleID' => 2, 'accessLevelID' => 3, 'privilegeCode' => 'ROLES','privilegeName' => 'Roles Update'],
			['moduleID' => 2, 'accessLevelID' => 4, 'privilegeCode' => 'ROLES','privilegeName' => 'Roles Delete'],
			['moduleID' => 3, 'accessLevelID' => 1, 'privilegeCode' => 'PRODUCT', 'privilegeName' => 'Products Create'],
			['moduleID' => 3, 'accessLevelID' => 2, 'privilegeCode' => 'PRODUCT', 'privilegeName' => 'Products Read'],
			['moduleID' => 3, 'accessLevelID' => 3, 'privilegeCode' => 'PRODUCT', 'privilegeName' => 'Products Update'],
			['moduleID' => 3, 'accessLevelID' => 4, 'privilegeCode' => 'PRODUCT', 'privilegeName' => 'Products Delete'],
            ['moduleID' => 4, 'accessLevelID' => 1, 'privilegeCode' => 'PRODUCT_CATEGORY','privilegeName' => 'Products Category Create'],
			['moduleID' => 4, 'accessLevelID' => 2, 'privilegeCode' => 'PRODUCT_CATEGORY','privilegeName' => 'Products Category Read'],
			['moduleID' => 4, 'accessLevelID' => 3, 'privilegeCode' => 'PRODUCT_CATEGORY','privilegeName' => 'Products Category Update'],
			['moduleID' => 4, 'accessLevelID' => 4, 'privilegeCode' => 'PRODUCT_CATEGORY','privilegeName' => 'Products Category Delete'],
			['moduleID' => 5, 'accessLevelID' => 1, 'privilegeCode' => 'SUPPLIER','privilegeName' => 'Supplier Create'],
			['moduleID' => 5, 'accessLevelID' => 2, 'privilegeCode' => 'SUPPLIER','privilegeName' => 'Supplier Read'],
			['moduleID' => 5, 'accessLevelID' => 3, 'privilegeCode' => 'SUPPLIER','privilegeName' => 'Supplier Update'],
			['moduleID' => 5, 'accessLevelID' => 4, 'privilegeCode' => 'SUPPLIER','privilegeName' => 'Supplier Delete'],
			['moduleID' => 6, 'accessLevelID' => 1, 'privilegeCode' => 'BATCH','privilegeName' => 'Batch Create'],
			['moduleID' => 6, 'accessLevelID' => 2, 'privilegeCode' => 'BATCH','privilegeName' => 'Batch Read'],
			['moduleID' => 6, 'accessLevelID' => 3, 'privilegeCode' => 'BATCH','privilegeName' => 'Batch Update'],
			['moduleID' => 6, 'accessLevelID' => 4, 'privilegeCode' => 'BATCH','privilegeName' => 'Batch Delete'],
			['moduleID' => 7, 'accessLevelID' => 1, 'privilegeCode' => 'PURCHASE_ORDER','privilegeName' => 'Purchase Order Create'],
			['moduleID' => 7, 'accessLevelID' => 2, 'privilegeCode' => 'PURCHASE_ORDER','privilegeName' => 'Purchase Order Read'],
			['moduleID' => 7, 'accessLevelID' => 3, 'privilegeCode' => 'PURCHASE_ORDER','privilegeName' => 'Purchase Order Update'],
			['moduleID' => 7, 'accessLevelID' => 4, 'privilegeCode' => 'PURCHASE_ORDER','privilegeName' => 'Purchase Order Delete'],
			['moduleID' => 8, 'accessLevelID' => 1, 'privilegeCode' => 'ACCOUNT_HEAD','privilegeName' => 'Account Head Create'],
			['moduleID' => 8, 'accessLevelID' => 2, 'privilegeCode' => 'ACCOUNT_HEAD','privilegeName' => 'Account Head Read'],
			['moduleID' => 8, 'accessLevelID' => 3, 'privilegeCode' => 'ACCOUNT_HEAD','privilegeName' => 'Account Head Update'],
			['moduleID' => 8, 'accessLevelID' => 4, 'privilegeCode' => 'ACCOUNT_HEAD','privilegeName' => 'Account Head Delete'],
			['moduleID' => 9, 'accessLevelID' => 1, 'privilegeCode' => 'BANK_ACCOUNT','privilegeName' => 'Bank Account Create'],
			['moduleID' => 9, 'accessLevelID' => 2, 'privilegeCode' => 'BANK_ACCOUNT','privilegeName' => 'Bank Account Read'],
			['moduleID' => 9, 'accessLevelID' => 3, 'privilegeCode' => 'BANK_ACCOUNT','privilegeName' => 'Bank Account Update'],
			['moduleID' => 9, 'accessLevelID' => 4, 'privilegeCode' => 'BANK_ACCOUNT','privilegeName' => 'Bank Account Delete'],
			['moduleID' => 10, 'accessLevelID' => 1, 'privilegeCode' => 'GODOWN','privilegeName' => 'Godown Create'],
			['moduleID' => 10, 'accessLevelID' => 2, 'privilegeCode' => 'GODOWN','privilegeName' => 'Godown Read'],
			['moduleID' => 10, 'accessLevelID' => 3, 'privilegeCode' => 'GODOWN','privilegeName' => 'Godown Update'],
			['moduleID' => 10, 'accessLevelID' => 4, 'privilegeCode' => 'GODOWN','privilegeName' => 'Godown Delete'],
			['moduleID' => 11, 'accessLevelID' => 1, 'privilegeCode' => 'TRANSPORT','privilegeName' => 'Transport Create'],
			['moduleID' => 11, 'accessLevelID' => 2, 'privilegeCode' => 'TRANSPORT','privilegeName' => 'Transport Read'],
			['moduleID' => 11, 'accessLevelID' => 3, 'privilegeCode' => 'TRANSPORT','privilegeName' => 'Transport Update'],
			['moduleID' => 11, 'accessLevelID' => 4, 'privilegeCode' => 'TRANSPORT','privilegeName' => 'Transport Delete'],
			['moduleID' => 12, 'accessLevelID' => 1, 'privilegeCode' => 'STAFF','privilegeName' => 'Staff Create'],
			['moduleID' => 12, 'accessLevelID' => 2, 'privilegeCode' => 'STAFF','privilegeName' => 'Staff Read'],
			['moduleID' => 12, 'accessLevelID' => 3, 'privilegeCode' => 'STAFF','privilegeName' => 'Staff Update'],
			['moduleID' => 12, 'accessLevelID' => 4, 'privilegeCode' => 'STAFF','privilegeName' => 'Staff Delete'],
			['moduleID' => 13, 'accessLevelID' => 1, 'privilegeCode' => 'STOCK','privilegeName' => 'Stock Create'],
			['moduleID' => 13, 'accessLevelID' => 2, 'privilegeCode' => 'STOCK','privilegeName' => 'Stock Read'],
			['moduleID' => 14, 'accessLevelID' => 1, 'privilegeCode' => 'SALES','privilegeName' => 'Sales Create'],
			['moduleID' => 14, 'accessLevelID' => 2, 'privilegeCode' => 'SALES','privilegeName' => 'Sales Read'],
			['moduleID' => 14, 'accessLevelID' => 3, 'privilegeCode' => 'SALES','privilegeName' => 'Sales Update'],
			['moduleID' => 14, 'accessLevelID' => 4, 'privilegeCode' => 'SALES','privilegeName' => 'Sales Delete'],
			['moduleID' => 15, 'accessLevelID' => 1, 'privilegeCode' => 'CUSTOMER','privilegeName' => 'Customer Create'],
			['moduleID' => 15, 'accessLevelID' => 2, 'privilegeCode' => 'CUSTOMER','privilegeName' => 'Customer Read'],
			['moduleID' => 15, 'accessLevelID' => 3, 'privilegeCode' => 'CUSTOMER','privilegeName' => 'Customer Update'],
			['moduleID' => 15, 'accessLevelID' => 4, 'privilegeCode' => 'CUSTOMER','privilegeName' => 'Customer Delete'],
			['moduleID' => 16, 'accessLevelID' => 1, 'privilegeCode' => 'TRANSACTION','privilegeName' => 'Transaction Create'],
			['moduleID' => 16, 'accessLevelID' => 2, 'privilegeCode' => 'TRANSACTION','privilegeName' => 'Transaction Read'],
			['moduleID' => 16, 'accessLevelID' => 3, 'privilegeCode' => 'TRANSACTION','privilegeName' => 'Transaction Update'],
			['moduleID' => 16, 'accessLevelID' => 4, 'privilegeCode' => 'TRANSACTION','privilegeName' => 'Transaction Delete'],
			['moduleID' => 17, 'accessLevelID' => 2, 'privilegeCode' => 'REPORT','privilegeName' => 'Report Read'],
			['moduleID' => 18, 'accessLevelID' => 1, 'privilegeCode' => 'SETTING','privilegeName' => 'Settings Create'],
			['moduleID' => 18, 'accessLevelID' => 2, 'privilegeCode' => 'SETTING','privilegeName' => 'Settings Read'],
			['moduleID' => 18, 'accessLevelID' => 3, 'privilegeCode' => 'SETTING','privilegeName' => 'Settings Update'],
			['moduleID' => 18, 'accessLevelID' => 4, 'privilegeCode' => 'SETTING','privilegeName' => 'Settings Delete'],
            ['moduleID' => 19, 'accessLevelID' => 1, 'privilegeCode' => 'INVOICE_BOOKS','privilegeName' => 'Invoice Books Create'],
			['moduleID' => 19, 'accessLevelID' => 2, 'privilegeCode' => 'INVOICE_BOOKS','privilegeName' => 'Invoice Books Read'],
			['moduleID' => 19, 'accessLevelID' => 3, 'privilegeCode' => 'INVOICE_BOOKS','privilegeName' => 'Invoice Books Update'],
			['moduleID' => 19, 'accessLevelID' => 4, 'privilegeCode' => 'INVOICE_BOOKS','privilegeName' => 'Invoice Books Delete']
        ];

        foreach ($aryPrivileges as $privilege) {
            \Illuminate\Support\Facades\DB::table('privilege')->insert(
				[
					'moduleID' => $privilege['moduleID'],
					'accessLevelID' => $privilege['accessLevelID'],
					'privilegeCode' => $privilege['privilegeCode'],
					'privilegeName' => $privilege['privilegeName'],
				]
			);
        }
    }
}
