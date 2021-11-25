<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Godown extends Model
{
    use HasFactory;
    protected $table = 'godown';
    protected $primaryKey = 'godownID';
	protected $with = ['head'];
    public $timestamps = false;
    protected $fillable = ['godownID','headID','name','address','description','createdByUserID'];

    public function head()
    {
        return $this->hasOne('App\Models\AccountHead','headID','headID');
    }

    public function purchaseOrders() {
        return $this->hasMany('App\Models\PurchaseOrder','lastGodownID','godownID');
    }

	public function stockDetails()
    {
        return $this->hasMany('App\Models\StockDetail','godownID','godownID');
    }

    public function stockDetailStatuses()
    {
        return $this->hasMany('App\Models\StockDetailStatus','godownID','godownID');
    }

	public static function getBalance($godownID,$isHeadID = FALSE) {
		$godownHeadID = $godownID;
		if ($isHeadID == FALSE) {
			$godownHeadID = Godown::find($godownID)->headID;
		}

		// $parentAccountHead = \App\Models\AccountHead::whereRaw('headID IN (SELECT parentHeadID from accountHead WHERE headID = ' . $request->headID[$i] . ')')->first()->headID;
		$rawSQL = "
			SELECT IFNULL(SUM(godownAmount.amount),0) AS totalPayable FROM (
				SELECT
					(transactionDetail.amount * -1) " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID = " . \Config::get('constants.account_heads.accounts_payable') . " AND subHeadID = ${godownHeadID} and isDebit = 1
				UNION
				SELECT
					(transactionDetail.amount " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate) AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID = " . \Config::get('constants.account_heads.accounts_payable') . " AND subHeadID = ${godownHeadID} and isDebit = 0
			) AS godownAmount
		";

		return DB::select($rawSQL);
	}
}
