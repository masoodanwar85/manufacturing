<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stock';
    protected $primaryKey = 'stockID';
	protected $with = ['stockDetails'];
    public $timestamps = false;
    protected $fillable = ['stockID','purchaseOrderID','productionID','createdByUserID'];

	public function stockDetails()
    {
        return $this->hasMany('App\Models\StockDetail','stockID','stockID');
    }

	public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder','purchaseOrderID','purchaseOrderID');
    }

    public function production()
    {
        return $this->belongsTo('App\Models\Production','productionID','productionID');
    }

	public static function getStock($productID = null,$isThresholdStock = FALSE,$isGroupByGodown = FALSE, $godownID = null, $productTypeID = null) {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$whereClause = "WHERE 1=1";

		if (is_numeric($productID) && $productID > 0) {
			$whereClause .= " AND productID = " . (int) $productID;
		}
        if (is_numeric($productTypeID) && $productTypeID > 0) {
			$whereClause .= " AND productTypeID = " . (int) $productTypeID;
		}
		if ($isThresholdStock == TRUE) {
			$whereClause .= " AND (totalPurchasedQuantity + totalGoodSalesReturnQuantity - totalSoldQuantity - totalDamagedQuantity - totalBadSalesReturnQuantity) < thresholdUnit";
		}
        if ($isGroupByGodown == TRUE && is_numeric($godownID) && $godownID > 0) {
			$whereClause .= " AND godownID = " . (int) $godownID;
		}
		$rawSQL = "
			SELECT
				symbol,productID,
				unitsInProduct,
				" . ($isGroupByGodown == TRUE ? 'godownID,godownName,' : '') . "
                productTypeID,
				productName,
				thresholdUnit,
				totalPurchasedQuantity,
				totalPurchasedUnits,
				totalSoldQuantity,
				totalSoldUnits,
				totalDamagedQuantity,
				totalDamagedUnits,
				totalBadSalesReturnQuantity,
				totalBadSalesReturnUnits,
				totalGoodSalesReturnQuantity,
				totalGoodSalesReturnUnits,
				(totalPurchasedQuantity + totalGoodSalesReturnQuantity - totalSoldQuantity - totalDamagedQuantity - totalBadSalesReturnQuantity - totalManufacturingQuantity) AS inStockQuantity,
				(totalPurchasedUnits + totalGoodSalesReturnUnits - totalSoldUnits - totalDamagedUnits - totalBadSalesReturnUnits - totalManufacturingQuantity) AS inStockUnits,
				lastPurchasePrice,
                ((totalPurchasedQuantity + totalGoodSalesReturnQuantity - totalSoldQuantity - totalDamagedQuantity - totalBadSalesReturnQuantity - totalManufacturingQuantity) * lastPurchasePrice) AS inStockTotalPrice
			FROM (
				SELECT
					measurementUnit.symbol,
					temp.productID,
					product.unitsInProduct,
					" . ($isGroupByGodown == TRUE ? 'temp.godownID,godown.name as godownName,' : '') . "
					product.productTypeID,
                    product.productName,
					product.thresholdUnit,
					SUM(temp.totalQuantityPurchased) AS totalPurchasedQuantity,
					SUM(temp.totalUnitsPurchased) AS totalPurchasedUnits,
					SUM(temp.quantitySold) AS totalSoldQuantity,
					SUM(temp.unitsSold) AS totalSoldUnits,
					SUM(temp.quantityDamaged) AS totalDamagedQuantity,
					SUM(temp.unitsDamaged) AS totalDamagedUnits,
					SUM(temp.quantityBadSalesReturn) AS totalBadSalesReturnQuantity,
					SUM(temp.unitsBadSalesReturn) AS totalBadSalesReturnUnits,
					SUM(temp.quantityGoodSalesReturn) AS totalGoodSalesReturnQuantity,
					SUM(temp.unitsGoodSalesReturn) AS totalGoodSalesReturnUnits,
                    SUM(temp.quantityManufacturing) AS totalManufacturingQuantity,
					product.unitPurchasePrice AS lastPurchasePrice
				FROM (
					SELECT
						stockDetail.productID,
						" . ($isGroupByGodown == TRUE ? 'stockDetailStatus.godownID,' : '') . "
						SUM(stockDetailStatus.quantity) AS totalQuantityPurchased,
						SUM(stockDetailStatus.quantityUnits) AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.quetta_godown') . "
					GROUP BY stockDetail.productID" . ($isGroupByGodown == TRUE ? ',stockDetailStatus.godownID' : '') . "
					UNION
					SELECT
						stockDetail.productID,
						" . ($isGroupByGodown == TRUE ? 'stockDetailStatus.godownID,' : '') . "
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						SUM(stockDetailStatus.quantity) AS quantityGoodSalesReturn,
						SUM(stockDetailStatus.quantityUnits) AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.good_sales_return') . "
					GROUP BY stockDetail.productID" . ($isGroupByGodown == TRUE ? ',stockDetailStatus.godownID' : '') . "
					UNION
					SELECT
						stockDetail.productID,
						" . ($isGroupByGodown == TRUE ? 'stockDetailStatus.godownID,' : '') . "
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						SUM(stockDetailStatus.quantity) AS quantitySold,
						SUM(stockDetailStatus.quantityUnits) AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.sold') . "
					GROUP BY stockDetail.productID" . ($isGroupByGodown == TRUE ? ',stockDetailStatus.godownID' : '') . "
					UNION
					SELECT
						stockDetail.productID,
						" . ($isGroupByGodown == TRUE ? 'stockDetailStatus.godownID,' : '') . "
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						SUM(stockDetailStatus.quantity) AS quantityBadSalesReturn,
						SUM(stockDetailStatus.quantityUnits) AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.bad_sales_return') . "
					GROUP BY stockDetail.productID" . ($isGroupByGodown == TRUE ? ',stockDetailStatus.godownID' : '') . "
					UNION
					SELECT
						stockDetail.productID,
						" . ($isGroupByGodown == TRUE ? 'stockDetailStatus.godownID,' : '') . "
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						SUM(stockDetailStatus.quantity) AS quantityDamaged,
						SUM(stockDetailStatus.quantityUnits) AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.damaged') . "
					GROUP BY stockDetail.productID" . ($isGroupByGodown == TRUE ? ',stockDetailStatus.godownID' : '') . "
                    UNION
                    SELECT
						stockDetail.productID,
						" . ($isGroupByGodown == TRUE ? 'stockDetailStatus.godownID,' : '') . "
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        SUM(stockDetailStatus.quantity) AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.manufacturing') . "
					GROUP BY stockDetail.productID" . ($isGroupByGodown == TRUE ? ',stockDetailStatus.godownID' : '') . "
				) AS temp
				INNER JOIN product ON product.productID = temp.productID
				" . ($isGroupByGodown == TRUE ? 'INNER JOIN godown ON godown.godownID = temp.godownID' : '') . "
				INNER JOIN measurementUnit ON product.maximumUnitID = measurementUnit.unitID
				GROUP BY temp.productID" . ($isGroupByGodown == TRUE ? ',temp.godownID' : '') . "
			) AS temp2
			" . $whereClause . "
			ORDER BY productName";
		return DB::select($rawSQL);
	}

	public static function getGodownStock($godownID) {
	    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$whereClause = " WHERE godownID = " . (int) $godownID;
		$rawSQL = "
			SELECT
				symbol,productID,
				unitsInProduct,
				productName,
				godownID,
				godownName,
				totalPurchasedQuantity,
				totalPurchasedUnits,
				totalSoldQuantity,
				totalSoldUnits,
				totalDamagedQuantity,
				totalDamagedUnits,
				totalBadSalesReturnQuantity,
				totalBadSalesReturnUnits,
				totalGoodSalesReturnQuantity,
				totalGoodSalesReturnUnits,
				(totalPurchasedQuantity + totalGoodSalesReturnQuantity - totalSoldQuantity - totalDamagedQuantity - totalBadSalesReturnQuantity- totalManufacturingQuantity) AS inStockQuantity,
				(totalPurchasedUnits + totalGoodSalesReturnUnits - totalSoldUnits - totalDamagedUnits - totalBadSalesReturnUnits - totalManufacturingQuantity) AS inStockUnits,
				lastPurchasePrice
			FROM (
				SELECT
					measurementUnit.symbol,
					temp.productID,
					product.unitsInProduct,
					product.productName,
					temp.godownID,
					godown.name as godownName,
					SUM(temp.totalQuantityPurchased) AS totalPurchasedQuantity,
					SUM(temp.totalUnitsPurchased) AS totalPurchasedUnits,
					SUM(temp.quantitySold) AS totalSoldQuantity,
					SUM(temp.unitsSold) AS totalSoldUnits,
					SUM(temp.quantityDamaged) AS totalDamagedQuantity,
					SUM(temp.unitsDamaged) AS totalDamagedUnits,
					SUM(temp.quantityBadSalesReturn) AS totalBadSalesReturnQuantity,
					SUM(temp.unitsBadSalesReturn) AS totalBadSalesReturnUnits,
					SUM(temp.quantityGoodSalesReturn) AS totalGoodSalesReturnQuantity,
					SUM(temp.unitsGoodSalesReturn) AS totalGoodSalesReturnUnits,
                    SUM(temp.quantityManufacturing) AS totalManufacturingQuantity,
					product.unitPurchasePrice AS lastPurchasePrice
				FROM (
					SELECT
						stockDetail.productID,
						stockDetailStatus.godownID,
						SUM(stockDetailStatus.quantity) AS totalQuantityPurchased,
						SUM(stockDetailStatus.quantityUnits) AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.quetta_godown') . "
					GROUP BY stockDetail.productID,stockDetailStatus.godownID
					UNION
					SELECT
						stockDetail.productID,
						stockDetailStatus.godownID,
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						SUM(stockDetailStatus.quantity) AS quantityGoodSalesReturn,
						SUM(stockDetailStatus.quantityUnits) AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					INNER JOIN product ON product.productID = stockDetail.productID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.good_sales_return') . "
					GROUP BY stockDetail.productID,stockDetailStatus.godownID
					UNION
					SELECT
						stockDetail.productID,
						stockDetailStatus.godownID,
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						SUM(stockDetailStatus.quantity) AS quantitySold,
						SUM(stockDetailStatus.quantityUnits) AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					INNER JOIN product ON product.productID = stockDetail.productID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.sold') . "
					GROUP BY stockDetail.productID,stockDetailStatus.godownID
					UNION
					SELECT
						stockDetail.productID,
						stockDetailStatus.godownID,
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						0 AS quantityDamaged,
						0 AS unitsDamaged,
						SUM(stockDetailStatus.quantity) AS quantityBadSalesReturn,
						SUM(stockDetailStatus.quantityUnits) AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					INNER JOIN product ON product.productID = stockDetail.productID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.bad_sales_return') . "
					GROUP BY stockDetail.productID,stockDetailStatus.godownID
					UNION
					SELECT
						stockDetail.productID,
						stockDetailStatus.godownID,
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						SUM(stockDetailStatus.quantity) AS quantityDamaged,
						SUM(stockDetailStatus.quantityUnits) AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        0 AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					INNER JOIN product ON product.productID = stockDetail.productID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.damaged') . "
					GROUP BY stockDetail.productID,stockDetailStatus.godownID
                    UNION
                    SELECT
						stockDetail.productID,
						stockDetailStatus.godownID,
						0 AS totalQuantityPurchased,
						0 AS totalUnitsPurchased,
						0 AS quantitySold,
						0 AS unitsSold,
						0 AS quantityManufacturing,
						0 AS unitsDamaged,
						0 AS quantityBadSalesReturn,
						0 AS unitsBadSalesReturn,
						0 AS quantityGoodSalesReturn,
						0 AS unitsGoodSalesReturn,
                        SUM(stockDetailStatus.quantity) AS quantityManufacturing
					FROM stockDetail
					INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
					INNER JOIN product ON product.productID = stockDetail.productID
					WHERE stockDetailStatus.statusID = " . \Config::get('constants.stock_status.manufacturing') . "
					GROUP BY stockDetail.productID,stockDetailStatus.godownID
				) AS temp
				INNER JOIN product ON product.productID = temp.productID
				INNER JOIN godown ON godown.godownID = temp.godownID
				INNER JOIN measurementUnit ON product.maximumUnitID = measurementUnit.unitID
				GROUP BY temp.productID,temp.godownID
			) AS temp2
			" . $whereClause . "
			ORDER BY productName";
		return DB::select($rawSQL);
	}

	public static function getProducts($productID = null) {
	    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$whereClause = "";
		if (is_numeric($productID) && $productID > 0) {
			$whereClause = " AND product.productID = " . (int) $productID;
		}
		$rawSQL = "
			SELECT Temp.productID,Temp.unitsInProduct,Temp.productName,Temp.purchasePrice,SUM(Temp.totalQty) AS quantityAvailable,category.categoryName FROM (
				SELECT product.categoryID,product.unitsInProduct,product.productID,product.productName,product.unitPurchasePrice AS purchasePrice,SUM(stockDetailStatus.quantity) AS totalQty
				FROM product
				INNER JOIN stockDetail ON stockDetail.productID = product.productID
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				WHERE stockDetailStatus.statusID IN (" . \Config::get('constants.stock_status.isAvailableForSale') . ")" . $whereClause . "
				GROUP BY product.productID
				UNION
				SELECT product.categoryID,product.unitsInProduct,product.productID,product.productName,product.unitPurchasePrice,(SUM(stockDetailStatus.quantity) * -1) AS totalQty
				FROM product
				INNER JOIN stockDetail ON stockDetail.productID = product.productID
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				WHERE stockDetailStatus.statusID IN (" . \Config::get('constants.stock_status.isNotAvailableForSale') . ")" . $whereClause . "
				GROUP BY product.productID
			) AS Temp
			INNER JOIN category ON category.categoryID = Temp.categoryID
			GROUP BY Temp.productID,Temp.unitsInProduct,Temp.productName,Temp.purchasePrice,category.categoryName
			HAVING quantityAvailable > 0
			ORDER BY Temp.productName";

		return DB::select($rawSQL);
	}

	public static function getGodownProducts($productID = null)
	{
	    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$whereClause = "";
		if (is_numeric($productID) && $productID > 0) {
			$whereClause = " AND product.productID = " . (int) $productID;
		}
		$rawSQL = "
			SELECT Temp.unitsInProduct,measurementUnit.symbol,Temp.productID,Temp.godownID,Temp.productName,Temp.purchasePrice,SUM(Temp.totalQty) AS quantityAvailable,SUM(Temp.totalUnits) AS unitsAvailable,category.categoryName,godown.name as godownName FROM (
				SELECT product.unitsInProduct,product.maximumUnitID,product.categoryID,stockDetailStatus.godownID,product.productID,product.productName,product.unitPurchasePrice AS purchasePrice,SUM(stockDetailStatus.quantity) AS totalQty,SUM(stockDetailStatus.quantityUnits) AS totalUnits
				FROM product
				INNER JOIN stockDetail ON stockDetail.productID = product.productID
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				WHERE stockDetailStatus.statusID IN (" . \Config::get('constants.stock_status.isAvailableForSale') . ")" . $whereClause . "
				GROUP BY product.productID,stockDetailStatus.godownID
				UNION
				SELECT product.unitsInProduct,product.maximumUnitID,product.categoryID,stockDetailStatus.godownID,product.productID,product.productName,product.unitPurchasePrice,(SUM(stockDetailStatus.quantity) * -1) AS totalQty,(SUM(stockDetailStatus.quantityUnits) * -1) AS totalUnits
				FROM product
				INNER JOIN stockDetail ON stockDetail.productID = product.productID
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				WHERE stockDetailStatus.statusID IN (" . \Config::get('constants.stock_status.isNotAvailableForSale') . ")" . $whereClause . "
				GROUP BY product.productID,stockDetailStatus.godownID
			) AS Temp
			INNER JOIN category ON category.categoryID = Temp.categoryID
			INNER JOIN godown ON godown.godownID = Temp.godownID
			INNER JOIN measurementUnit ON measurementUnit.unitID = Temp.maximumUnitID
			GROUP BY Temp.unitsInProduct,measurementUnit.symbol,Temp.productID,Temp.godownID,Temp.productName,Temp.purchasePrice,category.categoryName
			HAVING unitsAvailable > 0
			ORDER BY Temp.productName,godown.saleSortOrder";

		return DB::select($rawSQL);
	}

	public static function getProductStockDetails($productID,$godownID = NULL)
	{
	    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$whereClause = " AND stockDetail.productID = " . (int) $productID;
		if (is_numeric($godownID) && $godownID > 0) {
			 $whereClause .= " AND stockDetailStatus.godownID = " . (int) $godownID;
		}
		$rawSQL = "
			SELECT temp.stockDetailID,temp.godownID,SUM(totalQty) AS quantityAvailable,SUM(totalUnits) AS unitsAvailable FROM (
				SELECT
					stockDetail.stockDetailID,
                    stockDetailStatus.godownID,
					stockDetail.purchasePrice,
					product.unitsInProduct,
					SUM(stockDetailStatus.quantity) AS totalQty,
					SUM(stockDetailStatus.quantityUnits) AS totalUnits
				FROM stockDetail
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				INNER JOIN product ON product.productID = stockDetail.productID
				WHERE stockDetailStatus.statusID IN (" . \Config::get('constants.stock_status.isAvailableForSale') . ")" . $whereClause . "
				GROUP BY stockDetailStatus.godownID,stockDetail.stockDetailID
				UNION
				SELECT
					stockDetail.stockDetailID,
                    stockDetailStatus.godownID,
					stockDetail.purchasePrice,
					product.unitsInProduct,
					(SUM(stockDetailStatus.quantity) * -1) AS totalQty,
					(SUM(stockDetailStatus.quantityUnits) * -1) AS totalUnits
				FROM stockDetail
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				INNER JOIN product ON product.productID = stockDetail.productID
				WHERE stockDetailStatus.statusID IN (" . \Config::get('constants.stock_status.isNotAvailableForSale') . ")" . $whereClause . "
				GROUP BY stockDetailStatus.godownID,stockDetail.stockDetailID
			) AS temp
            LEFT JOIN godown ON godown.godownID = temp.godownID
			GROUP BY temp.stockDetailID,temp.godownID
			HAVING unitsAvailable > 0
            ORDER BY godown.saleSortOrder";
		return DB::select($rawSQL);
	}

	// select stockDetailID,productID,godownID,SUM(totalQuantityPurchased) as purchased,SUM(quantitySold) as sold, SUM(GReturn) as goodReturn,SUM(manufacturing) as manufacturing from (
	// select
	// 	stockDetailStatus.stockDetailID,
	// 	stockDetail.productID,
	// 	stockDetailStatus.godownID,
	// 	SUM(stockDetailStatus.quantity) AS totalQuantityPurchased,
	// 	0 AS quantitySold,
	// 	0 as GReturn,
	// 	0 as manufacturing
	// FROM stockDetail
	// INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
	// WHERE stockDetailStatus.statusID = 1 and stockDetail.productID = 17
	// GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
	// UNION
	// select
	// 	stockDetailStatus.stockDetailID,
	// 	stockDetail.productID,
	// 	stockDetailStatus.godownID,
	// 	0 AS totalQuantityPurchased,
	// 	SUM(stockDetailStatus.quantity) AS quantitySold,
	// 	0 as GReturn,
	// 	0 as manufacturing
	// FROM stockDetail
	// INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
	// WHERE stockDetailStatus.statusID = 3 and stockDetail.productID = 17
	// GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
	// UNION
	// select
	// 	stockDetailStatus.stockDetailID,
	// 	stockDetail.productID,
	// 	stockDetailStatus.godownID,
	// 	0 AS totalQuantityPurchased,
	// 	0 AS quantitySold,
	// 	SUM(stockDetailStatus.quantity) AS GReturn,
	// 	0 as manufacturing
	// FROM stockDetail
	// INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
	// WHERE stockDetailStatus.statusID = 2 and stockDetail.productID = 17
	// GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
	// UNION
	// select
	// 	stockDetailStatus.stockDetailID,
	// 	stockDetail.productID,
	// 	stockDetailStatus.godownID,
	// 	0 AS totalQuantityPurchased,
	// 	0 AS quantitySold,
	// 	0 AS GReturn,
	// 	SUM(stockDetailStatus.quantity) as manufacturing
	// FROM stockDetail
	// INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
	// WHERE stockDetailStatus.statusID = 6 and stockDetail.productID = 17
	// GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
	// ) as t
	// group by stockDetailID,productID,godownID
	// having sold < purchased
	// order by godownID,stockDetailID


	public static function getStockError($productID,$godownID = NULL)
	{
	    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$rawSQL = "
			select stockDetailID,productID,godownID,SUM(totalQuantityPurchased) as purchased,SUM(quantitySold) as sold from (
				select
					stockDetailStatus.stockDetailID,
					stockDetail.productID,
					stockDetailStatus.godownID,
					SUM(stockDetailStatus.quantity) AS totalQuantityPurchased,
					0 AS quantitySold
				FROM stockDetail
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				WHERE stockDetailStatus.statusID = 1 and stockDetail.productID = " . $productID . " AND stockDetailStatus.godownID = " . (int) $godownID . "
				GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
				UNION
				select
					stockDetailStatus.stockDetailID,
					stockDetail.productID,
					stockDetailStatus.godownID,
					0 AS totalQuantityPurchased,
					SUM(stockDetailStatus.quantity) AS quantitySold
				FROM stockDetail
				INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
				WHERE stockDetailStatus.statusID = 3 and stockDetail.productID = " . $productID . " AND stockDetailStatus.godownID = " . (int) $godownID . "
				GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
			) as t
			group by stockDetailID,productID,godownID
			having sold < purchased
			order by stockDetailID,godownID";
		return DB::select($rawSQL);
	}
}
