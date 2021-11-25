<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $primaryKey = 'productID';
	protected $with = ['category'];
    public $timestamps = false;
    protected $fillable = ['productName','categoryID','unitsInProduct','isUnitsInProductFixed','unitPurchasePrice','unitSalePrice','maximumUnitID','minimumUnitID','thresholdUnit','isSoldPackOrLoose','image','createdByUserID'];

    public function category()
    {
        return $this->belongsTo('App\Models\Category','categoryID','categoryID');
    }

    public function maximumUnit()
    {
        return $this->belongsTo('App\Models\MeasurementUnit','maximumUnitID','unitID');
    }

	public function minimumUnit()
    {
        return $this->belongsTo('App\Models\MeasurementUnit','minimumUnitID','unitID');
    }

	public function purchaseOrderDetails()
    {
        return $this->hasMany('App\Models\PurchaseOrderDetail','productID','productID');
    }

	public function stockDetails()
    {
        return $this->hasMany('App\Models\StockDetail','productID','productID');
    }

	public static function getHistory($productID) {
		DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$rawSQL = "
			SELECT temp.* FROM (
				SELECT
					1 AS sort,
					NULL AS poID,
					NULL AS soID,
					NULL AS poSupplier,
					NULL AS poCustomer,
					NULL AS soCustomer,
					'OpeningStock' AS productTrack,
					SUM(stockDetailStatus.quantity) AS quantity,
					DATE_FORMAT(stockDetail.dateCreated,'%Y-%m-%d') AS dateCreated
				FROM stockDetail
				INNER JOIN stockDetailStatus on stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				WHERE stockDetail.productID = ${productID} AND stockDetailStatus.statusID = " . \Config::get('constants.stock_status.quetta_godown') . " AND stockDetail.purchaseOrderDetailID IS NULL
				GROUP BY stockDetail.dateCreated
				UNION
				SELECT
					2 AS sort,
					purchaseOrder.purchaseOrderID AS poID,
					NULL AS soID,
					supplier.supplierName AS poSupplier,
					customer.customerName AS poCustomer,
					NULL AS soCustomer,
					'Purchase' AS productTrack,
					SUM(stockDetailStatus.quantity) AS quantity,
					DATE_FORMAT(stockDetail.dateCreated,'%Y-%m-%d') AS dateCreated
				FROM stockDetail
				INNER JOIN stockDetailStatus on stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				INNER JOIN purchaseOrderDetail on purchaseOrderDetail.purchaseOrderDetailID = stockDetail.purchaseOrderDetailID
				INNER JOIN purchaseOrder on purchaseOrder.purchaseOrderID = purchaseOrderDetail.purchaseOrderID
				LEFT JOIN customer on customer.customerID = purchaseOrder.customerID
				LEFT JOIN supplier on supplier.supplierID = purchaseOrder.supplierID
				WHERE stockDetail.productID = ${productID} AND stockDetailStatus.statusID = " . \Config::get('constants.stock_status.quetta_godown') . " AND stockDetail.purchaseOrderDetailID IS NOT NULL
				GROUP BY purchaseOrder.purchaseOrderID,stockDetail.dateCreated
				UNION
				SELECT
					3 AS sort,
					NULL AS poID,
					salesOrder.salesOrderID as soID,
					NULL AS poSupplier,
					NULL AS poCustomer,
					customer.customerName AS soCustomer,
					'Sold' as productTrack,
					SUM(stockDetailStatus.quantity) AS quantity,
					DATE_FORMAT(stockDetail.dateCreated,'%Y-%m-%d') AS dateCreated
				FROM stockDetail
				INNER JOIN stockDetailStatus on stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				INNER JOIN salesOrderDetail on salesOrderDetail.stockDetailStatusID = stockDetailStatus.stockDetailStatusID
				INNER JOIN salesOrder on salesOrder.salesOrderID = salesOrderDetail.salesOrderID
				INNER JOIN customer on customer.customerID = salesOrder.customerID
				WHERE stockDetail.productID = ${productID} AND stockDetailStatus.statusID = " . \Config::get('constants.stock_status.sold') . "
				GROUP BY stockDetail.dateCreated,salesOrder.salesOrderID
			) AS temp
			order by dateCreated,sort
		";
		return DB::select($rawSQL);
	}
}
