<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);

Route::redirect('/','/admin/dashboard');

// Route::group(['prefix' => 'cronjob'], function() {
//     Route::get('createAccruedSalaries',[App\Http\Controllers\CronjobController::class,'createAccruedSalaries'])->name();
// });

Route::group(['middleware' => 'auth','prefix' => 'admin'], function() {
	Route::post('ajax/{method}', [App\Http\Controllers\Admin\AjaxController::class, 'handle'])->name('ajax.handle');
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/search', [App\Http\Controllers\Admin\DashboardController::class, 'search'])->name('dashboard.search');
    Route::get('/dashboard/test', [App\Http\Controllers\Admin\DashboardController::class, 'test'])->name('dashboard.test');
	Route::get('purchase/{purchaseOrderID}/shift', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'shiftToStock'])->name('purchase.shift');
	Route::get('purchase/{purchaseOrderID}/customizeShift', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'customizeShift'])->name('purchase.customizeShift');
	Route::get('accountHead/trialBalance', [App\Http\Controllers\Admin\AccountHeadController::class, 'trialBalance'])->name('accountHead.trialBalance');
    Route::delete('accountHead/delete', [App\Http\Controllers\Admin\AccountHeadController::class, 'transactionDestroy'])->name('accountHead.transactionDestroy');
	Route::get('accountHead/paymentsReceipts', [App\Http\Controllers\Admin\AccountHeadController::class, 'paymentsReceipts'])->name('accountHead.paymentsReceipts');
    Route::get('accountHead/payment', [App\Http\Controllers\Admin\AccountHeadController::class, 'newPayment'])->name('accountHead.payment');
	Route::get('accountHead/receipt', [App\Http\Controllers\Admin\AccountHeadController::class, 'newReceipt'])->name('accountHead.receipt');
	Route::get('accountHead/cheques', [App\Http\Controllers\Admin\AccountHeadController::class, 'cheques'])->name('accountHead.cheques');
	Route::get('accountHead/{bankInstrumentDetailID}/{bankInstrumentStatusID}/updateCheque', [App\Http\Controllers\Admin\AccountHeadController::class, 'updateCheques'])->name('accountHead.updateCheques');
	Route::post('accountHead/doPayment', [App\Http\Controllers\Admin\AccountHeadController::class, 'doPayment'])->name('accountHead.doPayment');
	Route::post('accountHead/doReceipt', [App\Http\Controllers\Admin\AccountHeadController::class, 'doReceipt'])->name('accountHead.doReceipt');
	Route::get('accountHead/openingBalance', [App\Http\Controllers\Admin\AccountHeadController::class, 'openingBalance'])->name('accountHead.openingBalance');
	Route::post('accountHead/updateOpeningBalance', [App\Http\Controllers\Admin\AccountHeadController::class, 'updateOpeningBalance'])->name('accountHead.updateOpeningBalance');
    Route::post('purchase/doCustomizedShift', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'doCustomizedShift'])->name('purchase.doCustomizedShift');
	Route::get('stock/{productID}/view', [App\Http\Controllers\Admin\StockController::class, 'view'])->name('stock.view');
    Route::post('stock/transfer', [App\Http\Controllers\Admin\StockController::class,'transfer'])->name('stock.transfer');
	Route::get('stock/createMultiTransfer', [App\Http\Controllers\Admin\StockController::class,'createMultiTransfer'])->name('stock.createMultiTransfer');
	Route::post('stock/multiTransfer', [App\Http\Controllers\Admin\StockController::class,'multiTransfer'])->name('stock.multiTransfer');
	Route::get('stock/reduceStock', [App\Http\Controllers\Admin\StockController::class,'reduceStockFix'])->name('stock.reduceStockFix');
	Route::get('stock/stockFix', [App\Http\Controllers\Admin\StockController::class,'stockFix'])->name('stock.stockFix');
	Route::get('sales/{salesOrderID}/invoice', [App\Http\Controllers\Admin\SalesOrderController::class, 'invoice'])->name('sales.invoice');
	Route::get('sales/{salesOrderID}/invoicePDF', [App\Http\Controllers\Admin\SalesOrderController::class, 'invoicePDF'])->name('sales.invoicePDF');
	Route::get('customer/{customerID}/balance', [App\Http\Controllers\Admin\CustomerController::class, 'getBalance'])->name('customer.balance');
    Route::get('staff/{staffID}/balance', [App\Http\Controllers\Admin\StaffController::class, 'getBalance'])->name('staff.balance');
	Route::get('supplier/{supplierID}/balance', [App\Http\Controllers\Admin\SupplierController::class, 'getBalance'])->name('supplier.balance');
	Route::get('godown/{godownID}/balance', [App\Http\Controllers\Admin\GodownController::class, 'getBalance'])->name('godown.balance');
	Route::get('godown/{godownID}/balanceByHead', [App\Http\Controllers\Admin\GodownController::class, 'getBalanceByHeadID'])->name('godown.balanceByHead');
	Route::get('transport/{transportID}/balance', [App\Http\Controllers\Admin\TransportController::class, 'getBalance'])->name('transport.balance');
	Route::get('transport/{transportID}/balanceByHead', [App\Http\Controllers\Admin\TransportController::class, 'getBalanceByHeadID'])->name('transport.balanceByHead');
    Route::post('invoiceBooks/updateBookSerials', [App\Http\Controllers\Admin\InvoiceBooksController::class, 'updateBookSerials'])->name('invoiceBooks.updateBookSerials');
    Route::get('invoiceBooks/{bookType}/nextSerial', [App\Http\Controllers\Admin\InvoiceBooksController::class, 'getNextSerialNumber'])->name('invoiceBooks.nextSerial');
	Route::post('invoiceBooks/voidSerial', [App\Http\Controllers\Admin\InvoiceBooksController::class, 'voidSerialNumber'])->name('invoiceBooks.voidSerial');
	Route::get('production/list', [App\Http\Controllers\Admin\ProductionController::class, 'list'])->name('production.list');
	Route::get('production/view/{production}', [App\Http\Controllers\Admin\ProductionController::class, 'view'])->name('production.view');
	Route::get('production/{production}/change', [App\Http\Controllers\Admin\ProductionController::class, 'change'])->name('production.change');
	Route::get('production/new', [App\Http\Controllers\Admin\ProductionController::class, 'new'])->name('production.new');
	Route::post('production/save', [App\Http\Controllers\Admin\ProductionController::class, 'save'])->name('production.save');
	Route::post('production/{production}/changeUpdate', [App\Http\Controllers\Admin\ProductionController::class, 'changeUpdate'])->name('production.changeUpdate');
	Route::get('production/{production}/nextStage', [App\Http\Controllers\Admin\ProductionController::class, 'nextStage'])->name('production.nextStage');
    Route::get('sales/returns', [App\Http\Controllers\Admin\SalesOrderController::class, 'returns'])->name('sales.returns');
    Route::get('sales/returns/{sale}', [App\Http\Controllers\Admin\SalesOrderController::class, 'create_return'])->name('sales.create_return');
    Route::post('sales/returns/{sale}', [App\Http\Controllers\Admin\SalesOrderController::class, 'add_return'])->name('sales.add_return');
    Route::resource('user', App\Http\Controllers\Admin\UserController::class);
    Route::resource('role', App\Http\Controllers\Admin\RoleController::class);
	Route::resource('product', App\Http\Controllers\Admin\ProductController::class);
	Route::resource('production', App\Http\Controllers\Admin\ProductionController::class);
	Route::resource('category', App\Http\Controllers\Admin\CategoryController::class);
	Route::resource('supplier', App\Http\Controllers\Admin\SupplierController::class);
	Route::resource('batch', App\Http\Controllers\Admin\BatchController::class);
	Route::resource('purchase', App\Http\Controllers\Admin\PurchaseOrderController::class);
	Route::resource('accountHead', App\Http\Controllers\Admin\AccountHeadController::class);
	Route::resource('godown', App\Http\Controllers\Admin\GodownController::class);
	Route::resource('transport', App\Http\Controllers\Admin\TransportController::class);
	Route::resource('bankAccount', App\Http\Controllers\Admin\BankAccountController::class);
	Route::resource('staffType', App\Http\Controllers\Admin\StaffTypeController::class);
	Route::resource('staff', App\Http\Controllers\Admin\StaffController::class);
    Route::resource('attendance', App\Http\Controllers\Admin\AttendanceController::class);
	Route::resource('stock', App\Http\Controllers\Admin\StockController::class);
	Route::resource('sales', App\Http\Controllers\Admin\SalesOrderController::class);
	Route::resource('customer', App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('invoiceBooks', App\Http\Controllers\Admin\InvoiceBooksController::class);
	Route::resource('setting', App\Http\Controllers\Admin\SettingController::class)->only(['edit','update']);
	Route::get('report', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('report.index');
	Route::get('report/profitLoss', [App\Http\Controllers\Admin\ReportController::class, 'profitLoss'])->name('report.profitLoss');
	Route::get('report/cashInOut', [App\Http\Controllers\Admin\ReportController::class, 'cashInOut'])->name('report.cashInOut');
    Route::get('report/accounts', [App\Http\Controllers\Admin\ReportController::class, 'accounts'])->name('report.accounts');
	Route::get('report/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('report.sales');
    Route::get('report/duplicates', [App\Http\Controllers\Admin\ReportController::class, 'duplicates'])->name('report.duplicates');
    Route::get('report/missing', [App\Http\Controllers\Admin\ReportController::class, 'missings'])->name('report.missings');
	Route::get('report/daySummary', [App\Http\Controllers\Admin\ReportController::class, 'daySummary'])->name('report.daySummary');
	Route::get('report/rangeSummary', [App\Http\Controllers\Admin\ReportController::class, 'rangeSummary'])->name('report.rangeSummary');
	Route::get('report/receivables', [App\Http\Controllers\Admin\ReportController::class, 'receivables'])->name('report.receivables');
	Route::get('report/monthlyDefaulters', [App\Http\Controllers\Admin\ReportController::class, 'monthlyDefaulters'])->name('report.monthlyDefaulters');
	Route::get('report/stockTransfer', [App\Http\Controllers\Admin\ReportController::class, 'stockTransfer'])->name('report.stockTransfer');
});
