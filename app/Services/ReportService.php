<?php

namespace App\Services;
use Carbon\Carbon;
use \Illuminate\Support\Facades\DB;

clASs ReportService {

	public static function getProfitLoss($start_date = NULL, $end_date = NULL) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $strWhere = "";
        if ($start_date != NULL && $end_date != NULL) {
            $strWhere .= " AND (`transaction`.transactionDate BETWEEN '${start_date}' AND '${end_date}')";
        }
        $rawSQL = "
            select SUM(expense) AS totalExpense, SUM(revenue) AS totalRevenue,(SUM(revenue) - SUM(expense)) AS profitLoss FROM (
                SELECT
                    transaction.transactionDate,
                    SUM(
                        CASE
                            WHEN transactionDetail.isDebit = 1 THEN (transactionDetail.amount / `transaction`.exchangeRate)
                            ELSE (transactionDetail.amount / `transaction`.exchangeRate) * -1
                        END) AS expense, 0 AS revenue
                FROM `transaction`
                INNER JOIN transactionDetail on `transaction`.transactionID = transactionDetail.transactionID
                INNER JOIN accountHead on accountHead.headID = transactionDetail.headID
                WHERE (accountHead.rootHeadID = " . \Config::get('constants.account_heads.expense') . " OR transactionDetail.headID = " . \Config::get('constants.account_heads.expense') . ") $strWhere
                UNION
                SELECT
                    transaction.transactionDate,
                    0 AS expense,
                    SUM(
                        CASE
                            WHEN transactionDetail.isDebit = 0 THEN (transactionDetail.amount / `transaction`.exchangeRate)
                            ELSE (transactionDetail.amount / `transaction`.exchangeRate) * -1
                        END) AS revenue
                FROM `transaction`
                INNER JOIN transactionDetail on `transaction`.transactionID = transactionDetail.transactionID
                INNER JOIN accountHead on accountHead.headID = transactionDetail.headID
                WHERE (accountHead.rootHeadID = " . \Config::get('constants.account_heads.revenue') . " OR transactionDetail.headID = " . \Config::get('constants.account_heads.revenue') . ") $strWhere
            ) AS temp
		";
        return DB::select($rawSQL);
	}
}
