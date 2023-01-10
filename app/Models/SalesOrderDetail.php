<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesOrderDetail extends Model
{
    use HasFactory;
    protected $table = 'salesOrderDetail';
    protected $primaryKey = 'salesOrderDetailID';
    public $timestamps = false;
    protected $fillable = ['salesOrderDetailID','salesOrderID','stockDetailStatusID'];

	public function salesOrder()
	{
		return $this->belongsTo('App\Models\SalesOrder','salesOrderID','salesOrderID');
	}

	public function stockDetailStatus()
	{
		return $this->belongsTo('App\Models\StockDetailStatus','stockDetailStatusID','stockDetailStatusID');
	}

	public static function getSaleOrderDetails($salesOrderID = 0,$filters = []) {
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
			SELECT
				temp.salesOrderID,
				temp.productID,
				product.productName,
				category.categoryName,
				temp.salePrice,
				SUM(temp.qty) AS totalQty,
				SUM(temp.returnQty) AS totalReturnQty,
				SUM(temp.totalPrice) AS totalPrice,
				SUM(temp.totalDiscount) AS totalDiscount
			FROM (
				SELECT
					salesOrder.salesOrderID,
					stockDetail.productID,
					stockDetailStatus.salePrice,
					SUM(stockDetailStatus.quantity) AS soldQty,
					0 AS returnQty,
					SUM(stockDetailStatus.quantity) AS qty,
					SUM(stockDetailStatus.salePrice * stockDetailStatus.quantity) AS totalPrice,
					SUM(stockDetailStatus.discount * stockDetailStatus.quantity) as totalDiscount
				FROM salesOrder
				INNER JOIN salesOrderDetail ON salesOrderDetail.salesOrderID = salesOrder.salesOrderID
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailStatusID = salesOrderDetail.stockDetailStatusID
				INNER JOIN stockDetail ON stockDetail.stockDetailID = stockdetailstatus.stockDetailID
				WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.sold') . " AND salesOrder.salesOrderID = " . $salesOrderID . "
				GROUP BY salesOrder.salesOrderID,stockDetail.productID
				UNION
				SELECT
					salesOrder.salesOrderID,
					stockDetail.productID,
					stockDetailStatus.salePrice,
					0 AS soldQty,
					SUM(stockDetailStatus.quantity) AS returnQty,
					SUM(stockDetailStatus.quantity * -1) AS qty,
					SUM(stockDetailStatus.salePrice * stockDetailStatus.quantity) * -1 AS totalPrice,
					SUM(stockDetailStatus.discount * stockDetailStatus.quantity) * -1 as totalDiscount
				FROM salesOrder
				INNER JOIN salesOrderDetail ON salesOrderDetail.salesOrderID = salesOrder.salesOrderID
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailStatusID = salesOrderDetail.stockDetailStatusID
				INNER JOIN stockDetail ON stockDetail.stockDetailID = stockdetailstatus.stockDetailID
				WHERE stockDetailStatus.statusID in (" . \Config::get('constants.stock_status.isReturn') . ") AND salesOrder.salesOrderID = " . $salesOrderID . "
				GROUP BY salesOrder.salesOrderID,stockDetailStatus.salePrice,stockDetail.productID
			) AS temp
			INNER JOIN product ON product.productID = temp.productID
			INNER JOIN category ON product.categoryID = category.categoryID
			GROUP BY temp.salesOrderID,temp.productID,product.productName,category.categoryName,temp.salePrice";
			// dd($rawSQL);
		return DB::select($rawSQL);
	}
}
