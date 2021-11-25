<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Staff extends Model
{
    use HasFactory;
    protected $table = 'staff';
    protected $primaryKey = 'staffID';
	protected $with = ['staffType','head'];
    public $timestamps = false;
    protected $fillable = ['staffID','staffTypeID','paymentFrequencyID','paymentAmount','headID','staffName','dateJoined','createdByUserID'];

    public function staffType()
    {
        return $this->belongsTo('App\Models\StaffType','staffTypeID','staffTypeID');
    }

    public function head()
    {
        return $this->hasOne('App\Models\AccountHead','headID','headID');
    }

    public static function getBalance($staffID = 0) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		if ($staffID > 0) {
			$staffHeadID = Staff::find($staffID)->headID;
		}
		$rawSQL = "
			SELECT IFNULL(SUM(staffAmount.amount),0) AS totalPayable FROM (
				SELECT
					SUM(transactionDetail.amount " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate) AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.staff_receivable') . "," . \Config::get('constants.account_heads.salaries_payable') . "," . \Config::get('constants.account_heads.staff_payable') . ") " . ($staffID > 0 ? "AND subHeadID = " . $staffHeadID : "") . " AND isDebit = 1
				UNION
				SELECT
					SUM(transactionDetail.amount * -1) " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " `transaction`.exchangeRate AS amount
				FROM transactionDetail
				INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
				WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.staff_receivable') . "," . \Config::get('constants.account_heads.salaries_payable') . "," . \Config::get('constants.account_heads.staff_payable') . ") " . ($staffID > 0 ? "AND subHeadID = " . $staffHeadID : "") . " AND isDebit = 0
			) AS staffAmount
		";
        return DB::select($rawSQL);
	}
}
