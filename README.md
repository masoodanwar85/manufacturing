<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## About Laravel Import & Sale Management System

An import & Sale Management System based on Laravel 8

## How to install

- composer install
- npm install
- npm run dev
- php artisan key:generate
- php artisan migrate --seed

## Issues while deploying
- If you get error ('Server Error') while listing the datatables then add the following SQL before that query
- DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

## TODO

- sales return (Pending) - or Edit Sales Return amount adjustment issue handling
- filters in listing page
- datatables button for Copy, Print, PDF
- Customer Sales Alert based on due date
- Urdu translation
- Auto adjust amount of customer, if entered more than actual sales order price.
- Amount should be shown in urdu too
- There should be Bank/Cheque options in Purchase Orders and Sales Orders
- Unsaved changes alert on the form (Leave?)
- Sales Form Check if the quantities of the products are correct?
- Sales Edit Form
- Save foreign amount only - do not convert to local, AND currency conversion should be done at query level
- image uploading in products, users etc.
- Receipts & payments -> Cross entry -> Add Customers/Suppliers (Other Party) and show their respective balances when dropdown selected.
- Godowns tracking
- customizeShift needs fixation - put both quantity and units in form -
- Damage Logic fixation
- Link the invoices wherever they are
- Payment / Receipts Confirmation page
- Staff Ledger
- Purchase should be editable until sold
- Expense Rows in Sales Order (like in Purchase Orders), so that the account should be maintained too
- Reports
    - Profit/Loss (All Period, Specific Purchase, Specific Sales)
	- Cash in hand
    - Account Ledgers --- Customer, Suppliers, Staff, Godowns, Transport, Bank
## Conversion of exchange rate calculation

## Questions

## Suggestions TODO
UPDATE purchaseOrderDetail SET quantity = 325, quantityUnits = 325 WHERE purchaseOrderDetailID = 433;
UPDATE transactionDetail SET amount = 45500 WHERE transactionID = 11033;
UPDATE stockDetail SET quantity = 325, quantityUnits = 325 WHERE stockDetailID = 897;
UPDATE stockDetailStatus SET quantity = 325, quantityUnits = 325 WHERE stockDetailStatusID = 7477;

UPDATE purchaseOrderDetail SET quantity = 115, quantityUnits = 115 WHERE purchaseOrderDetailID = 534;
UPDATE transactionDetail SET amount = 12075 WHERE transactionID = 5394;
UPDATE stockDetail SET quantity = 115, quantityUnits = 115 WHERE stockDetailID = 534;
UPDATE stockDetailStatus SET quantity = 115, quantityUnits = 115 WHERE stockDetailStatusID = 7319;

UPDATE stockDetail SET quantity = 504, quantityUnits = 504 WHERE stockDetailID = 720;
UPDATE stockDetailStatus SET quantity = 504, quantityUnits = 504 WHERE stockDetailStatusID = 6113;

UPDATE purchaseOrderDetail SET quantity = 577, quantityUnits = 577 WHERE purchaseOrderDetailID = 430;
UPDATE transactionDetail SET amount = 80780 WHERE transactionID = 10732;
UPDATE stockDetail SET quantity = 577, quantityUnits = 577 WHERE stockDetailID = 834;
UPDATE stockDetailStatus SET quantity = 577, quantityUnits = 577 WHERE stockDetailStatusID = 7017;

UPDATE purchaseOrderDetail SET quantity = 4709, quantityUnits = 4709 WHERE purchaseOrderDetailID = 435;
UPDATE transactionDetail SET amount = 2071960 WHERE transactionID = 11094;
UPDATE stockDetail SET quantity = 4709, quantityUnits = 4709 WHERE stockDetailID = 963;
UPDATE stockDetailStatus SET quantity = 4709, quantityUnits = 4709 WHERE stockDetailStatusID = 7767;

DELETE FROM stockDetailStatus WHERE stockDetailStatusID = 7950;
UPDATE stockDetail SET quantity = 1, quantityUnits = 1 WHERE stockDetailID = 1005;
UPDATE productionBOM set quantity = 1 WHERE productionBOMID = 464;

DELETE FROM productionBOMItem WHERE productionBOMID = 435;
DELETE FROM productionBOM WHERE productionBOMID = 435;
DELETE FROM stockDetailStatus WHERE stockDetailID = 976;
DELETE FROM stockDetail WHERE stockID = 593;
DELETE FROM stock WHERE stockID = 593;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7549;
UPDATE stockDetail SET quantity = 1, quantityUnits = 1 WHERE stockDetailID = 913;
UPDATE productionBOM set quantity = 1 WHERE productionBOMID = 371;

UPDATE stockDetail SET quantity = 8, quantityUnits = 8 WHERE stockDetailID = 694;
UPDATE stockDetailStatus SET quantity = 7,quantityUnits = 7 WHERE stockDetailStatusID = 6087;
UPDATE stockDetailStatus SET godownID = 4 WHERE stockDetailStatusID = 4848;

UPDATE stockDetailStatus SET godownID = 4 WHERE stockDetailStatusID = 8006;

DELETE FROM productionBOMItem WHERE productionBOMID = 451;
DELETE FROM productionBOM WHERE productionBOMID = 451;
DELETE FROM stockDetailStatus WHERE stockDetailID = 992;
DELETE FROM stockDetail WHERE stockDetailID = 992;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7670;
UPDATE stockDetail SET quantity = 1, quantityUnits = 1 WHERE stockDetailID = 940;
UPDATE productionBOM set quantity = 1 WHERE productionBOMID = 398;

DELETE FROM productionBOMItem WHERE productionBOMID = 369;
DELETE FROM productionBOM WHERE productionBOMID = 369;
DELETE FROM stockDetailStatus WHERE stockDetailID = 911;
DELETE FROM stockDetail WHERE stockDetailID = 911;

UPDATE stockDetailStatus SET quantity = 2, quantityUnits = 2 WHERE stockDetailStatusID = 7534;
UPDATE stockDetail SET quantity = 2, quantityUnits = 2 WHERE stockDetailID = 910;
UPDATE productionBOM SET quantity = 2 WHERE productionBOMID = 368;

UPDATE stockDetailStatus SET quantity = 3, quantityUnits = 3 WHERE stockDetailStatusID = 7494;
UPDATE stockDetail SET quantity = 3, quantityUnits = 3 WHERE stockDetailID = 901;
UPDATE productionBOM SET quantity = 3 WHERE productionBOMID = 359;

UPDATE stockDetailStatus SET stockDetailID = 1010 WHERE stockDetailStatusID = 5047;
UPDATE stockDetailStatus SET stockDetailID = 871 WHERE stockDetailStatusID = 5076;
UPDATE stockDetailStatus SET stockDetailID = 901 WHERE stockDetailStatusID = 5056;
UPDATE stockDetailStatus SET stockDetailID = 1023 WHERE stockDetailStatusID = 3024;
UPDATE stockDetailStatus SET stockDetailID = 1019 WHERE stockDetailStatusID = 5052;
UPDATE stockDetailStatus SET stockDetailID = 1001 WHERE stockDetailStatusID = 6022;

UPDATE stockDetail SET godownID = 4 WHERE stockDetailID = 994;
UPDATE stockDetailStatus SET godownID = 4 WHERE stockDetailID = 994;

UPDATE stockDetailStatus SET quantity = 3, quantityUnits = 3 WHERE stockDetailStatusID = 7850;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,4 AS godownID,NULL AS bookSerial,1 AS quantity,discount,1 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 7850;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7692;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,4 AS godownID,NULL AS bookSerial,2 AS quantity,discount,2 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 7692;

UPDATE stockDetailStatus SET quantity = 3, quantityUnits = 3 WHERE stockDetailStatusID = 7618;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,4 AS godownID,NULL AS bookSerial,2 AS quantity,discount,2 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 7618;


UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7570;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,4 AS godownID,NULL AS bookSerial,1 AS quantity,discount,1 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 7570;

DELETE FROM productionBOMItem WHERE productionBOMID = 366;
DELETE FROM productionBOM WHERE productionBOMID = 366;
DELETE FROM stockDetailStatus WHERE stockDetailID = 908;
DELETE FROM stockDetail WHERE stockDetailID = 908;
DELETE FROM stock WHERE stockID = 525;

DELETE FROM productionBOMItem WHERE productionBOMID = 366;
DELETE FROM productionBOM WHERE productionBOMID = 366;
DELETE FROM stockDetailStatus WHERE stockDetailID = 908;
DELETE FROM stockDetail WHERE stockDetailID = 908;
DELETE FROM stock WHERE stockID = 525;

DELETE FROM productionBOMItem WHERE productionBOMID = 351;
DELETE FROM productionBOM WHERE productionBOMID = 351;
DELETE FROM stockDetailStatus WHERE stockDetailID = 892;
DELETE FROM stockDetail WHERE stockDetailID = 892;
DELETE FROM stock WHERE stockID = 509;

UPDATE stockDetailStatus SET quantity = 2, quantityUnits = 2 WHERE stockDetailStatusID = 7481;
UPDATE stockDetail SET quantity = 2, quantityUnits = 2 WHERE stockDetailID = 898;
UPDATE productionBOM SET quantity = 2 WHERE productionBOMID = 356;

DELETE FROM productionBOMItem WHERE productionBOMID = 237;
DELETE FROM productionBOM WHERE productionBOMID = 237;
DELETE FROM stockDetailStatus WHERE stockDetailID = 782;
DELETE FROM stockDetail WHERE stockDetailID = 782;
DELETE FROM stock WHERE stockID = 399;

UPDATE stockDetailStatus SET stockDetailID = 984 WHERE stockDetailStatusID = 5057;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7657;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,4 AS godownID,NULL AS bookSerial,2 AS quantity,discount,2 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 7657;

UPDATE purchaseOrderDetail SET quantity = 20, quantityUnits = 20 WHERE purchaseOrderDetailID = 365;
UPDATE stockDetail SET quantity = 20, quantityUnits = 20 WHERE stockDetailID = 491;
UPDATE stockDetailStatus SET quantity = 5, quantityUnits = 5 WHERE stockDetailStatusID = 4886;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7613;
UPDATE stockDetail SET quantity = 1, quantityUnits = 1 WHERE stockDetailID = 927;
UPDATE productionBOM SET quantity = 1 WHERE productionBOMID = 385;

DELETE FROM stockDetailStatus WHERE stockDetailStatusID = 7657;
UPDATE stockDetail SET quantity = 2, quantityUnits = 2 WHERE stockDetailID = 937;
UPDATE productionBOM SET quantity = 2 WHERE productionBOMID = 395;

DELETE FROM productionBOMItem WHERE productionBOMID = 487;
DELETE FROM productionBOM WHERE productionBOMID = 487;
DELETE FROM stockDetailStatus WHERE stockDetailID = 1028;
DELETE FROM stockDetail WHERE stockDetailID = 1028;
DELETE FROM stock WHERE stockID = 645;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7485;
UPDATE stockDetail SET quantity = 1, quantityUnits = 1 WHERE stockDetailID = 899;
UPDATE productionBOM SET quantity = 1 WHERE productionBOMID = 357;

UPDATE stockDetail SET quantity = 8, quantityUnits = 8 WHERE stockDetailID = 707;
UPDATE stockDetailStatus SET quantity = 5, quantityUnits = 5 WHERE stockDetailStatusID = 6100;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7116;
UPDATE stockDetail SET quantity = 2, quantityUnits = 2 WHERE stockDetailID = 859;
UPDATE productionBOM SET quantity = 2 WHERE productionBOMID = 315;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7104;
UPDATE stockDetail SET quantity = 1, quantityUnits = 1 WHERE stockDetailID = 856;
UPDATE productionBOM SET quantity = 1 WHERE productionBOMID = 312;

UPDATE stockDetailStatus SET quantity = 2, quantityUnits = 2 WHERE stockDetailStatusID = 6767;
UPDATE stockDetail SET quantity = 131, quantityUnits = 131 WHERE stockDetailID = 17;

DELETE FROM productionBOMItem WHERE productionBOMID = 372;
DELETE FROM productionBOM WHERE productionBOMID = 372;
DELETE FROM stockDetailStatus WHERE stockDetailID = 914;
DELETE FROM stockDetail WHERE stockDetailID = 914;
DELETE FROM stock WHERE stockID = 531;

DELETE FROM stockDetailStatus WHERE stockDetailStatusID = 7898;
UPDATE stockDetail SET quantity = 2, quantityUnits = 2 WHERE stockDetailID = 993;
UPDATE productionBOM SET quantity = 2 WHERE productionBOMID = 452;

UPDATE stockDetailStatus SET quantity = 5, quantityUnits = 5 WHERE stockDetailStatusID = 6090;
UPDATE stockDetail SET quantity = 10, quantityUnits = 10 WHERE stockDetailID = 697;

DELETE FROM productionBOMItem WHERE productionBOMID = 436;
DELETE FROM productionBOM WHERE productionBOMID = 436;
DELETE FROM stockDetailStatus WHERE stockDetailID = 977;
DELETE FROM stockDetail WHERE stockDetailID = 977;
DELETE FROM stock WHERE stockID = 594;

DELETE FROM productionBOMItem WHERE productionBOMID = 350;
DELETE FROM productionBOM WHERE productionBOMID = 350;
DELETE FROM stockDetailStatus WHERE stockDetailID = 891;
DELETE FROM stockDetail WHERE stockDetailID = 891;
DELETE FROM stock WHERE stockID = 508;

DELETE FROM stockDetailStatus WHERE stockDetailStatusID = 6101;
UPDATE stockDetail SET quantity = 8, quantityUnits = 8 WHERE stockDetailID = 708;

UPDATE stockDetail SET godownID = 4 WHERE stockDetailID = 924;
UPDATE stockDetailStatus SET godownID = 4 WHERE stockDetailStatusID = 7599;

UPDATE stockDetailStatus SET quantity = 2, quantityUnits = 2 WHERE stockDetailStatusID = 7644;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,3 AS godownID,NULL AS bookSerial,1 AS quantity,discount,1 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 7644;

UPDATE stockDetailStatus SET quantity = 2, quantityUnits = 2 WHERE stockDetailStatusID = 6103;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,2 AS godownID,NULL AS bookSerial,2 AS quantity,discount,2 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 6103;

UPDATE stockDetailStatus SET quantity = 2, quantityUnits = 2 WHERE stockDetailStatusID = 4892;
UPDATE stockDetail SET quantity = 11, quantityUnits = 11 WHERE stockDetailID = 500;
UPDATE purchaseOrderDetail SET quantity = 13, quantityUnits = 13 WHERE purchaseOrderDetailID = 374;

UPDATE stockDetailStatus SET quantity = 3, quantityUnits = 3 WHERE stockDetailStatusID = 6093;
UPDATE stockDetail SET quantity = 5, quantityUnits = 5 WHERE stockDetailID = 500;

UPDATE stockDetailStatus SET quantity = 3, quantityUnits = 3 WHERE stockDetailStatusID = 7874;
UPDATE stockDetail SET quantity = 8, quantityUnits = 8 WHERE stockDetailID = 988;

UPDATE stockDetailStatus SET quantity = 6, quantityUnits = 6 WHERE stockDetailStatusID = 6094;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,2 AS godownID,NULL AS bookSerial,2 AS quantity,discount,2 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 6094;

UPDATE stockDetailStatus SET quantity = 3, quantityUnits = 3 WHERE stockDetailStatusID = 6094;
UPDATE stockDetail SET quantity = 5, quantityUnits = 5 WHERE stockDetailID = 701;

DELETE FROM transactionDetail WHERE transactionID = 9109;
DELETE FROM purchaseOrderTransaction WHERE purchaseOrderDetailID = 376;
DELETE FROM transaction WHERE transactionID = 9109;
DELETE FROM stockDetailStatus WHERE stockDetailID = 502;
DELETE FROM stockDetail WHERE stockDetailID = 502;
DELETE FROM purchaseOrderDetail WHERE purchaseOrderDetailID = 376;

DELETE FROM productionBOMItem WHERE productionBOMID = 361;
DELETE FROM productionBOM WHERE productionBOMID = 361;
DELETE FROM stockDetailStatus WHERE stockDetailID = 903;
DELETE FROM stockDetail WHERE stockDetailID = 903;
DELETE FROM stock WHERE stockID = 520;

UPDATE stockDetailStatus SET godownID = 3 WHERE stockDetailStatusID = 6498;

UPDATE stockDetailStatus SET godownID = 2 WHERE stockDetailStatusID = 6095;
UPDATE stockDetail SET godownID = 2 WHERE stockDetailID = 702;

UPDATE stockDetailStatus SET godownID = 4 WHERE stockDetailStatusID = 7701;
UPDATE stockDetail SET godownID = 4 WHERE stockDetailID = 947;

UPDATE stockDetailStatus SET quantity = 8, quantityUnits = 8 WHERE stockDetailStatusID = 4860;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,4 AS godownID,NULL AS bookSerial,1 AS quantity,discount,1 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 4860;

UPDATE stockDetailStatus SET quantity = 2, quantityUnits = 2 WHERE stockDetailStatusID = 4879;
INSERT INTO stockDetailStatus SELECT NULL AS stockDetailStatusID,stockDetailID,NULL AS productionID,1 AS statusID,7 AS batchID,2 AS godownID,NULL AS bookSerial,1 AS quantity,discount,1 AS quantityUnits,NULL AS salePrice,transferDate,createdByUserID,dateCreated FROM stockDetailStatus WHERE stockDetailStatusID = 4879;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 6107;
UPDATE stockDetail SET quantity = 3, quantityUnits = 3 WHERE stockDetailID = 714;

UPDATE stockDetailStatus SET godownID = 4,quantity = 2, quantityUnits = 2 WHERE stockDetailID = 851;
UPDATE stockDetail SET godownID = 4,quantity = 2, quantityUnits = 2 WHERE stockDetailID = 851;
UPDATE productionBOM SET quantity = 2 WHERE productionBOMID = 307;

UPDATE stockDetailStatus SET quantity = 1, quantityUnits = 1 WHERE stockDetailStatusID = 7366;
UPDATE stockDetail SET quantity = 1, quantityUnits = 1 WHERE stockDetailID = 879;
UPDATE productionBOM SET quantity = 1 WHERE productionBOMID = 338;

-------------------------------- Query to Get Products Which are less Purchased but high Sold ------

select stockDetailID,productID,godownID,SUM(totalQuantityPurchased) as purchased,SUM(quantitySold) as sold, SUM(GReturn) as goodReturn,SUM(manufacturing) as manufacturing from (
select
	stockDetailStatus.stockDetailID,
	stockDetail.productID,
	stockDetailStatus.godownID,
	SUM(stockDetailStatus.quantity) AS totalQuantityPurchased,
	0 AS quantitySold,
	0 as GReturn,
	0 as manufacturing
FROM stockDetail
INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
WHERE stockDetailStatus.statusID = 1 and stockDetail.productID = 17
GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
UNION
select
	stockDetailStatus.stockDetailID,
	stockDetail.productID,
	stockDetailStatus.godownID,
	0 AS totalQuantityPurchased,
	SUM(stockDetailStatus.quantity) AS quantitySold,
	0 as GReturn,
	0 as manufacturing
FROM stockDetail
INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
WHERE stockDetailStatus.statusID = 3 and stockDetail.productID = 17
GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
UNION
select
	stockDetailStatus.stockDetailID,
	stockDetail.productID,
	stockDetailStatus.godownID,
	0 AS totalQuantityPurchased,
	0 AS quantitySold,
	SUM(stockDetailStatus.quantity) AS GReturn,
	0 as manufacturing
FROM stockDetail
INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
WHERE stockDetailStatus.statusID = 2 and stockDetail.productID = 17
GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
UNION
select
	stockDetailStatus.stockDetailID,
	stockDetail.productID,
	stockDetailStatus.godownID,
	0 AS totalQuantityPurchased,
	0 AS quantitySold,
	0 AS GReturn,
	SUM(stockDetailStatus.quantity) as manufacturing
FROM stockDetail
INNER JOIN stockDetailStatus ON stockDetailStatus.stockDetailID = stockDetail.stockDetailID
WHERE stockDetailStatus.statusID = 6 and stockDetail.productID = 17
GROUP BY stockDetailStatus.stockDetailID,stockDetail.productID,stockDetailStatus.godownID
) as t
group by stockDetailID,productID,godownID
having sold < purchased
order by godownID,stockDetailID
