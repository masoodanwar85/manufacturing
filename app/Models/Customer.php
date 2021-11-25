<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $primaryKey = 'customerID';
	protected $with = ['head'];
    public $timestamps = false;
    protected $fillable = ['customerID','customerName','headID','shopName','phone','address','description','createdByUserID'];

	public function head()
    {
        return $this->hasOne('App\Models\AccountHead','headID','headID');
    }

	public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder','customerID','customerID');
    }

	public function salesOrders()
	{
		return $this->hasMany('App\Models\SalesOrder','customerID','customerID');
	}

	public static function getBalance($customerID = 0) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        if ($customerID != 0) {
            $customerHeadID = Customer::find($customerID)->headID;
        }
		$rawSQL = "
			SELECT IFNULL(SUM(customerAmount.amount),0) AS totalPayable FROM (
				SELECT
					SUM(transactionDetail.amount " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate) AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ") " . ($customerID != 0 ? "AND subHeadID = " . $customerHeadID : "") . " AND isDebit = 1
				UNION
				SELECT
					SUM(transactionDetail.amount * -1) " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ") " . ($customerID != 0 ? "AND subHeadID = " . $customerHeadID : "") . " AND isDebit = 0
			) AS customerAmount
		";
        return DB::select($rawSQL);
	}
}
