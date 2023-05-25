<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transport extends Model
{
    use HasFactory;
    protected $table = 'transport';
    protected $primaryKey = 'transportID';
	protected $with = ['head'];
    public $timestamps = false;
    protected $fillable = ['transportID','headID','name','owner','vehicleNumber','description','createdByUserID'];

    public function head()
    {
        return $this->hasOne('App\Models\AccountHead','headID','headID');
    }
    public function deliveries()
    {
        return $this->hasMany('App\Models\Delivery','transportID','transportID');
    }

	public static function getBalance($transportID,$isHeadID = FALSE) {
		$transportHeadID = $transportID;
		if ($isHeadID == FALSE) {
			$transportHeadID = Transport::find($transportID)->headID;
		}
		// $parentAccountHead = \App\Models\AccountHead::whereRaw('headID IN (SELECT parentHeadID from accountHead WHERE headID = ' . $request->headID[$i] . ')')->first()->headID;
		$rawSQL = "
			SELECT IFNULL(SUM(transportAmount.amount),0) AS totalPayable FROM (
				SELECT
					(transactionDetail.amount * -1) " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID = " . \Config::get('constants.account_heads.accounts_payable') . " AND subHeadID = ${transportHeadID} and isDebit = 1
				UNION
				SELECT
					(transactionDetail.amount " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate) AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID = " . \Config::get('constants.account_heads.accounts_payable') . " AND subHeadID = ${transportHeadID} and isDebit = 0
			) AS transportAmount
		";
		
		return DB::select($rawSQL);
	}
}
