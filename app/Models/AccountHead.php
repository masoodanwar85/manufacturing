<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
// use Illuminate\Database\Eloquent\Scope;

class AccountHead extends Model
{
    use HasFactory;
    protected $table = 'accountHead';
    protected $primaryKey = 'headID';
	public $timestamps = false;
    protected $fillable = ['headID','parentHeadID','rootHeadID','headName','isSystemGenerated','isEditable','isShowForPurchaseOrderExpense','isShowForPayment','isShowForReceipt','isShowForOpeningBalance','isShowForBOMExpense','createdByUserID'];

	public function heads()
	{
		return $this->hasMany(AccountHead::class,'headID','parentHeadID');
	}

	public function childrenAccountHeads()
	{
		return $this->hasMany(AccountHead::class,'parentHeadID','headID')->with('heads');
	}

	public function parentHead()
	{
		return $this->belongsTo(AccountHead::class, 'parentHeadID', 'headID');
	}

    public function rootHead()
	{
		return $this->belongsTo(AccountHead::class, 'rootHeadID', 'headID');
	}

    public function transactionType()
    {
        return $this->belongsTo('App\Models\TransactionType','transactionTypeID','transactionTypeID');
    }

    public function transactionDetails()
    {
        return $this->hasMany('App\Models\TransactionDetail','headID','headID');
    }

	public function transactionDetailSubHeads()
    {
        return $this->hasMany('App\Models\TransactionDetail','headID','subHeadID');
    }

	public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier','headID','headID');
    }

	public function transport()
    {
        return $this->belongsTo('App\Models\Transport','headID','headID');
    }

	public function godown()
    {
        return $this->belongsTo('App\Models\Godown','headID','headID');
    }

	public function bankAccount()
    {
        return $this->belongsTo('App\Models\BankAccount','headID','headID');
    }

	public function staff()
    {
        return $this->belongsTo('App\Models\Staff','headID','headID');
    }

	public function customer()
    {
        return $this->belongsTo('App\Models\Customer','headID','headID');
    }

	public static function addAccountHead($headName,$createdByUserID,$parentHeadID = -1,$isSystemGenerated = 1,$isEditable = 0, $isShowForPurchaseOrderExpense = 0,$isShowForPayment = 0,$isShowForReceipt = 0,$isShowForOpeningBalance = 0)
	{
		$accountHead = new AccountHead;
		$accountHead->parentHeadID = $parentHeadID;
        $accountHead->rootHeadID = NULL;
        if ($parentHeadID != -1) {
            $rootAccountHead = new AccountHead;
            $rootHeadID = $rootAccountHead->find($parentHeadID)->rootHeadID;
            if ($rootHeadID != NULL) {
                $accountHead->rootHeadID = $rootHeadID;
            } else {
                $accountHead->rootHeadID = $parentHeadID;
            }
        }
		$accountHead->headName = $headName;
		$accountHead->isSystemGenerated = $isSystemGenerated;
		$accountHead->isEditable = $isEditable;
		$accountHead->isShowForPurchaseOrderExpense = $isShowForPurchaseOrderExpense;
		$accountHead->isShowForPayment = $isShowForPayment;
		$accountHead->isShowForReceipt = $isShowForReceipt;
		$accountHead->isShowForOpeningBalance = $isShowForOpeningBalance;
		$accountHead->createdByUserID = $createdByUserID;
		$accountHead->save();
		return $accountHead->headID;
	}

	public static function updateAccountHead($headID,$headName)
	{
		return DB::table('accountHead')->where('headID', $headID)->update(['headName' => $headName]);
	}

	public static function getAccountHeads($whereRaw = null) {
		if ($whereRaw == null) {
			return DB::table('accountHead')->orderBy('parentHeadID')->get();
		} else {
			return DB::table('accountHead')->whereRaw($whereRaw)->orderBy('parentHeadID')->get();
		}
	}

	public static function getAccountHeadHierarchy($whereRaw, $level = 0, $prevLevel = -1)
	{
		$parentHeads = self::getAccountHeads($whereRaw);
		$accountHeadsHierarchy = array();
		foreach ($parentHeads as $key => $value) {
			$accountHeadsHierarchy[] = array(
				'headID' => $value->headID,
				'headName' => $value->headName,
				'level' => $level,
				'children' => self::getAccountHeadHierarchy('parentHeadID = ' . $value->headID,++$level,--$level)
			);
		}
		return $accountHeadsHierarchy;
	}

    public static function buildTree($elements, $parentId = NULL) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element->parentHeadID == $parentId) {
                $children = self::buildTree($elements, $element->headID);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
	}

	public static function buildAccountHeadTree() {
        $elements = self::getAccountHeads('isSystemGenerated = 0');
        return AccountHead::buildTree($elements);
	}

    private static function getAccountHeadChild($accountHeads,$accountHeadID) {
        foreach($accountHeads as $aryHead) {
            if ($aryHead->headID == $accountHeadID) {
                return $aryHead->children;
            } elseif (isset($aryHead->children) && is_array($aryHead->children)) {
                $returned = self::getAccountHeadChild($aryHead->children,$accountHeadID);
                if ($returned !== NULL) {
                    return $returned;
                }
            }
        }
        return null;
    }

    public static function getAccountHeadChilds($accountHeadID) {
        return self::getAccountHeadChild(self::buildAccountHeadTree(),$accountHeadID);
    }

    public static function getAccountHeadChildIDs($accountHeads,$aryReturned = array()) {
        foreach($accountHeads as $aryHead) {
            array_push($aryReturned,$aryHead->headID);
            if (isset($aryHead->children) && is_array($aryHead->children)) {
                self::getAccountHeadChildIDs($aryHead->children,$aryReturned);
            }
        }
        return $aryReturned;
    }

    public static function getMonthlyReceivables($start_date = NULL,$end_date = NULL){
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $strWhere = "";
        if (Carbon::createFromFormat('Y-m-d', $start_date) !== false && Carbon::createFromFormat('Y-m-d', $end_date) !== false) {
            $strWhere .= " AND (`transaction`.transactionDate BETWEEN '${start_date}' AND '${end_date}')";
        }
        $sql = "
	        select concat_ws('-',month(customerAmount.transactionDate),year(customerAmount.transactionDate)) as transactionMonth, IFNULL(SUM(customerAmount.receivable ),0) AS receivables,IFNULL(SUM(customerAmount.received),0) AS received,(IFNULL(SUM(customerAmount.received),0) - IFNULL(SUM(customerAmount.receivable),0) ) as balance FROM (
                select
                    transaction.transactionDate,
                    SUM(transactionDetail.amount) AS receivable,
                    0 AS received
                FROM transactionDetail
                INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
                WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ")  $strWhere AND isDebit = 1
                group by transaction.transactionDate 
                UNION
                select
                    transaction.transactionDate,
                    0 AS receivable,
                    SUM(transactionDetail.amount) AS received
                FROM transactionDetail
                INNER JOIN `transaction` ON `transaction`.transactionID = transactionDetail.transactionID
                WHERE transactionDetail.headID IN (" . \Config::get('constants.account_heads.customer_receivable') . "," . \Config::get('constants.account_heads.customer_payable') . ") $strWhere AND isDebit = 0
                group by transaction.transactionDate 
            ) AS customerAmount
            group by concat_ws('-',month(customerAmount.transactionDate),year(customerAmount.transactionDate))
            order by customerAmount.transactionDate
	    ";
        return DB::select($sql);
    }

}
