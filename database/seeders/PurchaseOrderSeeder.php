<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

	public function run()
    {
        $aryPurchaseOrders = [
            ['supplierID' => 1, 'lastGodownID' => rand(1,5),'batchID' => 4, 'purchaseOrderDate' => '2020-12-10','purchaseOrderDetail' => [
					['productID' => 1, 'quantity' => 10, 'damaged' => 0,'exchangeRate' => 10, 'perUnitPrice' => 10],
					['productID' => 2, 'quantity' => 20, 'damaged' => 0, 'exchangeRate' => 5, 'perUnitPrice' => 100],
					['productID' => 3, 'quantity' => 5, 'damaged' => 0, 'exchangeRate' => 8, 'perUnitPrice' => 100]
				], 'expenses' => [
					['subHeadID' => 38,'exchangeRate' => 10, 'amount' => 450],
					['subHeadID' => 0,'exchangeRate' => 20, 'amount' => 200]
				]
			],
            ['supplierID' => 2, 'lastGodownID' => rand(1,5),'batchID' => 4, 'purchaseOrderDate' => '2020-12-11','purchaseOrderDetail' => [
					['productID' => 4, 'quantity' => 20, 'damaged' => 0, 'exchangeRate' => 10, 'perUnitPrice' => 10],
					['productID' => 5, 'quantity' => 30, 'damaged' => 5, 'exchangeRate' => 5, 'perUnitPrice' => 100],
					['productID' => 6, 'quantity' => 10, 'damaged' => 0, 'exchangeRate' => 8, 'perUnitPrice' => 100]
				], 'expenses' => [
					['subHeadID' => 0,'exchangeRate' => 10, 'amount' => 800],
					['subHeadID' => 0,'exchangeRate' => 20, 'amount' => 500]
				]
			],
            ['supplierID' => 3, 'lastGodownID' => rand(1,5),'batchID' => 4, 'purchaseOrderDate' => '2020-12-12','purchaseOrderDetail' => [
					['productID' => 7, 'quantity' => 10, 'damaged' => 0, 'exchangeRate' => 23, 'perUnitPrice' => 5],
					['productID' => 8, 'quantity' => 4, 'damaged' => 0, 'exchangeRate' => 22, 'perUnitPrice' => 10]
				], 'expenses' => [
					['subHeadID' => 0,'exchangeRate' => 5, 'amount' => 500]
				]
			],
            ['supplierID' => 4, 'lastGodownID' => rand(1,5),'batchID' => 4, 'purchaseOrderDate' => '2020-12-13','purchaseOrderDetail' => [
					['productID' => 9, 'quantity' => 80, 'damaged' => 0, 'exchangeRate' => 10, 'perUnitPrice' => 10],
					['productID' => 10, 'quantity' => 50, 'damaged' => 0, 'exchangeRate' => 50, 'perUnitPrice' => 5]
				], 'expenses' => [
					['subHeadID' => 0,'exchangeRate' => 10, 'amount' => 450],
					['subHeadID' => 38,'exchangeRate' => 10, 'amount' => 800]
				]
			],
			['supplierID' => 5, 'lastGodownID' => rand(1,5),'batchID' => 4, 'purchaseOrderDate' => '2020-12-14','purchaseOrderDetail' => [
					['productID' => 8, 'quantity' => 1, 'damaged' => 0, 'exchangeRate' => 10, 'perUnitPrice' => 900],
					['productID' => 1, 'quantity' => 20, 'damaged' => 0, 'exchangeRate' => 33, 'perUnitPrice' => 100],
					['productID' => 6, 'quantity' => 7, 'damaged' => 0, 'exchangeRate' => 8, 'perUnitPrice' => 10]
				], 'expenses' => []
			],
			['customerID' => 1, 'lastGodownID' => rand(1,5),'batchID' => 4, 'purchaseOrderDate' => '2020-12-14','purchaseOrderDetail' => [
					['productID' => 8, 'quantity' => 2, 'damaged' => 0, 'exchangeRate' => 10, 'perUnitPrice' => 900],
					['productID' => 1, 'quantity' => 20, 'damaged' => 2, 'exchangeRate' => 33, 'perUnitPrice' => 100],
					['productID' => 6, 'quantity' => 8, 'damaged' => 0, 'exchangeRate' => 8, 'perUnitPrice' => 10]
				], 'expenses' => [
					['subHeadID' => 38,'exchangeRate' => 1, 'amount' => 4500]
				]
			]
        ];

		foreach ($aryPurchaseOrders as $purchaseOrder) {
			$purchaseOrderInfo = [
				'batchID' => $purchaseOrder['batchID'],
				'lastGodownID' => $purchaseOrder['lastGodownID'],
				'purchaseOrderDate' => $purchaseOrder['purchaseOrderDate'],
				'createdByUserID' => 1
			];

			$purchaseOrderInfo['supplierID'] = null;
			$purchaseOrderInfo['customerID'] = null;
			if (array_key_exists('supplierID',$purchaseOrder)) {
				$subHeadID = \App\Models\Supplier::find($purchaseOrder['supplierID'])->headID;
				$purchaseOrderInfo['supplierID'] = $purchaseOrder['supplierID'];
				$headID = \Config::get('constants.account_heads.supplier_payable');
			} else {
				$subHeadID = \App\Models\Customer::find($purchaseOrder['customerID'])->headID;
				$purchaseOrderInfo['customerID'] = $purchaseOrder['customerID'];
				$headID = \Config::get('constants.account_heads.customer_payable');
			}

			$purchaseOrderID = DB::table('purchaseOrder')->insertGetId($purchaseOrderInfo);

			foreach ($purchaseOrder['purchaseOrderDetail'] as $purchaseOrderDetail) {
				$unitsInProduct = \App\Models\Product::find($purchaseOrderDetail['productID'])->unitsInProduct;
				$updatedQty = $purchaseOrderDetail['quantity'] * $unitsInProduct;
				$purchaseOrderDetailID = DB::table('purchaseOrderDetail')->insertGetId([
					'purchaseOrderID' => $purchaseOrderID,
					'productID' => $purchaseOrderDetail['productID'],
					'quantity' => $updatedQty,
					'damaged' => $purchaseOrderDetail['damaged'] * $unitsInProduct,
					'exchangeRate' => $purchaseOrderDetail['exchangeRate'],
					'foreignPerUnitPrice' => $purchaseOrderDetail['perUnitPrice'],
					'perUnitPrice' => (new \App\Services\CurrencyService())->setExchangeRate($purchaseOrderDetail['exchangeRate'])->setPrice($purchaseOrderDetail['perUnitPrice'])->convertToPKR()
				]);

				$totalInPKR = (new \App\Services\CurrencyService())
								->setExchangeRate($purchaseOrderDetail['exchangeRate'])
								->setPrice($purchaseOrderDetail['perUnitPrice'])
								->setQuantity($updatedQty)
								->convertToPKR();

				$total = $purchaseOrderDetail['perUnitPrice'] * $updatedQty;

				$transactionID = DB::table('transaction')->insertGetId([
					'transactionTypeID' => 1,
					'batchID' => $purchaseOrder['batchID'],
					'exchangeRate' => $purchaseOrderDetail['exchangeRate'],
					'createdByUserID' => 1
				]);

				DB::table('transactionDetail')->insert([
					[
						'transactionID' => $transactionID,
						'headID' => \Config::get('constants.account_heads.purchases'),
						'subHeadID' => $subHeadID,
						'isDebit' => 1,
						'foreignAmount' => $total,
						'amount' => $totalInPKR,
						'description' => 'Purchases from Supplier/Customer'
					],
					[
						'transactionID' => $transactionID,
						'headID' => $headID,
						'subHeadID' => $subHeadID,
						'isDebit' => 0,
						'foreignAmount' => $total,
						'amount' => $totalInPKR,
						'description' => 'Payable Expense incurred from Supplier/Customer Purchases'
					]
				]);

				DB::table('purchaseOrderTransaction')->insert([
					'purchaseOrderID' => $purchaseOrderID,
					'purchaseOrderDetailID' => $purchaseOrderDetailID,
					'transactionID' => $transactionID,
					'isExpense' => 0
				]);
			}

			foreach ($purchaseOrder['expenses'] as $expenses) {
				$totalInPKR = (new \App\Services\CurrencyService())->setExchangeRate($expenses['exchangeRate'])->setPrice($expenses['amount'])->convertToPKR();
				$subHeadID = $expenses['subHeadID'];
				if ($subHeadID == 0) {
					$godownOrTransport = rand(1,2);
					if ($godownOrTransport == 1) {
						$subHeadID = \App\Models\Godown::find(rand(1,5))->headID;
					} else {
						$subHeadID = \App\Models\Transport::find(rand(1,5))->headID;
					}
				}
				$parentAccountHead = \App\Models\AccountHead::whereRaw('headID IN (SELECT parentHeadID from accountHead WHERE headID = ' . $subHeadID . ')')->first();
				$transactionID = DB::table('transaction')->insertGetId([
					'transactionTypeID' => 1,
					'batchID' => $purchaseOrder['batchID'],
					'exchangeRate' => $expenses['exchangeRate'],
					'createdByUserID' => 1
				]);

				DB::table('transactionDetail')->insert([
					[
						'transactionID' => $transactionID,
						'headID' => $parentAccountHead->headID,
						'subHeadID' => $subHeadID,
						'isDebit' => 1,
						'foreignAmount' => $expenses['amount'],
						'amount' => $totalInPKR,
						'description' => 'Expense incurred from Supplier/Customer to Owner'
					],
					[
						'transactionID' => $transactionID,
						'headID' => \Config::get('constants.account_heads.accounts_payable'),
						'subHeadID' => $subHeadID,
						'isDebit' => 0,
						'foreignAmount' => $expenses['amount'],
						'amount' => $totalInPKR,
						'description' => 'Payable Expense incurred from Supplier/Customer to Owner'
					]
				]);

				DB::table('purchaseOrderTransaction')->insert([
					'purchaseOrderID' => $purchaseOrderID,
					'transactionID' => $transactionID,
					'isExpense' => 1
				]);
			}
        }
    }
}
