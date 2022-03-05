<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('stock_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {

            $query = Stock::getStock();

            $grandTotal = array_sum(array_column($query,'inStockTotalPrice'));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
				$viewGate      = '';
				if (Gate::allows('stock_read')) {
					return '<a class="btn btn-xs btn-primary" href="' . route('stock.view', $row->productID) . '">View Details</a>';
				}
            });

            $table->editColumn('product', function ($row) {
                return $row->productName ? $row->productName : "";
            });
			$table->editColumn('purchased', function ($row) {
				$extra = $row->unitsInProduct > 1 ? " Qty -- " . $row->totalPurchasedUnits . ($row->symbol == "Qty" ? " Items" : " " . $row->symbol) : "";
				return $row->totalPurchasedQuantity . $extra;
            });
			$table->editColumn('inStock', function ($row) {
				$extra = $row->unitsInProduct > 1 ? " Qty -- " . $row->inStockUnits . ($row->symbol == "Qty" ? " Items" : " " . $row->symbol) : "";
				return $row->inStockQuantity > 0 ? $row->inStockQuantity . $extra : "0";
            });
			$table->editColumn('totalSold', function ($row) {
				$extra = $row->unitsInProduct > 1 ? " Qty -- " . $row->totalSoldUnits . ($row->symbol == "Qty" ? " Items" : " " . $row->symbol) : "";
				return $row->totalSoldQuantity > 0 ? $row->totalSoldQuantity . $extra : "0";
            });
			$table->editColumn('totalDamaged', function ($row) {
				$extra = $row->unitsInProduct > 1 ? " Qty -- " . $row->totalDamagedUnits . ($row->symbol == "Qty" ? " Items" : " " . $row->symbol) : "";
				return $row->totalDamagedQuantity > 0 ? $row->totalDamagedQuantity . $extra : "0";
            });
			$table->editColumn('totalBadSalesReturn', function ($row) {
				$extra = $row->unitsInProduct > 1 ? " Qty -- " . $row->totalBadSalesReturnUnits . ($row->symbol == "Qty" ? " Items" : " " . $row->symbol) : "";
				return $row->totalBadSalesReturnQuantity > 0 ? $row->totalBadSalesReturnQuantity . $extra : "0";
            });
			$table->editColumn('totalGoodSalesReturn', function ($row) {
				$extra = $row->unitsInProduct > 1 ? " Qty -- " . $row->totalGoodSalesReturnUnits . ($row->symbol == "Qty" ? " Items" : " " . $row->symbol) : "";
				return $row->totalGoodSalesReturnQuantity > 0 ? $row->totalGoodSalesReturnQuantity . $extra : "0";
            });
            $table->editColumn('lastPurchasePrice', function ($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted($row->lastPurchasePrice);
            });
            $table->editColumn('totalPriceInStock', function ($row) use ($grandTotal) {
                $grandTotal+=$row->inStockTotalPrice;
                return \App\Services\CurrencyService::getCurrencyFormatted($row->inStockTotalPrice);
            });

            $table->rawColumns(['actions', 'placeholder']);
            $table->with('grandTotal', $grandTotal);

            return $table->make(true);
        }

        return view('admin.stock.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('stock_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $products = \App\Models\Product::all()->sortBy('productName');
		$godowns = \App\Models\Godown::all();
		return view('admin.stock.create',compact('products','godowns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		DB::beginTransaction();
		try {
			$request->request->add(['createdByUserID' => Auth::id()]);
			$stock = Stock::create($request->all());
			foreach ($request->productID as $idx => $productID) {
				$updatedQty = $request->quantity[$idx];
				$updatedUnits = $request->quantity[$idx] * \App\Models\Product::find($productID)->unitsInProduct;
				if (\Config::get('constants.client_settings.is_units_in_product_fixed') == 0) {
					$updatedQty = $request->quantity[$idx];
					$updatedUnits = $request->quantityUnits[$idx];
				}
				$stockDetail = $stock->stockDetails()->create([
					'productID' => $productID,
					'quantity' => $updatedQty,
					'quantityUnits' => $updatedUnits,
					'godownID' => $request->godownID[$idx],
					'purchasePrice' => $request->perUnitPrice[$idx]
				]);
				$stockDetail->stockDetailStatuses()->create([
					'statusID' => \Config::get('constants.stock_status.quetta_godown'),
					'batchID' => \App\Services\BatchService::getCurrentBatch()->batchID,
					'quantity' => $updatedQty,
                    'godownID' => $request->godownID[$idx],
					'quantityUnits' => $updatedUnits,
					'createdByUserID' => Auth::id()
				]);

				if ($request->isUpdateProductPrice[$idx] == 1) {
					\App\Models\Product::find($productID)->update(['unitPurchasePrice' => $request->perUnitPrice[$idx]]);
				}
			}
			DB::commit();
			$request->session()->flash('message', 'Stock added successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while creating stock!');
		}
		return redirect()->route('stock.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
		abort_if(Gate::denies('stock_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.stock.show', compact('stock'));
    }

	public function view(int $productID)
    {
		abort_if(Gate::denies('stock_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$stockInfo = Stock::getStock($productID,FALSE,TRUE);
        $product = \App\Models\Product::find($productID);
		$saleOrderIDs = DB::table('stockDetail')
							->join('stockDetailStatus','stockDetailStatus.stockDetailID','=','stockDetail.stockDetailID')
							->join('salesOrderDetail', 'salesOrderDetail.stockDetailStatusID', '=', 'stockDetailStatus.stockDetailStatusID')
							->select('salesOrderDetail.salesOrderID')
							->whereRaw('stockDetail.productID = ' . $productID . ' AND stockDetailStatus.statusID IN (' .\Config::get('constants.stock_status.isIncludeCustomers') . ')')
							->get()->toArray();
		$arySaleOrderIDs = [];
		foreach ($saleOrderIDs as $saleOrderID) {
			$arySaleOrderIDs[] = $saleOrderID->salesOrderID;
		}
		$saleOrders = \App\Models\SalesOrder::with('customer','stockDetailStatuses.stockDetail')->find($arySaleOrderIDs);
		$soldStatusID = \Config::get('constants.stock_status.sold');
		$goodSalesReturnStatusID = \Config::get('constants.stock_status.good_sales_return');
		$badSalesReturnStatusID = \Config::get('constants.stock_status.bad_sales_return');
        $godowns = \App\Models\Godown::orderBy('name','asc')->get();
		return view('admin.stock.view', compact('product','stockInfo','saleOrders','soldStatusID','goodSalesReturnStatusID','badSalesReturnStatusID','godowns'));
    }

    public function transfer(Request $request) {
		abort_if(Gate::denies('stock_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();
		try {
            if ($request->previousGodownID != $request->newGodownID) {
                $stockDetails = \App\Models\Stock::getProductStockDetails($request->productID,$request->previousGodownID);
                $quantityRemaining = $request->quantityToMove;
                $unitsRemaining = $request->unitsToMove;
                foreach ($stockDetails as $stockDetail) {
                    $oldStockDetail = \App\Models\StockDetail::find($stockDetail->stockDetailID);
                    $oldStockDetailStatuses = \App\Models\StockDetailStatus::where('stockDetailID',$stockDetail->stockDetailID)->where('godownID',$request->previousGodownID)->get();

                    $allSoldQty = $oldStockDetailStatuses->where('statusID',\Config::get('constants.stock_status.sold'))->sum('quantity');
                    $allSoldUnits = $oldStockDetailStatuses->where('statusID',\Config::get('constants.stock_status.sold'))->sum('quantityUnits');

                    // $allNAQty = $oldStockDetailStatuses->whereIn('statusID',explode(',',\Config::get('constants.stock_status.isNotAvailableForSale')))->sum('quantity');
                    // $allNAUnits = $oldStockDetailStatuses->whereIn('statusID',explode(',',\Config::get('constants.stock_status.isNotAvailableForSale')))->sum('quantityUnits');
                    //
                    //
                    // $allGoodReturnQty = $oldStockDetailStatuses->where('statusID',\Config::get('constants.stock_status.good_sales_return'))->sum('quantity');
                    // $allGoodReturnUnits = $oldStockDetailStatuses->where('statusID',\Config::get('constants.stock_status.good_sales_return'))->sum('quantityUnits');
                    //
                    // $allAvailableQty = $oldStockDetail->quantity - $allNAQty + $allGoodReturnQty;
                    // $allAvailableUnits = $oldStockDetail->quantityUnits - $allNAUnits + $allGoodReturnUnits;

                    // If Not Sold
                    if ($oldStockDetailStatuses->count() == 1) {
                        // If all qty being moved
                        $firstStockDetailStatus = $oldStockDetailStatuses->first();
                        if ($oldStockDetail->quantity <= $quantityRemaining) {
                            $firstStockDetailStatus->godownID = $request->newGodownID;
                            $firstStockDetailStatus->save();
                            $quantityRemaining -= $oldStockDetail->quantity;
                            $unitsRemaining -= $oldStockDetail->quantityUnits;
                        } else {
                            // If some qty being moved
                            $newStockDetailStatus = $firstStockDetailStatus->replicate()->fill([
                                'quantity' => $quantityRemaining,
                                'quantityUnits' => $unitsRemaining,
                                'godownID' => $request->newGodownID
                            ]);
                            $newStockDetailStatus->save();

                            $quantityRemaining -= $firstStockDetailStatus->quantity;
                            $unitsRemaining -= $firstStockDetailStatus->quantityUnits;

                            $firstStockDetailStatus->quantity = abs($quantityRemaining);
                            $firstStockDetailStatus->quantityUnits = abs($unitsRemaining);
                            $firstStockDetailStatus->save();

                            if ($quantityRemaining < 0 && $unitsRemaining < 0) {
                                $quantityRemaining = 0;
                                $unitsRemaining = 0;
                            }
                        }
                        if ($quantityRemaining == 0) {
                            break;
                        } else {
                            continue;
                        }
                    } else {
                        $oldStockDetailStatusesAvailableForSale = $oldStockDetailStatuses->whereIn('statusID',explode(',',\Config::get('constants.stock_status.isAvailableForSale')));

                        foreach ($oldStockDetailStatusesAvailableForSale as $oldStockDetailStatus) {
                            $remainingStockDetailStatusQty = $oldStockDetailStatus->quantity - $allSoldQty;
                            $remainingStockDetailStatusUnits = $oldStockDetailStatus->quantityUnits - $allSoldUnits;

                            $newStockDetailStatus = $oldStockDetailStatus->replicate()->fill([
                                'quantity' => $remainingStockDetailStatusQty,
                                'quantityUnits' => $remainingStockDetailStatusUnits,
                                'godownID' => $request->newGodownID
                            ]);

                            $salesOrderDetail = null;

                            if ($oldStockDetailStatus->statusID == \Config::get('constants.stock_status.quetta_godown')) {
                                if ($remainingStockDetailStatusQty == 0) {
                                    continue;
                                }
                                if ($remainingStockDetailStatusQty <= $quantityRemaining) {

                                    $oldStockDetailStatus->quantity = $allSoldQty;
                                    $oldStockDetailStatus->quantityUnits = $allSoldUnits;

                                    $quantityRemaining -= $remainingStockDetailStatusQty;
                                    $unitsRemaining -= $remainingStockDetailStatusUnits;

                                } elseif ($remainingStockDetailStatusQty > $quantityRemaining) {

                                    $newStockDetailStatus->quantity = $quantityRemaining;
                                    $newStockDetailStatus->quantityUnits = $unitsRemaining;

                                    $oldStockDetailStatus->quantity = $oldStockDetailStatus->quantity - $quantityRemaining;
                                    $oldStockDetailStatus->quantityUnits = $oldStockDetailStatus->quantityUnits - $unitsRemaining;

                                    $quantityRemaining = 0;
                                    $unitsRemaining = 0;
                                }

                            } else {

                                if ($oldStockDetailStatus->quantity <= $quantityRemaining) {
                                    $newStockDetailStatus = null;
                                    $oldStockDetailStatus->godownID = $request->newGodownID;

                                    $quantityRemaining -= $oldStockDetailStatus->quantity;
                                    $unitsRemaining -= $oldStockDetailStatus->quantityUnits;

                                } elseif ($oldStockDetailStatus->quantity > $quantityRemaining) {

                                    $newStockDetailStatus->quantity = $quantityRemaining;
                                    $newStockDetailStatus->quantityUnits = $unitsRemaining;

                                    $oldStockDetailStatus->quantity = $oldStockDetailStatus->quantity - $quantityRemaining;
                                    $oldStockDetailStatus->quantityUnits = $oldStockDetailStatus->quantityUnits - $unitsRemaining;

                                    $quantityRemaining = 0;
                                    $unitsRemaining = 0;

                                    $salesOrderDetail = \App\Models\SalesOrderDetail::where('stockDetailStatusID',$oldStockDetailStatus->stockDetailStatusID)->first();
                                }
                            }

                            if ($newStockDetailStatus != null) {
                                $newStockDetailStatus->save();

                                if ($salesOrderDetail != null) {
                                    $salesOrderDetail->replicate()->fill([
                                        'stockDetailStatusID' => $newStockDetailStatus->id
                                    ]);
                                    $salesOrderDetail->save();
                                }
                            }

                            $oldStockDetailStatus->save();

                            if ($quantityRemaining == 0) {
                                break;
                            } else {
                                continue;
                            }
                        }
                    }

                    if ($quantityRemaining == 0) {
                        break;
                    }
                }

                $deletedRows = \App\Models\StockDetailStatus::where('quantity',0)->where('quantityUnits', 0)->delete();

                if ($quantityRemaining != 0 || $unitsRemaining != 0) {
                    DB::rollback();
                    $request->session()->flash('error', 'Stock does not match!');
                } else {
                    DB::commit();
                    $request->session()->flash('message', 'Stock transferred successfully!');
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            $request->session()->flash('error', 'An error occurred while transferring stock!');
        }
        return redirect()->route('stock.view',$request->productID);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    // public function edit(Stock $stock)
    // {
	// 	abort_if(Gate::denies('stock_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    //     $stockTypes = \App\Models\StockType::all()->sortBy('stockType');
    //     return view('admin.stock.edit', compact('stock','stockTypes'));
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    // public function update(UpdateStockRequest $request, Stock $stock)
    // {
	// 	DB::beginTransaction();
	// 	try {
	// 		\App\Models\AccountHead::updateAccountHead($stock->headID,$request->stockName . ' (Stock)');
	// 		$stock->update($request->all());
	// 		DB::commit();
	// 		$request->session()->flash('message', 'Stock updated successfully!');
	// 	} catch (\Exception $e) {
	// 		DB::rollback();
	// 		$request->session()->flash('error', 'An error occurred while updating stock!');
	// 	}
	// 	return redirect()->route('stock.index');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Stock $stock,Request $request)
    // {
	// 	abort_if(Gate::denies('stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
	// 	DB::beginTransaction();
	// 	try {
	// 		$stock->delete();
	// 		$stock->head->delete();
	// 		DB::commit();
	// 		$request->session()->flash('message', 'Stock deleted successfully!');
	// 	} catch (\Exception $e) {
	// 		DB::rollback();
	// 		dd($e);
	// 		$request->session()->flash('error', 'An error occurred while deleting stock!');
	// 	}
    //     return redirect()->route('stock.index');
    // }
}
