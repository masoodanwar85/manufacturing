<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $primaryKey = 'supplierID';
	protected $with = ['head'];
    public $timestamps = false;
    protected $fillable = ['headID','supplierName','phone','address','description','createdByUserID'];

	public function head()
    {
        return $this->hasOne('App\Models\AccountHead','headID','headID');
    }

	public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder','supplierID','supplierID');
    }

	public static function getBalance($supplierID = 0) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		if ($supplierID > 0) {
			$supplierHeadID = Supplier::find($supplierID)->headID;
		}
		$rawSQL = "
			SELECT IFNULL(SUM(supplierAmount.amount),0) AS totalPayable FROM (
				SELECT
                    SUM(transactionDetail.amount * -1) " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.supplier_payable') . "," . \Config::get('constants.account_heads.supplier_receivable') . ") " . ($supplierID > 0 ? "AND subHeadID = " . $supplierHeadID : "") . " AND isDebit = 0
				UNION
				SELECT
                    SUM(transactionDetail.amount " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate) AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.supplier_payable') . "," . \Config::get('constants.account_heads.supplier_receivable') . ") " . ($supplierID > 0 ? "AND subHeadID = " . $supplierHeadID : "") . " AND isDebit = 1
			) AS supplierAmount
		";
		return DB::select($rawSQL);
	}

}
