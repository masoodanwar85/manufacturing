<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$aryBankAccounts = [
            ['bankID' => 2, 'accountTitle' => 'Masood Anwar','accountNumber' => '29237497947924729', 'branchCode' => 1373,'branchName' => 'Qandhari Bazar', 'branchLocation' => 'Jinnah Road'],
			['bankID' => 4, 'accountTitle' => 'Tayyab Hussain','accountNumber' => '4882929299292924', 'branchCode' => 4322,'branchName' => 'Jinnah Road', 'branchLocation' => 'Jinnah Road'],
			['bankID' => 10, 'accountTitle' => 'Mohsin Shah','accountNumber' => '84928278399584', 'branchCode' => 6322,'branchName' => 'High Court', 'branchLocation' => 'Jinnah Road'],
			['bankID' => 16, 'accountTitle' => 'Imran Khan','accountNumber' => '372894848884888484', 'branchCode' => 9274,'branchName' => 'Jinnah Road', 'branchLocation' => 'Jinnah Road'],
			['bankID' => 17, 'accountTitle' => 'Nawaz Sharif','accountNumber' => '9726279458783933', 'branchCode' => 3727,'branchName' => 'Jinnah Road', 'branchLocation' => 'Jinnah Road']
        ];
        foreach ($aryBankAccounts as $bankAccount) {
			$bankName = \App\Models\Bank::find($bankAccount['bankID'])->bankName;
			$headID = DB::table('accountHead')->insertGetId([
				'parentHeadID' => \Config::get('constants.account_heads.bank_accounts'),
                'rootHeadID' => \Config::get('constants.account_heads.assets'),
				'headName' => $bankName . ' - ' . $bankAccount['accountTitle'] . ' - ' . $bankAccount['accountNumber'] . ' (Bank Account)',
				'isSystemGenerated' => 1,
				'isEditable' => 0,
				'isShowForPurchaseOrderExpense' => 0,
				'createdByUserID' => 1
			]);
            DB::table('bankAccount')->insert([
				'headID' => $headID,
				'bankID' => $bankAccount['bankID'],
				'accountTitle' => $bankAccount['accountTitle'],
				'accountNumber' => $bankAccount['accountNumber'],
				'branchCode' => $bankAccount['branchCode'],
				'branchName' => $bankAccount['branchName'],
				'branchLocation' => $bankAccount['branchLocation'],
				'createdByUserID' => 1
			]);
        }
    }
}
