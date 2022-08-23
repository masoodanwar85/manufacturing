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
    protected $fillable = ['productName','categoryID','unitsInProduct','isUnitsInProductFixed','unitPurchasePrice','unitSalePrice','isBOM','maximumUnitID','minimumUnitID','thresholdUnit','isSoldPackOrLoose','image','createdByUserID'];

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

    public function BOM() {
        return $this->hasOne('App\Models\ProductBOM','productID','productID');
    }

    public function BOMProducts() {
        return $this->hasMany('App\Models\ProductBOM','productID','productID');
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

    public static function getSalesAgentDaySummary($salesAgentID, $orderDate) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $rawSQL = "
            select
                salesOrder.salesOrderID,
                salesOrder.customerID,
                customer.shopName,
                salesOrder.orderDate,
                stockDetail.productID,
                product.productName,
                SUM(stockDetailStatus.quantity) as quantity,
                (SUM((stockDetailStatus.quantity * stockDetailStatus.saleprice)) - salesOrder.discount) as total
            from salesOrder
            inner join customer on customer.customerID = salesOrder.customerID
            inner join salesOrderDetail on salesOrder.salesOrderID = salesOrderDetail.salesOrderID
            inner join stockDetailStatus on stockDetailStatus.stockDetailStatusID = salesOrderDetail.stockDetailStatusID
            inner join stockDetail on stockDetail.stockDetailID = stockDetailStatus.stockDetailID
            inner join product on product.productID = stockDetail.productID
            where salesOrder.salesAgentID = " . $salesAgentID . " and salesOrder.orderDate = '" . $orderDate . "'
            group by
                salesOrder.salesOrderID,
                salesOrder.customerID,
                salesOrder.orderDate,
                stockDetail.productID
            order by
                salesOrder.salesOrderID,
                stockDetail.productID
        ";
        return DB::select($rawSQL);
    }

    public static function getSalesAgentSummary($salesAgentID, $startDate,$endDate) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $rawSQL = "
            SELECT
                salesOrder.orderDate,
                stockDetail.productID,
                product.productName,
                SUM(stockDetailStatus.quantity) as quantity,
                (SUM((stockDetailStatus.quantity * stockDetailStatus.saleprice)) - SUM(salesOrder.discount)) AS total
            FROM salesOrder
            INNER JOIN salesOrderDetail on salesOrder.salesOrderID = salesOrderDetail.salesOrderID
            INNER JOIN stockDetailStatus on stockDetailStatus.stockDetailStatusID = salesOrderDetail.stockDetailStatusID
            INNER JOIN stockDetail on stockDetail.stockDetailID = stockDetailStatus.stockDetailID
            INNER JOIN product on product.productID = stockDetail.productID
            WHERE salesOrder.salesAgentID = " . $salesAgentID . " AND salesOrder.orderDate BETWEEN '" . $startDate . "' AND '" . $endDate . "'
            GROUP BY
                salesOrder.orderDate,
                stockDetail.productID
            ORDER BY
                salesOrder.orderDate,
                stockDetail.productID
        ";
        return DB::select($rawSQL);
    }

    public static function getSupplierAgentSummary($supplierID, $startDate, $endDate){
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $rawSQL = "
        SELECT
            purchaseorder.purchaseOrderDate,
	        purchaseorderdetail.productID,
	        SUM(purchaseorderdetail.quantity) AS quantity ,
	        SUM(purchaseorderdetail.quantity * purchaseorderdetail.perUnitPrice) AS total	,
	        product.productName
        FROM
            purchaseorderdetail
            INNER JOIN purchaseorder ON purchaseorder.purchaseOrderID = purchaseorderdetail.purchaseOrderDetailID
            INNER JOIN product ON product.productID = purchaseorderdetail.productID
            
        WHERE purchaseorder.supplierID = ".$supplierID." AND purchaseOrderDate BETWEEN '" . $startDate . "' AND '".$endDate."' 
        GROUP BY purchaseorder.purchaseOrderDate,
                 purchaseorderdetail.productID
        ORDER BY  purchaseorder.purchaseOrderDate,
                  purchaseorderdetail.productID
        ";

        return DB::select($rawSQL);

    }
}
