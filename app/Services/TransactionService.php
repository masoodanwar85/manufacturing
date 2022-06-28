<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionService {

	public static function deleteTransaction($transactionID) {
		$transaction = Transaction::find($transactionID);
		$transaction->transactionDetails()->delete();
		if ($transaction->delete()) {
			return true;
		} else {
			return false;
		}
	}

	public static function addTransaction($transactionTypeID = 1, $exchangeRate = 1, $batchID = null,$transactionTypeNumber = null,$isPaymentReceipt = 0,$transactionDate = null) {
		$transaction = new Transaction();
		$transaction->transactionTypeID = $transactionTypeID;
		$transaction->batchID = ($batchID == null) ? \App\Services\BatchService::getCurrentBatch()->batchID : $batchID;
		$transaction->exchangeRate = $exchangeRate;
		$transaction->transactionDate = $transactionDate;
		if ($transactionDate == null) {
			$transaction->transactionDate = date('Y-m-d');
		}
		$transaction->isPaymentReceipt = $isPaymentReceipt;
		$transaction->transactionTypeNumber = $transactionTypeNumber;
		$transaction->createdByUserID = Auth::id();
		$transaction->save();
		return $transaction->transactionID;
	}

	public static function addTransactionArray($aryTransaction = []) {
		if (!empty($aryTransaction)) {
			$transaction = new Transaction();
			if (Arr::exists($aryTransaction,'transactionTypeNumber')) {
				$transaction->transactionTypeNumber = $aryTransaction['transactionTypeNumber'];
			}
			$transaction->transactionDate = date('Y-m-d');
			if (Arr::exists($aryTransaction,'transactionDate')) {
				$transaction->transactionDate = $aryTransaction['transactionDate'];
			}
			$transaction->isPaymentReceipt = 0;
			if (Arr::exists($aryTransaction,'isPaymentReceipt')) {
				$transaction->isPaymentReceipt = $aryTransaction['isPaymentReceipt'];
			}
			$transaction->transactionTypeID = $aryTransaction['transactionTypeID'];

			$transaction->batchID = \App\Services\BatchService::getCurrentBatch()->batchID;
			if (Arr::exists($aryTransaction,'batchID')) {
				$transaction->batchID = $aryTransaction['batchID'];
			}
			$transaction->exchangeRate = 1;
			if (Arr::exists($aryTransaction,'exchangeRate')) {
				$transaction->exchangeRate = $aryTransaction['exchangeRate'];
			}
			$transaction->createdByUserID = Auth::id();
			if ($transaction->createdByUserID == NULL) {
				$transaction->createdByUserID = $aryTransaction['createdByUserID'];
			}
			$transaction->save();
			return $transaction->transactionID;
		} else {
			return null;
		}
	}

	public static function addTransactionDetailArray($aryTransactionDetail = []) {
		if (!empty($aryTransactionDetail)) {
			$exchangeRate = Transaction::find($aryTransactionDetail['transactionID'])->exchangeRate;
			$transactionDetail = new TransactionDetail();
			$transactionDetail->transactionID = $aryTransactionDetail['transactionID'];
			$transactionDetail->headID = $aryTransactionDetail['headID'];
			$transactionDetail->subHeadID = $aryTransactionDetail['subHeadID'];
			$transactionDetail->isDebit = $aryTransactionDetail['isDebit'];
			$transactionDetail->amount = $aryTransactionDetail['amount'];
			$transactionDetail->description = $aryTransactionDetail['description'];
			$transactionDetail->save();
			return $transactionDetail->transactionDetailID;
		} else {
			return null;
		}
	}

	public static function addTransactionDetail($transactionID,$headID,$subHeadID,$isDebit,$amount,$description) {
		$exchangeRate = Transaction::find($transactionID)->exchangeRate;
		$transactionDetail = new TransactionDetail();
		$transactionDetail->transactionID = $transactionID;
		$transactionDetail->headID = $headID;
		$transactionDetail->subHeadID = $subHeadID;
		$transactionDetail->isDebit = $isDebit;
		$transactionDetail->amount = $amount;
		$transactionDetail->description = $description;
		$transactionDetail->save();
		return $transactionDetail->transactionDetailID;
	}

	public static function updateTransactionDetail($transactionDetailID,$aryUpdate = []) {
		if (!empty($aryUpdate)) {
			$transactionDetail = TransactionDetail::find($transactionDetailID);
			if (Arr::has($aryUpdate, 'amount')) {
				$transactionDetail->amount = $aryUpdate['amount'];
			}
			if (Arr::has($aryUpdate, 'description')) {
				$transactionDetail->description = $aryUpdate['description'];
			}
			$transactionDetail->save();
		}
	}

	public static function getSubHeadTransactions($subHeadID,$withRelationship = null,$sortBy = "transactionDate",$sortOrder = "DESC") {
		$transactions = \App\Models\Transaction::whereIn('transactionID',\App\Models\TransactionDetail::groupBy('transactionID')->where('subHeadID',$subHeadID)->pluck('transactionID')->toArray())->orderBy($sortBy,$sortOrder)->get();
		if ($withRelationship == null) {
			$transactions->load($withRelationship);
		}
		return $transactions;
	}

	// public static function getAccountsReceivables($accountHeadID) {
	// 	$returnedArray = \App\Models\AccountHead::getAccountHeadChilds($accountHeadID);
	// 	$aryHeadIDs = join(',',\App\Models\AccountHead::getAccountHeadChildIDs($returnedArray));
	// 	DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
	// 	$rawSQL = "
	// 		SELECT Temp.headID,accountHead.headName,SUM(Temp.totalReceivables) AS totalAmount FROM (
	// 			SELECT
	// 				headID,SUM(transactionDetail.amount) AS totalReceivables
	// 			FROM transactionDetail
	// 			WHERE headID IN (".$aryHeadIDs.") AND isDebit = 1
	// 			GROUP BY headID
	// 			UNION
	// 			SELECT
	// 				headID,(SUM(transactionDetail.amount) * -1) AS totalReceivables
	// 			FROM transactionDetail
	// 			WHERE headID IN (".$aryHeadIDs.") AND isDebit = 0
	// 			GROUP BY headID
	// 		) AS Temp
	// 		INNER JOIN accountHead ON accountHead.headID = Temp.headID
	// 		GROUP BY Temp.headID";
	// 	return DB::select($rawSQL);
	// }
}
