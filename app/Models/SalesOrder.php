<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

	public static function getSaleOrders() {
	    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$rawSQL = "
			SELECT temp.salesOrderID,temp.invoiceNumber,temp.bookSerial,temp.orderDate,temp.discount,temp.shippingCharges,temp.paymentDueDate,SUM(totalAmount) AS totalAmount,SUM(totalPaid) AS totalPaid,(SUM(totalAmount) - SUM(totalPaid)) AS remaining,customer.customerName, customer.shopName
			FROM (
				SELECT
					salesOrder.salesOrderID,
					salesOrder.customerID,
					salesOrder.invoiceNumber,
                    salesOrder.bookSerial,
					salesOrder.orderDate,
					salesOrder.discount,
					salesOrder.shippingCharges,
                    salesOrder.paymentDueDate,
					SUM(transactionDetail.amount) AS totalAmount,
					0 AS totalPaid
				FROM salesOrder
				INNER JOIN salesOrderTransaction ON salesOrderTransaction.salesOrderID = salesOrder.salesOrderID
				INNER JOIN transactionDetail ON transactionDetail.transactionID = salesOrderTransaction.transactionID
				WHERE transactionDetail.headID = " . \Config::get('constants.account_heads.sales') . "
				GROUP BY salesOrder.salesOrderID
				UNION
				SELECT
					salesOrder.salesOrderID,
					salesOrder.customerID,
					salesOrder.invoiceNumber,
                    salesOrder.bookSerial,
					salesOrder.orderDate,
					salesOrder.discount,
					salesOrder.shippingCharges,
                    salesOrder.paymentDueDate,
					0 AS totalAmount,
					SUM(transactionDetail.amount) AS totalPaid
				FROM salesOrder
				INNER JOIN salesOrderTransaction ON salesOrderTransaction.salesOrderID = salesOrder.salesOrderID
				INNER JOIN transactionDetail ON transactionDetail.transactionID = salesOrderTransaction.transactionID
				WHERE transactionDetail.headID = " . \Config::get('constants.account_heads.cash') . " AND transactionDetail.isDebit = 1
				GROUP BY salesOrder.salesOrderID
			) AS temp
			INNER JOIN customer ON customer.customerID = temp.customerID
			GROUP BY temp.salesOrderID,temp.invoiceNumber,temp.bookSerial,temp.orderDate,temp.discount,temp.shippingCharges,temp.paymentDueDate,customer.customerName,customer.shopName
			ORDER BY temp.salesOrderID DESC";

		return DB::select($rawSQL);
	}
}
