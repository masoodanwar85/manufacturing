<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use Illuminate\Support\Facades\DB;

class SalesOrderService {

	public function save($request) {
		DB::beginTransaction();
		try {

			// salesOrder
			$request->merge(['orderDate' => date('Y-m-d', strtotime($request->orderDate))]);
			$request->merge(['paymentDueDate' => date('Y-m-d', strtotime($request->paymentDueDate))]);
			$request->merge(['createdByUserID' => Auth::id()]);
			$request->merge(['invoiceNumber' => SalesOrder::select(DB::raw('IFNULL(max(CAST(invoiceNumber AS UNSIGNED)),0)+1 AS nextInvoiceNumber'))->first()->nextInvoiceNumber]);
			$salesOrder = SalesOrder::create($request->all());

			if ($this->insertSalesOrderDetails($request,$salesOrder) == true) {
				DB::commit();
				$request->session()->flash('message', 'Sales Order created successfully!');
				return true;
			} else {
				DB::rollback();
				$request->session()->flash('error', 'An error occurred while creating sales order!');
				return false;
			}
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while creating sales order!');
			return false;
		}
	}

	public function update($request,$salesOrder) {
		DB::beginTransaction();
		try {
			$request->merge(['orderDate' => date('Y-m-d', strtotime($request->orderDate))]);
			$request->merge(['paymentDueDate' => date('Y-m-d', strtotime($request->paymentDueDate))]);
			$salesOrder->update($request->all());

			$missedProducts = 0;
			$now = date('Y-m-d');
			$customerHeadID = \App\Models\Customer::find($request->customerID)->headID;

			// salesOrder

			// Delete all from stockDetailStatus
			$salesOrder->salesOrderDetails()->delete();
			$stockDetailStatusIDs = $salesOrder->salesOrderDetails->pluck('stockDetailStatusID')->toArray();
			DB::table('stockDetailStatus')->whereIn('stockDetailStatusID', $stockDetailStatusIDs)->delete();
			$SOTransactions = $salesOrder->transactions;
			$salesOrder->transactions()->detach();
			foreach ($SOTransactions as $transaction) {
				\App\Services\TransactionService::deleteTransaction($transaction->transactionID);
			}

			if ($this->insertSalesOrderDetails($request,$salesOrder) == true) {
				DB::commit();
				$request->session()->flash('message', 'Sales Order updated successfully!');
				return true;
			} else {
				DB::rollback();
				$request->session()->flash('error', 'An error occurred while updating sales order!');
				return false;
			}

		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating sales order!');
			return false;
		}
	}

	public function insertSalesOrderDetails($request,$salesOrder) {
		$missedProducts = 0;
		$now = date('Y-m-d');

		$customerHeadID = \App\Models\Customer::find($request->customerID)->headID;

		$discount = $request->discount;
		$shippingCharges = $request->shippingCharges;

		$count = count($request->productID);
		$total = 0;
		for ($i=0; $i < $count; $i++) {
			// Get Product Stock Availability
			$product = \App\Models\Stock::getProducts($request->productID[$i]);
			$updatedQuantity = $request->quantity[$i];

			if (!empty($product) && $product[0]->quantityAvailable >= $updatedQuantity) {

				// Get Stock Details of this product
				// $stockDetails = \App\Models\Stock::getProductStockDetails($request->productID[$i]);
				// TODO:: Fix in case of edit sales order and there is no stock available for that product in that godown.
				$stockDetails = \App\Models\Stock::getProductStockDetails($request->productID[$i],$request->godownID[$i]);

				if (empty($stockDetails)) {
					DB::rollBack();
					$request->session()->flash('error', 'Form tempering observed, so order not saved.');
					return false;

					// $stockDetails = \App\Models\Stock::getProductStockDetails($request->productID[$i]);
					//
					// if (empty($stockDetails)) {
					// 	DB::rollBack();
					// 	$request->session()->flash('error', 'Form tempering observed, so order not saved.');
					// 	return false;
					// }
				}

				$quantityRemaining = $updatedQuantity;
				foreach ($stockDetails as $stockDetailInfo) {
					if ($quantityRemaining == 0) {
						break;
					} else {
						$stockDetailStatus = new \App\Models\StockDetailStatus();
						$stockDetailStatus->stockDetailID = $stockDetailInfo->stockDetailID;
						$stockDetailStatus->statusID = \Config::get('constants.stock_status.sold');
						$stockDetailStatus->batchID = \App\Services\BatchService::getCurrentBatch()->batchID;
						$stockDetailStatus->godownID = $stockDetailInfo->godownID;
						$stockDetailStatus->salePrice = str_replace(',','',$request->salePrice[$i]);
						$stockDetailStatus->discount = str_replace(',','',$request->product_discount[$i]);
						$stockDetailStatus->createdByUserID = Auth::id();
					}
					if ($stockDetailInfo->quantityAvailable >= $quantityRemaining) {
						$stockDetailStatus->quantity = $quantityRemaining;
						$stockDetailStatus->quantityUnits = $quantityRemaining;
						$stockDetailStatus->save();

						// Insert stockDetailStatusID in salesOrderDetail
						$salesOrder->stockDetailStatuses()->attach($stockDetailStatus->stockDetailStatusID);
						$quantityRemaining = 0;
						break;
					} else {
						if ($stockDetailInfo->quantityAvailable >= $quantityRemaining) {
							dd('Please contact Admin...');
							$stockDetailStatus->quantity = $quantityRemaining;
							$quantityRemaining = 0;
						} elseif ($stockDetailInfo->quantityAvailable == 0) {
							$stockDetailStatus->quantity = 0;
						} else {
							// $quantityRemaining -= $stockDetailInfo->quantityAvailable;
							$stockDetailStatus->quantity = $stockDetailInfo->quantityAvailable;
						}

						$stockDetailStatus->quantityUnits = $stockDetailInfo->unitsAvailable;
						$stockDetailStatus->godownID = $stockDetailInfo->godownID;
						$stockDetailStatus->save();
						// Insert stockDetailStatusID in salesOrderDetail
						$salesOrder->stockDetailStatuses()->attach($stockDetailStatus->stockDetailStatusID);
						$quantityRemaining-=$stockDetailInfo->quantityAvailable;
					}
				}

				$actualQuantity = $updatedQuantity - $quantityRemaining;

				$total+= ($actualQuantity * floatval(str_replace(',','',$request->salePrice[$i]))) - ($actualQuantity * floatval(str_replace(',','',$request->product_discount[$i])));
			} else {
				$missedProducts+=1;
				DB::rollBack();
				$request->session()->flash('error', 'Form tempering observed, so order not saved.');
				return false;
			}
		}

		// Discount and Shipping Charges

		$total+=$shippingCharges-$discount;

		if ($total > 0) {

			$transactionID = \App\Services\TransactionService::addTransaction(1,1,null,$request->bookSerial,0,$request->orderDate);

			// If Paid in full
			// If paid less than total
			// If paid none

			// Transaction Details
			if ((int) $request->amountPaid > 0) {
				if ($request->amountPaid == $total) {
					// Dr = Cash and Cr = Sales
					\App\Services\TransactionService::addTransactionDetail($transactionID,\Config::get('constants.account_heads.cash'),$customerHeadID,1,$total,'Sales on Cash');
					\App\Services\TransactionService::addTransactionDetail($transactionID,\Config::get('constants.account_heads.sales'),$customerHeadID,0,$total,'Sales Order');
				} elseif ($request->amountPaid < $total) {
					// Dr = Cash + Dr = Account Receivable (Customer subHeadID) and Cr = Sales
					$accountReceivables = $total - $request->amountPaid;
					\App\Services\TransactionService::addTransactionDetail($transactionID,\Config::get('constants.account_heads.customer_receivable'),$customerHeadID,1,$accountReceivables,'Account Receivable for customer sale');
					\App\Services\TransactionService::addTransactionDetail($transactionID,\Config::get('constants.account_heads.cash'),$customerHeadID,1,$request->amountPaid,'Sales on Cash');
					\App\Services\TransactionService::addTransactionDetail($transactionID,\Config::get('constants.account_heads.sales'),$customerHeadID,0,$total,'Sales Order');
				} elseif ($request->amountPaid > $total) {
					// TODO:: If amount paid more than the order, then either adjust or customer payable
					DB::rollback();
					$request->session()->flash('error', 'Amount paid more than the order function is not handled by the application. Please contact Admin.');

					return false;
				}
			} else {
				// Dr = Account Receivable (Customer subHeadID) and Cr = Sales
				\App\Services\TransactionService::addTransactionDetail($transactionID,\Config::get('constants.account_heads.customer_receivable'),$customerHeadID,1,$total,'Account Receivable for customer sale');
				\App\Services\TransactionService::addTransactionDetail($transactionID,\Config::get('constants.account_heads.sales'),$customerHeadID,0,$total,'Sales Order');
			}

			$salesOrder->transactions()->attach($transactionID, ['createdByUserID' => Auth::id()]);
		}

		return true;
	}

}
