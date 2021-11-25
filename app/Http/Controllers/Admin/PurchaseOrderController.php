<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Gate;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('purchase_order_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
			$query = DB::table('purchaseOrder')
			            ->leftJoin('supplier','supplier.supplierID','=','purchaseOrder.supplierID')
						->leftJoin('customer','customer.customerID','=','purchaseOrder.customerID')
			            ->join('batch','batch.batchID','=','purchaseOrder.batchID')
			            ->join('purchaseOrderDetail','purchaseOrderDetail.purchaseOrderID','=','purchaseOrder.purchaseOrderID')
						->select(DB::raw('purchaseOrder.*,supplier.supplierName,customer.customerName,batch.batchName,SUM(' . \App\Services\CurrencyService::strQueryFormulaToPKRConversion(). ') as totalInPKR,(' . \App\Services\PurchaseOrderService::getPurchaseOrderExpensesSubQuery() . ') AS totalExpenseInPKR'))
						->groupBy('purchaseOrder.purchaseOrderID')
                        ->orderBy('purchaseOrder.dateCreated','desc')
			            ->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'purchase_order_read';
				$editGate = '';
				$deleteGate = '';
				if ($row->isLocked == 0) {
					$editGate      = 'purchase_order_update';
	                $deleteGate    = 'purchase_order_delete';
				}

                $crudRoutePart = 'purchase';
                $primaryKey = 'purchaseOrderID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

			$table->editColumn('supplierCustomer', function ($row) {
                return $row->supplierName ? '<strong>Supplier: </strong>' . $row->supplierName : '<strong>Customer: </strong>' . $row->customerName;
            });
			$table->editColumn('batchName', function ($row) {
                return $row->batchName ? $row->batchName : "";
            });
			$table->editColumn('purchaseOrderDate', function ($row) {
                return $row->purchaseOrderDate ? $row->purchaseOrderDate : "";
            });
			$table->editColumn('totalInPKR', function ($row) {
				return \App\Services\CurrencyService::getCurrencyFormatted($row->totalInPKR);
            });
			$table->editColumn('totalExpenseInPKR', function ($row) {
				return \App\Services\CurrencyService::getCurrencyFormatted($row->totalExpenseInPKR);
            });
			$table->editColumn('grandTotal', function ($row) {
				return \App\Services\CurrencyService::getCurrencyFormatted($row->totalInPKR + $row->totalExpenseInPKR);
            });
            $table->rawColumns(['actions', 'placeholder','supplierCustomer']);

            return $table->make(true);
        }

        return view('admin.purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('purchase_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$suppliers = \App\Models\Supplier::all()->sortBy('supplierName');
		$customers = \App\Models\Customer::all()->sortBy('customerName');
		$batches = \App\Models\Batch::all()->sortBy('batchName');
		$products = \App\Models\Product::all()->sortBy('productName');
		$currentBatch = \App\Services\BatchService::getCurrentBatch();
        $godowns = \App\Models\Godown::all();
        $purchaseOrderExpense = \App\Models\AccountHead::with('childrenAccountHeads')->whereRaw('parentHeadID = ' . \Config::get('constants.account_heads.expense') . ' AND isShowForPurchaseOrderExpense = 1')->get();
		//$purchaseOrderExpense = \App\Models\AccountHead::with('childrenAccountHeads')->where('isShowForPurchaseOrderExpense', 1)->get();
		//dd($purchaseOrderExpense);
		return view('admin.purchase.create',compact('suppliers','batches','products','currentBatch','purchaseOrderExpense','customers','godowns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseOrderRequest $request)
    {
		DB::beginTransaction();
		try {
            (new \App\Services\PurchaseOrderService())->save($request);
			DB::commit();
			$request->session()->flash('message', 'Purchase Order created successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while creating purchase order!');
		}
        return redirect()->route('purchase.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchase)
    {
		abort_if(Gate::denies('purchase_order_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $lastGodownHeadID = 0;
        if (is_numeric($purchase->lastGodownID)) {
            $lastGodownHeadID = \App\Models\Godown::find($purchase->lastGodownID)->headID;
        }
		$totalPayable = 0;
		if ($purchase->supplier) {
			$totalPayable = \App\Models\Supplier::getBalance($purchase->supplierID)[0]->totalPayable;
		} else {
			$totalPayable = \App\Models\Customer::getBalance($purchase->customerID)[0]->totalPayable;
		}
		return view('admin.purchase.show', compact('purchase','lastGodownHeadID','totalPayable'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchase)
    {
		abort_if(Gate::denies('purchase_order_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$suppliers = \App\Models\Supplier::all()->sortBy('supplierName');
		$customers = \App\Models\Customer::all()->sortBy('customerName');
		$batches = \App\Models\Batch::all()->sortBy('batchName');
		$products = \App\Models\Product::all()->sortBy('productName');
        $godowns = \App\Models\Godown::all();
		$totalPayable = 0;
		if ($purchase->supplier) {
			$totalPayable = \App\Models\Supplier::getBalance($purchase->supplierID)[0]->totalPayable;
		} else {
			$totalPayable = \App\Models\Customer::getBalance($purchase->customerID)[0]->totalPayable;
		}
		$purchaseOrderExpense = \App\Models\AccountHead::with('childrenAccountHeads')->whereRaw('parentHeadID = ' . \Config::get('constants.account_heads.expense') . ' AND isShowForPurchaseOrderExpense = 1')->get();
        return view('admin.purchase.edit', compact('purchase','suppliers','batches','products','purchaseOrderExpense','customers','godowns','totalPayable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchase)
    {
		DB::beginTransaction();
		try {
            (new \App\Services\PurchaseOrderService())->update($request,$purchase);
			DB::commit();
			$request->session()->flash('message', 'Purchase Order updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while updating purchase order!');
		}

        return redirect()->route('purchase.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchase, Request $request)
    {
		abort_if(Gate::denies('purchase_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		if ($purchase->isLocked == 1) {
			$request->session()->flash('warning', 'You cannot delete this purchase order!');
			return view('purchase.index');
		}
		DB::beginTransaction();
		try {
			$POTransactions = $purchase->transactions;
			$purchase->transactions()->detach();
			foreach ($POTransactions as $transaction) {
				\App\Services\TransactionService::deleteTransaction($transaction->transactionID);
			}
			$purchase->purchaseOrderDetails()->delete();
			$purchase->delete();
			DB::commit();
			$request->session()->flash('message', 'Purchase Order deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while deleting purchase order!');
		}
        return redirect()->route('purchase.index');
    }

	public function shiftToStock(int $purchaseOrderID, Request $request)
	{
		// Put a check if purchaseOrder is already added to Stock
		DB::beginTransaction();
		try {
			$purchase = PurchaseOrder::find($purchaseOrderID);
            if ($purchase->isLocked == 1) {
                $request->session()->flash('warning', 'Purchase order already in stock!');
    			return redirect()->route('purchase.show', $purchaseOrderID);
            }
            $totals = \App\Services\PurchaseOrderService::getPurchaseOrderPricesTotal($purchaseOrderID);
			$totalExpenseInPKR = $totals->totalExpenseInPKR;
			$totalProductsPriceInPKR = $totals->totalInPKR;

			// Add in stock table
			$stock = new \App\Models\Stock();
			$stock->purchaseOrderID = $purchaseOrderID;
			$stock->createdByUserID = Auth::id();
			$stock->save();

			// Add in stockDetail table
			foreach ($purchase->purchaseOrderDetails as $purchaseOrderDetail) {
				\App\Services\PurchaseOrderService::insertStockInfo($stock,$purchase,$purchaseOrderDetail,$totalProductsPriceInPKR,$totalExpenseInPKR);
			}

			// Set purchaseOrder isLocked to 1
			$purchase->update(['isLocked' => 1]);
			DB::commit();
			return redirect()->route('stock.index');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while shifting to stock!');
			return redirect()->route('purchase.show', $purchaseOrderID);
		}
	}

	public function customizeShift(int $purchaseOrderID, Request $request) {
		dd('Needs fixation');
        $purchase = PurchaseOrder::find($purchaseOrderID);
        if ($purchase->isLocked == 1) {
            $request->session()->flash('warning', 'Purchase order already in stock!');
            return redirect()->route('purchase.show', $purchaseOrderID);
        }
        $lastGodownHeadID = 0;
        if (is_numeric($purchase->lastGodownID)) {
            $lastGodownHeadID = \App\Models\Godown::find($purchase->lastGodownID)->headID;
        }
		return view('admin.purchase.customizeShift', compact('purchase','lastGodownHeadID'));
	}

    public function doCustomizedShift(Request $request) {
        DB::beginTransaction();
		try {
            $aryNewPurchaseOrder = [];
            $purchaseOrderID = $request->request->get('purchaseOrderID');
            $purchase = PurchaseOrder::find($purchaseOrderID);
            if ($purchase->isLocked == 1) {
                $request->session()->flash('warning', 'Purchase order already in stock!');
    			return redirect()->route('purchase.show', $purchaseOrderID);
            }
            $totals = \App\Services\PurchaseOrderService::getPurchaseOrderPricesTotal($purchaseOrderID);

            $totalExpenseInPKR = $totals->totalExpenseInPKR;
			$totalProductsPriceInPKR = $totals->totalInPKR;

            $aryNewPurchaseOrder['purchaseOrder'] = ['parentID' => $purchaseOrderID,
                                                        'supplierID' => $purchase->supplierID,
                                                        'customerID' => $purchase->customerID,
                                                        'batchID' => $purchase->batchID,
                                                        'lastGodownID' => $purchase->lastGodownID,
                                                        'isLocked' => 0,
                                                        'purchaseOrderDate' => $purchase->purchaseOrderDate,
                                                        'description' => $purchase->description,
                                                        'createdByUserID' => $purchase->createdByUserID
                                                    ];

			// Add in stock table
			$stock = new \App\Models\Stock();
			$stock->purchaseOrderID = $purchaseOrderID;
			$stock->createdByUserID = Auth::id();
			$stock->save();

            $pattern = "/quantity_\d+/i";
            $productsCtr = 0;
            $productsQtyZeroCtr = 0;
            $originalProductsTotalAmount = 0;
			$originalProductsForeignTotalAmount = 0;
            $newProductsTotalAmount = 0;
			$newProductsForeignTotalAmount = 0;
            $purchaseOrderTransactionsWithExpense = $purchase->transactions()->having('isExpense',1)->get();
            foreach ($request->request->all() as $key => $value) {
                if (preg_match($pattern,$key)) {
                    $purchaseOrderDetail = PurchaseOrderDetail::find(explode("_",$key)[1]);
                    if ($purchaseOrderDetail->quantity < $value) {
                        DB::rollback();
                        $request->session()->flash('warning', 'Quantity should NOT be greater than available!');
            			return redirect()->route('purchase.show', $purchaseOrderID);
                    }
                    $purchaseOrderTransaction = $purchase->transactions()->having('purchaseOrderDetailID', $purchaseOrderDetail->purchaseOrderDetailID)->first();
                    $oldTotalInPKR = $purchaseOrderDetail->perUnitPrice * $purchaseOrderDetail->quantity;
					$oldTotal = $purchaseOrderDetail->foreignPerUnitPrice * $purchaseOrderDetail->quantity;
                    $newTotalInPKR = $purchaseOrderDetail->perUnitPrice * ($purchaseOrderDetail->quantity - $value);
					$newTotal = $purchaseOrderDetail->foreignPerUnitPrice * ($purchaseOrderDetail->quantity - $value);
					$originalProductsTotalAmount+=$oldTotalInPKR;
					$originalProductsForeignTotalAmount+=$oldTotal;
                    $productsCtr++;

                    if ($value > 0) {
                        $newProductsTotalAmount+=($oldTotalInPKR - $newTotalInPKR);
						$newProductsForeignTotalAmount+=($oldTotal - $newTotal);
                        \App\Services\PurchaseOrderService::insertStockInfo($stock,$purchase,$purchaseOrderDetail,$totalProductsPriceInPKR,$totalExpenseInPKR,$value);

                        if ($purchaseOrderDetail->quantity > $value) {
                            $newTransactionDetail = [];
                            foreach ($purchaseOrderTransaction->transactionDetails as $transactionDetail) {
                                $newTransactionDetail[] = ['transactionID' => 0, 'headID' => $transactionDetail->headID, 'subHeadID' => $transactionDetail->subHeadID,'isDebit' => $transactionDetail->isDebit,'amount' => $newTotal,'description' => $transactionDetail->description];
                                // Update Transaction Detail
								\App\Services\TransactionService::updateTransactionDetail($transactionDetail->transactionDetailID,['amount' => $transactionDetail->foreignAmount - $newTotal]);
                            }
                            $newTransaction = ['transactionID' => 0,'transactionTypeID' => 1,'batchID' => $purchase->batchID, 'exchangeRate' => $purchaseOrderDetail->exchangeRate, 'createdByUserID' => $purchase->createdByUserID, 'transactionDetail' => $newTransactionDetail];
                            $aryNewPurchaseOrder['purchaseOrderDetail'][] = ['purchaseOrderID' => 0,'productID' => $purchaseOrderDetail->productID,'quantity' => $purchaseOrderDetail->quantity - $value, 'exchangeRate' => $purchaseOrderDetail->exchangeRate, 'perUnitPrice' => $purchaseOrderDetail->perUnitPrice,'foreignPerUnitPrice' => $purchaseOrderDetail->foreignPerUnitPrice,'transaction' => $newTransaction];
                            $purchaseOrderDetail->quantity = $value;
                            $purchaseOrderDetail->save();
                        }
                    } elseif ($value == 0) {
                        $productsQtyZeroCtr++;

                        $newTransactionDetail = [];
                        foreach ($purchaseOrderTransaction->transactionDetails as $transactionDetail) {
                            $newTransactionDetail[] = ['transactionID' => 0, 'headID' => $transactionDetail->headID, 'subHeadID' => $transactionDetail->subHeadID,'isDebit' => $transactionDetail->isDebit,'amount' => $transactionDetail->foreignAmount,'description' => $transactionDetail->description];
                        }
                        $newTransaction = ['transactionID' => 0,'transactionTypeID' => 1,'batchID' => $purchase->batchID, 'exchangeRate' => $purchaseOrderDetail->exchangeRate, 'createdByUserID' => $purchase->createdByUserID, 'transactionDetail' => $newTransactionDetail];
                        $aryNewPurchaseOrder['purchaseOrderDetail'][] = ['purchaseOrderID' => 0,'productID' => $purchaseOrderDetail->productID,'quantity' => $purchaseOrderDetail->quantity, 'exchangeRate' => $purchaseOrderDetail->exchangeRate, 'perUnitPrice' => $purchaseOrderDetail->perUnitPrice, 'foreignPerUnitPrice' => $purchaseOrderDetail->foreignPerUnitPrice,'transaction' => $newTransaction];

                        DB::table('purchaseOrderTransaction')->where('transactionID',$purchaseOrderTransaction->pivot->transactionID)->delete();
                        DB::table('purchaseOrderDetail')->where('purchaseOrderDetailID',$purchaseOrderTransaction->pivot->purchaseOrderDetailID)->delete();
                        \App\Models\Transaction::deleteTransaction($purchaseOrderTransaction->pivot->transactionID);
                    }
                }
            }

            if ($productsCtr == $productsQtyZeroCtr) {
                DB::rollback();
                $request->session()->flash('error', 'Quantity should be more than 1');
    			return redirect()->route('purchase.show', $purchaseOrderID);
            }

            $purchase->update(['isLocked' => 1]);

            $expensePercent = ($newProductsTotalAmount * 100) / $originalProductsTotalAmount;
			$expensePercentForeign = ($newProductsForeignTotalAmount * 100) / $originalProductsForeignTotalAmount;

            foreach ($purchaseOrderTransactionsWithExpense as $transaction) {
                $newTransactionDetail = [];
                foreach ($transaction->transactionDetails as $transactionDetail) {
                    $newAmount = ($expensePercent / 100) * $transactionDetail->amount;
					$newAmountForeign = ($expensePercentForeign / 100) * $transactionDetail->foreignAmount;
                    $changedAmount = $transactionDetail->amount - $newAmount;
					$changedAmountForeign = $transactionDetail->foreignAmount - $newAmountForeign;
                    $newTransactionDetail[] = ['transactionID' => 0, 'headID' => $transactionDetail->headID, 'subHeadID' => $transactionDetail->subHeadID,'isDebit' => $transactionDetail->isDebit,'amount' => $changedAmountForeign,'description' => $transactionDetail->description];
					\App\Services\TransactionService::updateTransactionDetail($transactionDetail->transactionDetailID,['amount' => $transactionDetail->foreignAmount - $changedAmountForeign]);
                }
                $aryNewPurchaseOrder['expenseTransactions'][] = ['transactionTypeID' => 1,'batchID' => $transaction->batchID, 'exchangeRate' => $transaction->exchangeRate, 'createdByUserID' => $transaction->createdByUserID, 'transactionDetail' => $newTransactionDetail];
            }

            if (array_key_exists('purchaseOrderDetail',$aryNewPurchaseOrder)) {
                $newPurchaseOrderID = DB::table('purchaseOrder')->insertGetId($aryNewPurchaseOrder['purchaseOrder']);
                foreach ($aryNewPurchaseOrder['purchaseOrderDetail'] as $purchaseOrderDetail) {
                    Arr::set($purchaseOrderDetail,'purchaseOrderID', $newPurchaseOrderID);
                    $newPurchaseOrderDetailID = DB::table('purchaseOrderDetail')->insertGetId(Arr::except($purchaseOrderDetail,['transaction']));

                    $newTransactionID = \App\Services\TransactionService::addTransactionArray($purchaseOrderDetail['transaction']);

                    foreach ($purchaseOrderDetail['transaction']['transactionDetail'] as $transactionDetail) {
                        Arr::set($transactionDetail,'transactionID', $newTransactionID);
						\App\Services\TransactionService::addTransactionDetailArray($transactionDetail);
                    }

                    DB::table('purchaseOrderTransaction')->insert(['purchaseOrderID' => $newPurchaseOrderID, 'transactionID' => $newTransactionID, 'purchaseOrderDetailID' => $newPurchaseOrderDetailID, 'isExpense' => 0]);
                }

                foreach ($aryNewPurchaseOrder['expenseTransactions'] as $expenseTransaction) {
                    $newTransactionID = DB::table('transaction')->insertGetId(Arr::except($expenseTransaction,['transactionDetail']));
                    foreach ($expenseTransaction['transactionDetail'] as $transactionDetail) {
                        Arr::set($transactionDetail,'transactionID', $newTransactionID);
						\App\Services\TransactionService::addTransactionDetailArray($transactionDetail);
                    }

                    DB::table('purchaseOrderTransaction')->insert(['purchaseOrderID' => $newPurchaseOrderID, 'transactionID' => $newTransactionID, 'isExpense' => 1]);
                }
            }

            // DB::rollback();

            DB::commit();
            return redirect()->route('stock.index');
        } catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while shifting to stock!');
			return redirect()->route('purchase.show', $purchaseOrderID);
		}
    }
}
