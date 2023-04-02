<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService {

	public static function getPurchaseOrderExpensesSubQuery() {
		return "
			SELECT SUM(transactionDetail.amount " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate) / 2
			FROM transactionDetail
			INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
			INNER JOIN purchaseOrderTransaction ON purchaseOrderTransaction.transactionID = `transaction`.transactionID
			WHERE purchaseOrderTransaction.purchaseOrderID = purchaseOrder.purchaseOrderID AND purchaseOrderTransaction.isExpense = 1
			GROUP BY purchaseOrderTransaction.purchaseOrderID
		";
	}

	private function savePurchaseOrderDetails($request, $purchaseOrder) {
		if ($request->isSupplier == 1) {
			$supplierCustomerHeadID = \App\Models\Supplier::find($request->supplierID)->headID;
			$payableHeadID = \Config::get('constants.account_heads.supplier_payable');
		} else {
			$supplierCustomerHeadID = \App\Models\Customer::find($request->customerID)->headID;
			$payableHeadID = \Config::get('constants.account_heads.customer_payable');
		}

		$count = count($request->productID);
		for ($i=0; $i < $count; $i++) {

			$qtyUnits = $request->quantity[$i];
			if (\Config::get('constants.client_settings.is_units_in_product_fixed') == 0 && \App\Models\Product::find($request->productID[$i])->isUnitsInProductFixed == 0) {
				$qtyUnits = $request->totalUnits[$i];
			}

			$totalAmount = $request->perUnitPrice[$i] * $qtyUnits;

			$purchaseOrderDetail = new \App\Models\PurchaseOrderDetail();
			$purchaseOrderDetail->productID = $request->productID[$i];
			$purchaseOrderDetail->quantity = $request->quantity[$i];
			$purchaseOrderDetail->quantityUnits = $request->quantity[$i];
			$purchaseOrderDetail->damaged = $request->damaged[$i];
			$purchaseOrderDetail->exchangeRate = $request->exchangeRate[$i];
			if ($request->perUnitPrice[$i] > 0) {
				$purchaseOrderDetail->perUnitPrice = $request->perUnitPrice[$i];
			} else {
				$purchaseOrderDetail->perUnitPrice = \App\Models\Product::find($request->productID[$i])->unitPurchasePrice;
			}

			$purchaseOrderDetail->purchaseOrderID = $purchaseOrder->purchaseOrderID;
			$purchaseOrderDetail->save();

			// Supplier Transactions
			$transactionID = \App\Services\TransactionService::addTransaction(1,$request->exchangeRate[$i],$request->batchID,null,0,$purchaseOrder->purchaseOrderDate);

			// Transaction Details
			// Debit
			\App\Services\TransactionService::addTransactionDetail($transactionID,\Config::get('constants.account_heads.purchases'),$supplierCustomerHeadID,1,$totalAmount,'Purchases from Supplier/Customer');

			//Credit
			\App\Services\TransactionService::addTransactionDetail($transactionID,$payableHeadID,$supplierCustomerHeadID,0,$totalAmount,'Payable Expense incurred from Supplier/Customer Purchases');

			$purchaseOrder->transactions()->attach($transactionID, ['purchaseOrderDetailID' => $purchaseOrderDetail->purchaseOrderDetailID,'isExpense' => 0]);
		}

		// Expense
		if ($request->has('headID')) {
			$countExpense = count($request->headID);
			for ($i=0; $i < $countExpense; $i++) {
				$transactionID = \App\Services\TransactionService::addTransaction(1,$request->expenseExchangeRate[$i],$request->batchID,null,0,$purchaseOrder->purchaseOrderDate);

				$parentAccountHead = \App\Models\AccountHead::whereRaw('headID IN (SELECT parentHeadID from accountHead WHERE headID = ' . $request->headID[$i] . ')')->first();

				$debitHeadID = $parentAccountHead->headID;
			    $creditHeadID = \Config::get('constants.account_heads.accounts_payable');
				// Transaction Details

				// Debit
				\App\Services\TransactionService::addTransactionDetail($transactionID,$debitHeadID,$request->headID[$i],1,$request->amount[$i],'Expense incurred from Supplier/Customer to Owner');

				//Credit
				\App\Services\TransactionService::addTransactionDetail($transactionID,$creditHeadID,$request->headID[$i],0,$request->amount[$i],'Payable Expense incurred from Supplier/Customer to Owner');

				$purchaseOrder->transactions()->attach($transactionID, ['isExpense' => 1]);
			}
		}
	}

	private function getPurchaseOrderFields($request) {
		if ($request->isSupplier == 1) {
			$request->merge([
				'supplierID' => $request->supplierID,
				'customerID' => null
			]);
		} else {
			$request->merge([
				'customerID' => $request->customerID,
				'supplierID' => null
			]);
		}

		return $request;
	}

	public function save($request) {
		$request = $this->getPurchaseOrderFields($request);
		$request->request->add(['createdByUserID' => Auth::id()]);
		$purchaseOrder = PurchaseOrder::create($request->all());
		$this->savePurchaseOrderDetails($request, $purchaseOrder);
	}

	public function update($request,$purchaseOrder) {
		$request = $this->getPurchaseOrderFields($request);
		$purchaseOrder->update($request->all());

		$POTransactions = $purchaseOrder->transactions;
		$purchaseOrder->transactions()->detach();
		foreach ($POTransactions as $transaction) {
			\App\Services\TransactionService::deleteTransaction($transaction->transactionID);
		}

		$purchaseOrder->purchaseOrderDetails()->delete();
		$this->savePurchaseOrderDetails($request, $purchaseOrder);
	}

	public static function getPurchaseOrderPricesTotal($purchaseOrderID) {
		return DB::table('purchaseOrder')
					->join('purchaseOrderDetail','purchaseOrderDetail.purchaseOrderID','=','purchaseOrder.purchaseOrderID')
					->select(DB::raw('SUM(' . \App\Services\CurrencyService::strQueryFormulaToPKRConversion(). ') AS totalInPKR,(' . self::getPurchaseOrderExpensesSubQuery() . ') AS totalExpenseInPKR'))
					->where('purchaseOrder.purchaseOrderID', $purchaseOrderID)
					->groupBy('purchaseOrder.purchaseOrderID')
					->first();
	}

	public static function insertStockInfo($stock,$purchase,$purchaseOrderDetail,$totalProductsPriceInPKR,$totalExpenseInPKR,$updatedQuantity = 0) {
		$totalInPKR = $purchaseOrderDetail->perUnitPrice * $purchaseOrderDetail->quantityUnits / $purchaseOrderDetail->exchangeRate;

		if (\Config::get('constants.client_settings.operatorToConvertToPKR') == '*') {
			$totalInPKR = $purchaseOrderDetail->perUnitPrice * $purchaseOrderDetail->quantityUnits * $purchaseOrderDetail->exchangeRate;
		}

		$purchasePrice = $totalInPKR;
		if ($totalExpenseInPKR > 0) {
			//expenseForThisProduct = (((singleProductPrice x 100) / totalProductsPrice) / 100) * totalExpense
			$purchasePrice = $totalInPKR + (((($totalInPKR * 100) / $totalProductsPriceInPKR) / 100) * $totalExpenseInPKR);
		}

		$perUnitPurchasePrice = $purchasePrice / $purchaseOrderDetail->quantityUnits;
		$stockDetail = new \App\Models\StockDetail();
		$stockDetail->stockID = $stock->stockID;
		$stockDetail->godownID = $purchase->lastGodownID;
		$stockDetail->purchaseOrderDetailID = $purchaseOrderDetail->purchaseOrderDetailID;
		$stockDetail->productID = $purchaseOrderDetail->productID;
		if ($updatedQuantity > 0) {
			$stockDetail->quantity = $updatedQuantity;
			// TODO:: Quantity and Units should come from form
			$stockDetail->quantityUnits = $updatedQuantity;
		} else {
			$stockDetail->quantity = $purchaseOrderDetail->quantity;
			$stockDetail->quantityUnits = $purchaseOrderDetail->quantity;
		}

		if ($perUnitPurchasePrice > 0) {
			$stockDetail->purchasePrice = $perUnitPurchasePrice;
		} else {
			$stockDetail->purchasePrice = \App\Models\Product::find($purchaseOrderDetail->productID)->unitPurchasePrice;
		}

		$stockDetail->save();

		// Update Product Table by this purchase price
		if ($perUnitPurchasePrice > 0) {
			\App\Models\Product::find($purchaseOrderDetail->productID)->update(['unitPurchasePrice' => $perUnitPurchasePrice]);
		}

		// Add in stockDetailStatus table
		$stockDetailStatus = new \App\Models\StockDetailStatus();
		$stockDetailStatus->stockDetailID = $stockDetail->stockDetailID;
		$stockDetailStatus->statusID = \Config::get('constants.stock_status.quetta_godown');
		$stockDetailStatus->godownID = $purchase->lastGodownID;
		$stockDetailStatus->transferDate = date('Y-m-d');
		$stockDetailStatus->batchID = $purchase->batchID;
		if ($updatedQuantity > 0) {
			$stockDetailStatus->quantity = $updatedQuantity;
			// TODO:: Quantity and Units should come from form
		} else {
			$stockDetailStatus->quantity = $purchaseOrderDetail->quantity;
			$stockDetailStatus->quantityUnits = $purchaseOrderDetail->quantity;
		}
		$stockDetailStatus->salePrice = 0;
		$stockDetailStatus->createdByUserID = Auth::id();
		$stockDetailStatus->save();

		if ($purchaseOrderDetail->damaged > 0) {
			$stockDetailStatusDamaged = $stockDetailStatus->replicate();
			$stockDetailStatusDamaged->quantity = $purchaseOrderDetail->damaged;
			// TODO:: Quantity and Units both should come from form
			$stockDetailStatusDamaged->statusID = \Config::get('constants.stock_status.damaged');
			$stockDetailStatusDamaged->save();
		}
	}

}
