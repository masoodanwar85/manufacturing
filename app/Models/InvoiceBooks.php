<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvoiceBooks extends Model
{
    use HasFactory;
    protected $table = 'invoiceBooks';
    protected $primaryKey = 'invoiceBookID';
	public $timestamps = false;
    protected $fillable = ['invoiceBookID','bookType','bookNumber','startPage','endPage','createdByUserID'];

    public function serials()
    {
        return $this->hasMany('App\Models\BookSerials','invoiceBookID','invoiceBookID');
    }

    public static function getInvoiceBooksSerialNumbers($params = []) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $strWhere = "";
        if (isset($params['invoiceBookID']) && is_numeric($params['invoiceBookID'])) {
            $strWhere .= " AND invoiceBooks.invoiceBookID = " . $params['invoiceBookID'] ;
        }
        if (isset($params['bookType']) && is_numeric($params['bookType'])) {
            $strWhere .= " AND invoiceBooks.bookType = " . $params['bookType'] ;
        }
        $rawSQL = "
            SELECT
                serialNumber.id as sequenceNumber, CONCAT_WS('-',invoiceBooks.bookType,invoiceBooks.bookNumber,serialNumber.id) as serial, invoiceBooks.*
            FROM serialNumber
            LEFT JOIN invoiceBooks on (invoiceBooks.startPage = serialNumber.id or invoiceBooks.startpage <= serialNumber.id) and (invoiceBooks.endPage = serialNumber.id or invoiceBooks.endPage >= serialNumber.id)
            WHERE 1=1
		";
        $rawSQL .= $strWhere;
        return DB::select($rawSQL);
	}

    public static function getTransactionTypeNumbersRemovedLeadingZeros() {
        $rawSQL = "
            SELECT
                transactionTypeNumber,
                CONCAT_WS('-',SUBSTRING_INDEX(transactionTypeNumber,'-',2),TRIM(leading '0' from SUBSTRING_INDEX(transactionTypeNumber,'-',-1))) as removedZeros
            FROM transaction
        ";
        return DB::select($rawSQL);
    }
}
