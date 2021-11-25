<?php

namespace App\Services;
use \Illuminate\Support\Facades\DB;

clASs ReportService {

	public static function getProfitLoss() {
        $rawSQL = "
            select SUM(expense) AS totalExpense, SUM(revenue) AS totalRevenue,(SUM(revenue) - SUM(expense)) AS profitLoss FROM (
                SELECT
                    SUM(
                        CASE
                            WHEN transactionDetail.isDebit = 1 THEN (transactionDetail.amount / `transaction`.exchangeRate)
                            ELSE (transactionDetail.amount / `transaction`.exchangeRate) * -1
                        END) AS expense, 0 AS revenue
                FROM `transaction`
                INNER JOIN transactionDetail on `transaction`.transactionID = transactionDetail.transactionID
                INNER JOIN accountHead on accountHead.headID = transactionDetail.headID
                WHERE accountHead.rootHeadID = " . \Config::get('constants.account_heads.expense') . " OR transactionDetail.headID = " . \Config::get('constants.account_heads.expense') . "
                UNION
                SELECT
                0 AS expense,
                    SUM(
                        CASE
                            WHEN transactionDetail.isDebit = 0 THEN (transactionDetail.amount / `transaction`.exchangeRate)
                            ELSE (transactionDetail.amount / `transaction`.exchangeRate) * -1
                        END) AS revenue
                FROM `transaction`
                INNER JOIN transactionDetail on `transaction`.transactionID = transactionDetail.transactionID
                INNER JOIN accountHead on accountHead.headID = transactionDetail.headID
                WHERE accountHead.rootHeadID = " . \Config::get('constants.account_heads.revenue') . " OR transactionDetail.headID = " . \Config::get('constants.account_heads.revenue') . "
            ) AS temp
		";
        return DB::select($rawSQL);
	}
}
