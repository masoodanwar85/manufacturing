<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;

class SalesOrder extends Model
{
    use HasFactory;
    protected $table = 'salesOrder';
    protected $primaryKey = 'salesOrderID';
	protected $with = ['customer','salesOrderDetails'];
    public $timestamps = false;
    protected $fillable = ['salesOrderID','customerID','salesAgentID','invoiceNumber','bookSerial','orderDate','discount','shippingCharges','paymentDueDate','description','createdByUserID'];

	public function customer()
	{
		return $this->belongsTo('App\Models\Customer','customerID','customerID');
	}

    public function salesAgent()
	{
		return $this->belongsTo('App\Models\Staff','salesAgentID','staffID');
	}

	public function salesOrderDetails()
	{
		return $this->hasMany('App\Models\SalesOrderDetail','salesOrderID','salesOrderID');
	}

	public function salesOrderTransactions()
	{
		return $this->hasMany('App\Models\SalesOrderTransaction','salesOrderID','salesOrderID');
	}

	public function transactions()
	{
		return $this->belongsToMany('App\Models\Transaction','salesOrderTransaction','salesOrderID','transactionID');
	}

	public function stockDetailStatuses()
	{
		return $this->belongsToMany('App\Models\StockDetailStatus','salesOrderDetail','salesOrderID','stockDetailStatusID');
	}

	public static function getSaleOrders($salesOrderID = 0,$filters = []) {
	    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $strWhere = "";
        if ($salesOrderID > 0) {
            $strWhere .= " AND temp.salesOrderID = " . $salesOrderID;
        }
        if (isset($filters['salesAgentID']) && $filters['salesAgentID'] > 0) {
            $strWhere .= " AND temp.salesAgentID = " . $filters['salesAgentID'];
        }
        if (isset($filters['fromDate']) && isset($filters['toDate']) && Carbon::createFromFormat('Y-m-d',$filters['fromDate']) !== false && Carbon::createFromFormat('Y-m-d',$filters['toDate']) !== false) {
            $strWhere .= " AND (temp.orderDate BETWEEN '" . $filters['fromDate'] . "' AND '" . $filters['toDate'] . "')";
        }
        if (isset($filters['customerID']) && $filters['customerID'] > 0) {
            $strWhere .= " AND temp.customerID = " . $filters['customerID'];
        }
        $rawSQL = "
			SELECT temp.salesOrderID,temp.invoiceNumber,temp.bookSerial,temp.orderDate,temp.discount,temp.shippingCharges,temp.paymentDueDate,(SUM(totalAmount) - SUM(salesReturnAmount)) AS totalAmount,SUM(totalPaid) AS totalPaid,(SUM(totalAmount) - SUM(salesReturnAmount) - SUM(totalPaid)) AS remaining,customer.customerID,customer.customerName,customer.shopName
			FROM (
				SELECT
					salesOrder.salesOrderID,
					salesOrder.customerID,
                    salesOrder.salesAgentID,
					salesOrder.invoiceNumber,
                    salesOrder.bookSerial,
					salesOrder.orderDate,
					salesOrder.discount,
					salesOrder.shippingCharges,
                    salesOrder.paymentDueDate,
					SUM(transactionDetail.amount) AS totalAmount,
					0 AS totalPaid,
                    0 AS salesReturnAmount
				FROM salesOrder
				INNER JOIN salesOrderTransaction ON salesOrderTransaction.salesOrderID = salesOrder.salesOrderID
				INNER JOIN transactionDetail ON transactionDetail.transactionID = salesOrderTransaction.transactionID
				WHERE transactionDetail.headID = " . \Config::get('constants.account_heads.sales') . "
				GROUP BY salesOrder.salesOrderID
				UNION
				SELECT
					salesOrder.salesOrderID,
					salesOrder.customerID,
                    salesOrder.salesAgentID,
					salesOrder.invoiceNumber,
                    salesOrder.bookSerial,
					salesOrder.orderDate,
					salesOrder.discount,
					salesOrder.shippingCharges,
                    salesOrder.paymentDueDate,
					0 AS totalAmount,
					SUM(transactionDetail.amount) AS totalPaid,
                    0 AS salesReturnAmount
				FROM salesOrder
				INNER JOIN salesOrderTransaction ON salesOrderTransaction.salesOrderID = salesOrder.salesOrderID
				INNER JOIN transactionDetail ON transactionDetail.transactionID = salesOrderTransaction.transactionID
				WHERE transactionDetail.headID = " . \Config::get('constants.account_heads.cash') . " AND transactionDetail.isDebit = 1
				GROUP BY salesOrder.salesOrderID
                UNION
                SELECT
					salesOrder.salesOrderID,
					salesOrder.customerID,
                    salesOrder.salesAgentID,
					salesOrder.invoiceNumber,
                    salesOrder.bookSerial,
					salesOrder.orderDate,
					salesOrder.discount,
					salesOrder.shippingCharges,
                    salesOrder.paymentDueDate,
					0 AS totalAmount,
					0 AS totalPaid,
                    SUM(stockDetailStatus.quantity * stockDetailStatus.salePrice) AS salesReturnAmount
				FROM salesOrder
                INNER JOIN salesOrderDetail ON salesOrderDetail.salesOrderID = salesOrder.salesOrderID
                INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailStatusID = salesOrderDetail.stockDetailStatusID
                WHERE stockDetailStatus.statusID in (" . \Config::get('constants.stock_status.isReturn') . ")
				GROUP BY salesOrder.salesOrderID
			) AS temp
			INNER JOIN customer ON customer.customerID = temp.customerID
            WHERE 1=1 " . $strWhere . "
			GROUP BY temp.salesOrderID,temp.invoiceNumber,temp.bookSerial,temp.orderDate,temp.discount,temp.shippingCharges,temp.paymentDueDate,customer.customerID,customer.customerName,customer.shopName
			ORDER BY temp.salesOrderID DESC";
		return DB::select($rawSQL);
	}

    public static function getSaleReturns($salesOrderID = 0,$filters = []) {
	    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $strWhere = "";
        if ($salesOrderID > 0) {
            $strWhere .= " AND salesOrder.salesOrderID = " . $salesOrderID;
        }
        if (isset($filters['salesAgentID']) && $filters['salesAgentID'] > 0) {
            $strWhere .= " AND salesOrder.salesAgentID = " . $filters['salesAgentID'];
        }
        if (isset($filters['fromDate']) && isset($filters['toDate']) && Carbon::createFromFormat('Y-m-d',$filters['fromDate']) !== false && Carbon::createFromFormat('Y-m-d',$filters['toDate']) !== false) {
            $strWhere .= " AND (salesOrder.orderDate BETWEEN '" . $filters['fromDate'] . "' AND '" . $filters['toDate'] . "')";
        }
        if (isset($filters['customerID']) && $filters['customerID'] > 0) {
            $strWhere .= " AND salesOrder.customerID = " . $filters['customerID'];
        }
        $rawSQL = "
			SELECT
                salesOrder.salesOrderID,
                salesOrder.customerID,
                customer.customerName,
                customer.shopName,
                salesOrder.salesAgentID,
                staff.staffName AS salesAgent,
                salesOrder.invoiceNumber,
                salesOrder.bookSerial,
                salesOrder.orderDate,
                salesOrder.paymentDueDate,
                SUM(stockDetailStatus.quantity * stockDetailStatus.salePrice) AS salesReturnAmount
            FROM salesOrder
            INNER JOIN salesOrderDetail ON salesOrderDetail.salesOrderID = salesOrder.salesOrderID
            INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailStatusID = salesOrderDetail.stockDetailStatusID
            INNER JOIN customer ON customer.customerID = salesOrder.customerID
            LEFT JOIN staff ON staff.staffID = salesOrder.salesAgentID
            WHERE stockDetailStatus.statusID in (2,4) " . $strWhere . "
            GROUP BY salesOrder.salesOrderID
			ORDER BY salesOrder.salesOrderID DESC";
		return DB::select($rawSQL);
	}
}
