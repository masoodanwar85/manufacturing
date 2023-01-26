<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $primaryKey = 'customerID';
	protected $with = ['head'];
    public $timestamps = false;
    protected $fillable = ['customerID','customerName','headID','salesAgentID','shopName','phone','address','description','createdByUserID'];

	public function head()
    {
        return $this->hasOne('App\Models\AccountHead','headID','headID');
    }

    public function salesAgent()
    {
        return $this->hasOne('App\Models\Staff','staffID','salesAgentID');
    }

	public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder','customerID','customerID');
    }

	public function salesOrders()
	{
		return $this->hasMany('App\Models\SalesOrder','customerID','customerID');
	}

	public static function getReceivablesReceived($customerID = NULL,$start_date = NULL,$end_date = NULL)
	{
		DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$strWhere = "";
        if ($customerID != NULL) {
            $customerHeadID = Customer::find($customerID)->headID;
			$strWhere .= " AND transactionDetail.subHeadID = ${customerHeadID} ";
        }
		if (Carbon::createFromFormat('Y-m-d', $start_date) !== false && Carbon::createFromFormat('Y-m-d', $end_date) !== false) {
			$strWhere .= " AND (`transaction`.transactionDate BETWEEN '${start_date}' AND '${end_date}')";
		}
		$rawSQL = "
			SELECT IFNULL(SUM(customerAmount.receivable),0) AS receivables,IFNULL(SUM(customerAmount.received),0) AS received FROM (
				SELECT
					SUM(transactionDetail.amount) AS receivable,
					0 AS received
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ") " . $strWhere . " AND isDebit = 1
				UNION
				SELECT
					0 AS receivable,
					SUM(transactionDetail.amount) AS received
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ") " . $strWhere . " AND isDebit = 0
			) AS customerAmount
		";
        return DB::select($rawSQL);
	}

	public static function getBalance($customerID = 0) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        if ($customerID != 0) {
            $customerHeadID = Customer::find($customerID)->headID;
        }
		$rawSQL = "
			SELECT IFNULL(SUM(customerAmount.amount),0) AS totalPayable FROM (
				SELECT				
					SUM(transactionDetail.amount) AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ") " . ($customerID != 0 ? "AND subHeadID = " . $customerHeadID : "") . " AND isDebit = 1
				UNION
				SELECT				
					SUM(transactionDetail.amount * -1) AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ") " . ($customerID != 0 ? "AND subHeadID = " . $customerHeadID : "") . " AND isDebit = 0
			) AS customerAmount
		";
        return DB::select($rawSQL);
	}

	public static function getMonthlyDefaulters(){
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
	    $rawSQL = "
                SELECT
                    customer.customerName,
                    tdOuter.subHeadID,
                    SUM(CASE WHEN tdOuter.isDebit = 0 THEN CAST(tdOuter.amount AS SIGNED) * -1
                    ELSE tdOuter.amount END) AS transactionAmount,
                    (
                        SELECT transaction.dateCreated 
                        FROM transactionDetail
                        INNER JOIN transaction ON transaction.transactionID = transactionDetail.transactionID
                        WHERE headID IN (" . \Config::get('constants.account_heads.customer_receivable') . ") AND isDebit = 0 AND subHeadID = tdOuter.subHeadID
                        ORDER BY transaction.dateCreated DESC
                        LIMIT 0,1
                    ) AS lastReceivingDate
                FROM customer
                INNER JOIN transactionDetail tdOuter ON tdOuter.subHeadID = customer.headID AND tdOuter.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ")
                INNER JOIN transaction ON transaction.transactionID = tdOuter.transactionID 
                GROUP BY customer.customerName,tdOuter.subHeadID
                HAVING transactionAmount > 0 AND ((DATE_FORMAT(lastReceivingDate,'%Y-%m-01') < DATE_FORMAT(NOW(),'%Y-%m-01')) OR lastReceivingDate IS NULL)
	    ";
        return DB::select($rawSQL);
    }
}
