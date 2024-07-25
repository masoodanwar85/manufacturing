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
    protected $fillable = ['invoiceBookID', 'bookType', 'bookNumber', 'startPage', 'endPage', 'createdByUserID'];

    public function serials()
    {
        return $this->hasMany('App\Models\BookSerials', 'invoiceBookID', 'invoiceBookID');
    }

    public static function getInvoiceBooksSerialNumbers($params = [])
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $strWhere = "";
        if (isset($params['invoiceBookID']) && is_numeric($params['invoiceBookID'])) {
            $strWhere .= " AND invoiceBooks.invoiceBookID = " . $params['invoiceBookID'];
        }
        if (isset($params['bookType']) && is_numeric($params['bookType'])) {
            $strWhere .= " AND invoiceBooks.bookType = " . $params['bookType'];
        }
        $rawSQL = "
            SELECT serialNumber.id as sequenceNumber, CONCAT_WS('-',invoiceBooks.bookType,invoiceBooks.bookNumber,serialNumber.id) as serial, invoiceBooks.*
            FROM serialNumber
            LEFT JOIN invoiceBooks ON (invoiceBooks.startPage = serialNumber.id OR invoiceBooks.startpage <= serialNumber.id) AND (invoiceBooks.endPage = serialNumber.id OR invoiceBooks.endPage >= serialNumber.id)
            WHERE invoiceBooks.bookNumber IS NOT NULL
		";
        $rawSQL .= $strWhere;
        return DB::select($rawSQL);
    }

    public static function getTransactionTypeNumbersRemovedLeadingZeros()
    {
        $rawSQL = "
            SELECT
                transactionTypeNumber,
                CONCAT_WS('-',SUBSTRING_INDEX(transactionTypeNumber,'-',2),TRIM(leading '0' from SUBSTRING_INDEX(transactionTypeNumber,'-',-1))) as removedZeros
            FROM transaction
        ";
        return DB::select($rawSQL);
    }

    public static function getInvoiceBooksMissingSerialNumbers($params = [])
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $strWhere = "";
        if (isset($params['invoiceBookID']) && is_numeric($params['invoiceBookID'])) {
            $strWhere .= " AND invoiceBooks.invoiceBookID = " . $params['invoiceBookID'];
        }
        if (isset($params['bookType']) && strlen($params['bookType'])) {
            $strWhere .= " AND invoiceBooks.bookType = '" . $params['bookType'] . "'";
        }
        if (in_array($params['bookType'],['TB','SR'])) {
            $rawSQL = "
                SELECT serialNumber.id as sequenceNumber, CONCAT_WS('-',invoiceBooks.bookType,invoiceBooks.bookNumber,serialNumber.id) as serial,bookSerials.bookSerialID,invoiceBooks.invoiceBookID,invoiceBooks.bookType,invoiceBooks.bookNumber,invoiceBooks.startPage,invoiceBooks.endPage
                FROM serialNumber
                LEFT JOIN invoiceBooks ON (invoiceBooks.startPage = serialNumber.id OR invoiceBooks.startpage <= serialNumber.id) AND (invoiceBooks.endPage = serialNumber.id OR invoiceBooks.endPage >= serialNumber.id)
                LEFT JOIN bookSerials ON bookSerials.invoiceBookID = invoiceBooks.invoiceBookID AND bookSerials.serialNumber = serialNumber.id
                LEFT JOIN stockDetailStatus ON CONCAT_WS('-',SUBSTRING_INDEX(bookSerial,'-',2),TRIM(leading '0' from SUBSTRING_INDEX(bookSerial,'-',-1))) = CONCAT_WS('-', invoiceBooks.bookType, invoiceBooks.bookNumber, serialNumber.id)
                WHERE invoiceBooks.bookNumber IS NOT NULL AND stockDetailStatus.stockDetailStatusID IS NULL AND bookSerials.bookSerialID IS NULL
            ";
        } elseif ($params['bookType'] == 'MB') {
            $rawSQL = "
                SELECT serialNumber.id as sequenceNumber, CONCAT_WS('-',invoiceBooks.bookType,invoiceBooks.bookNumber,serialNumber.id) as serial,bookSerials.bookSerialID,invoiceBooks.invoiceBookID,invoiceBooks.bookType,invoiceBooks.bookNumber,invoiceBooks.startPage,invoiceBooks.endPage
                FROM serialNumber
                LEFT JOIN invoiceBooks ON (invoiceBooks.startPage = serialNumber.id OR invoiceBooks.startpage <= serialNumber.id) AND (invoiceBooks.endPage = serialNumber.id OR invoiceBooks.endPage >= serialNumber.id)
                LEFT JOIN bookSerials ON bookSerials.invoiceBookID = invoiceBooks.invoiceBookID AND bookSerials.serialNumber = serialNumber.id
                LEFT JOIN production ON CONCAT_WS('-',SUBSTRING_INDEX(serial,'-',2),TRIM(leading '0' from SUBSTRING_INDEX(serial,'-',-1))) = CONCAT_WS('-', invoiceBooks.bookType, invoiceBooks.bookNumber, serialNumber.id)
                WHERE invoiceBooks.bookNumber IS NOT NULL AND production.productionID IS NULL AND bookSerials.bookSerialID IS NULL
            ";
        } else {
            $rawSQL = "
                SELECT serialNumber.id as sequenceNumber, CONCAT_WS('-',invoiceBooks.bookType,invoiceBooks.bookNumber,serialNumber.id) as serial,bookSerials.bookSerialID,invoiceBooks.invoiceBookID,invoiceBooks.bookType,invoiceBooks.bookNumber,invoiceBooks.startPage,invoiceBooks.endPage
                FROM serialNumber
                INNER JOIN invoiceBooks ON invoiceBooks.startpage <= serialNumber.id AND invoiceBooks.endPage >= serialNumber.id
                -- LEFT JOIN invoiceBooks ON (invoiceBooks.startPage = serialNumber.id OR invoiceBooks.startpage <= serialNumber.id) AND (invoiceBooks.endPage = serialNumber.id OR invoiceBooks.endPage >= serialNumber.id)
                LEFT JOIN bookSerials ON bookSerials.invoiceBookID = invoiceBooks.invoiceBookID AND bookSerials.serialNumber = serialNumber.id
                LEFT JOIN `transaction` ON CONCAT_WS('-',SUBSTRING_INDEX(transactionTypeNumber,'-',2),TRIM(leading '0' from SUBSTRING_INDEX(transactionTypeNumber,'-',-1))) = CONCAT_WS('-', invoiceBooks.bookType, invoiceBooks.bookNumber, serialNumber.id) AND `transaction`.transactionTypeNumber LIKE '" . $params['bookType'] . "-%'
                WHERE invoiceBooks.bookNumber IS NOT NULL AND `transaction`.transactionID IS NULL AND bookSerials.bookSerialID IS NULL
            ";
        }
        
        $rawSQL .= $strWhere;
        $rawSQL .= " ORDER BY invoiceBooks.bookType,invoiceBooks.bookNumber,sequenceNumber,serial";
        if (isset($params['nextSerial'])) {
            $rawSQL .= " LIMIT 0,1";
        }
        return DB::select($rawSQL);
    }
}
