<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryAccountHeads = [
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Assets', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Liabilities', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Capital', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Revenue', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Supplier', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 1],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Staff', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Customer', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 1],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Godown', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 1, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 1],
			['parentHeadID' => NULL, 'rootHeadID' => NULL,'headName' => 'Transport', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 1, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 1],
			['parentHeadID' => 1, 'rootHeadID' => 1, 'headName' => 'Cash', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 1],
			['parentHeadID' => 11, 'rootHeadID' => 1, 'headName' => 'Petty Cash', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 1, 'rootHeadID' => 1, 'headName' => 'Accounts Receivable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 1, 'rootHeadID' => 1, 'headName' => 'Bank Accounts', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 1],
			['parentHeadID' => 2, 'rootHeadID' => 2, 'headName' => 'Accounts payable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 2, 'rootHeadID' => 2, 'headName' => 'Salaries Payable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 3, 'rootHeadID' => 3, 'headName' => 'Owner Capital', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 4, 'rootHeadID' => 4, 'headName' => 'Sales', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Purchases', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Salaries', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Utility Bills', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Rent', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 1, 'rootHeadID' => 1, 'headName' => 'Sales Return - Good', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Office Supplies', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Sales Return - Bad', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Purchase Commission', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 13, 'rootHeadID' => 1, 'headName' => 'Salaries Advance', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 13, 'rootHeadID' => 1, 'headName' => 'Cash Advance', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Bank Charges', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Conveyance Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0, 'isShowForBOMExpense' => 1],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Electricity Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0, 'isShowForBOMExpense' => 1],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Godown Rent', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 1, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Office Equipment', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Office Maintenace/Repair', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Miscellaneous Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0, 'isShowForBOMExpense' => 1],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Daily Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Iran Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Purchase Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 1, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Transport Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 1, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0, 'isShowForBOMExpense' => 1],
			['parentHeadID' => 1, 'rootHeadID' => 1, 'headName' => 'Loan to Staff', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Sales Tax', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 38, 'rootHeadID' => 5, 'headName' => 'Custom Tax', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 1, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 38, 'rootHeadID' => 5, 'headName' => 'Misc. Purchase Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 1, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Income Tax', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Excise Tax', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 1, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Sales Commission', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
            ['parentHeadID' => 13, 'rootHeadID' => 1, 'headName' => 'Customer Receivable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 13, 'rootHeadID' => 1, 'headName' => 'Godown Receivable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 13, 'rootHeadID' => 1, 'headName' => 'Transport Receivable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
            ['parentHeadID' => 13, 'rootHeadID' => 1, 'headName' => 'Staff Receivable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
            ['parentHeadID' => 13, 'rootHeadID' => 1, 'headName' => 'Supplier Receivable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
            ['parentHeadID' => 15, 'rootHeadID' => 2, 'headName' => 'Customer Payable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
            ['parentHeadID' => 15, 'rootHeadID' => 2, 'headName' => 'Staff Payable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
            ['parentHeadID' => 15, 'rootHeadID' => 2, 'headName' => 'Supplier Payable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 15, 'rootHeadID' => 2, 'headName' => 'Godown Payable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 15, 'rootHeadID' => 2, 'headName' => 'Transport Payable', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 4, 'rootHeadID' => 4, 'headName' => 'Service Revenue', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],
			['parentHeadID' => 5, 'rootHeadID' => 5, 'headName' => 'Service Expense', 'isSystemGenerated' => 0, 'isEditable' => 0, 'isShowForPurchaseOrderExpense' => 0, 'isShowForPayment' => 0, 'isShowForReceipt' => 0,'isShowForOpeningBalance' => 0],

        ];
        foreach ($aryAccountHeads as $accountHead) {
            \Illuminate\Support\Facades\DB::table('accountHead')->insert([
				'parentHeadID' => $accountHead['parentHeadID'],
                'rootHeadID' => $accountHead['rootHeadID'],
				'headName' => $accountHead['headName'],
				'isSystemGenerated' => $accountHead['isSystemGenerated'],
				'isEditable' => $accountHead['isEditable'],
				'isShowForPurchaseOrderExpense' => $accountHead['isShowForPurchaseOrderExpense'],
				'isShowForPayment' => $accountHead['isShowForPayment'],
				'isShowForOpeningBalance' => $accountHead['isShowForOpeningBalance'],
				'isShowForBOMExpense' => isset($accountHead['isShowForBOMExpense']) ? $accountHead['isShowForBOMExpense'] : 0,
				'createdByUserID' => 1
			]);
        }
    }
}
