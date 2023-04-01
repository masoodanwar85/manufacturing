<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Production;
use App\Models\ProductionBOM;
use App\Models\ProductionBOMItem;
use App\Models\ProductionBOMExpense;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('production_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {

            $query = ProductionBOM::with(['product','items','expenses'])->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'production_read';
                $editGate      = 'production_update';
                $deleteGate    = 'production_delete';
                $crudRoutePart = 'production';
                $primaryKey = 'productionBOMID';
                $startButton = '<a onclick="return confirm(\'Are you sure you want to move it to next stage?\');" class="btn btn-warning btn-xs" href="' . route('production.nextStage', $row->productionBOMID) . '">Next Stage</a>';
                if ($row->productionStageID == 2) {
                    $startButton = '';
                    $editGate = '';
                    $deleteGate = '';
                }
                return view('partials.datatablesActions', compact(
                    'startButton',
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('productName', function ($row) {
                return $row->product->productName;
            });
			$table->editColumn('productionStage', function ($row) {
                return $row->productionStageID == 0 ? "Draft" : ($row->productionStageID == 1 ? "In-Process" : "Finished");
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.production.index');
    }

    public function list(Request $request)
    {
		abort_if(Gate::denies('production_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {

            $query = Production::with(['boms.product','boms.items','boms.expenses'])->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'productions_read';
                $showGate      = 'production_read';
                $editGate      = 'productions_update';
                $changeGate      = 'production_update';
                $deleteGate    = 'production_delete';
                $crudRoutePart = 'production';
                $primaryKey = 'productionID';
                $showGateRoute = 'production.view';
                $editGateRoute = 'production.change';
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'showGate',
                    'changeGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'showGateRoute',
                    'editGateRoute',
                    'primaryKey'
                ));
            });

            $table->editColumn('productName', function ($row) {
                $products = "";
                foreach ($row->boms as $bom) {
                    $products .= $bom->product->productName . ' ( ' . $bom->quantity . ' ) ';
                    $products .= ' ';
                }
                return $products;
            });
			$table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.production.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('production_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $BOMProducts = \App\Models\Product::with(['category','BOM.items','BOM.expenses.head'])->where('isBOM',1)->get()->sortBy('productName');
        return view('admin.production.create',compact('BOMProducts'));
    }

    public function new()
    {
        abort_if(Gate::denies('production_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $BOMProducts = \App\Models\Product::with(['BOM.items','BOM.expenses.head','category'])->where('isBOM',1)->get()->sortBy('productName');
        $now = date('Y-m-d');
        return view('admin.production.new',compact('BOMProducts','now'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('production_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
        DB::beginTransaction();
        $is_success = true;
        
        try {
            $request->merge(['createdByUserID' => Auth::id()]);
            $request->merge(['productionStageID' => \Config::get('constants.production_stages.finished')]);

            $productionBOM = ProductionBOM::create($request->all());

            foreach ($request->productItemID as $idx => $thisProductItemID) {
                $productionBOMItem = ProductionBOMItem::create([
                    'productionBOMID' => $productionBOM->productionBOMID,
                    'productID' => $thisProductItemID,
                    'quantity' => $request->productItemQuantity[$idx],
                    'unitPrice' => $request->productItemPrice[$idx],
                    'createdByUserID' => Auth::id()
                ]);
            }

            if ($request->filled('expenseHeadID')) {
                foreach ($request->expenseHeadID as $idx => $thisExpenseHeadID) {
                    $productionBOMExpense = ProductionBOMExpense::create([
                        'productionBOMID' => $productionBOM->productionBOMID,
                        'expenseHeadID' => $thisExpenseHeadID,
                        'amount' => $request->expenseAmount[$idx],
                        'createdByUserID' => Auth::id()
                    ]);
                }
            }

            //Add stockDetailStatus with statusID =  $retStatus = $this->addStockDetailStatus($production->productionBOMID);

            // if ($productionBOM->productionStageID == \Config::get('constants.production_stages.finished')) {}

            $retStatus = $this->addStockDetailStatus($productionBOM->productionBOMID);
            $is_success = $retStatus['success'];
            $errorMsg = $retStatus['message'];

            if (!$is_success) {
                DB::rollback();
                $request->session()->flash('error', $errorMsg);
            } else {
                $stockToAdd = [];
                foreach ($request->productItemID as $idx => $thisProductItemID) {
                    $thisProduct = $productionBOM->items()->where('productID', $thisProductItemID)->first();
                    $thisProduct->update([
                        'consumed' => $request->productItemQuantity[$idx]
                    ]);
    
                    // If Quantity is Less, then put back to Factory Stock
                    if ($request->productItemQuantity[$idx] < $thisProduct->quantity) {
                        // Add this product to stock
                        $stockToAdd[] = ['productID' => $thisProductItemID, 'quantityToAdd' => $thisProduct->quantity - $request->productItemQuantityConsumed[$idx]];
                    }
                }
    
                if (count($stockToAdd) > 0) {
                    $stock = \App\Models\Stock::create([
                        'productionBOMID' => $productionBOM->productionBOMID,
                        'createdByUserID' => Auth::id()
                    ]);
    
                    foreach ($stockToAdd as $thisStock) {
                        $stockDetail = $stock->stockDetails()->create([
                            'productID' => $thisStock['productID'],
                            'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                            'quantity' => $thisStock['quantityToAdd'],
                            'quantityUnits' => $thisStock['quantityToAdd'],
                            'purchasePrice' => 0
                        ]);
    
                        $stockDetail->stockDetailStatuses()->create([
                            'statusID' => \Config::get('constants.stock_status.quetta_godown'),
                            'batchID' => \App\Services\BatchService::getCurrentBatch()->batchID,
                            'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                            'quantity' => $thisStock['quantityToAdd'],
                            'transferDate' => date('Y-m-d'),
                            'quantityUnits' => $thisStock['quantityToAdd'],
                            'createdByUserID' => Auth::id()
                        ]);
                    }
                }
    
                $stock = \App\Models\Stock::create([
                    'productionBOMID' => $productionBOM->productionBOMID,
                    'createdByUserID' => Auth::id()
                ]);
                $stockDetail = $stock->stockDetails()->create([
                    'productID' => $request->productID,
                    'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                    'quantity' => $request->quantity,
                    'quantityUnits' => $request->quantity,
                    'purchasePrice' => 0
                ]);
    
                $stockDetail->stockDetailStatuses()->create([
                    'statusID' => \Config::get('constants.stock_status.quetta_godown'),
                    'batchID' => \App\Services\BatchService::getCurrentBatch()->batchID,
                    'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                    'quantity' => $request->quantity,
                    'transferDate' => date('Y-m-d'),
                    'quantityUnits' => $request->quantity,
                    'createdByUserID' => Auth::id()
                ]);
    
                DB::commit();
                $request->session()->flash('message', 'Production created successfully!');
            }
        } catch (\Exception $e) {
            DB::rollback();
			$request->session()->flash('error', 'An error occurred while creating production!');
        }
        return redirect()->route('production.index');
    }

    public function save(Request $request)
    {
		DB::beginTransaction();
        if ($this->add($request)) {
            DB::commit();
            $request->session()->flash('message', 'Production created successfully!');
        } else {
            DB::rollback();
        }
        return redirect()->route('production.list');
    }

    private function add(Request $request) 
    {
        abort_if(Gate::denies('production_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $is_success = true;
        try {
            $request->merge(['createdByUserID' => Auth::id()]);
            $request->merge(['isCompleted' => 1]);
            $request->merge(['serial' => $request->mb.'-'.$request->bookSerial]);
            $request->merge(['productionStageID' => \Config::get('constants.production_stages.finished')]);
            // dd($request->all());
            $production = Production::create($request->all());
                        
            foreach ($request->productIDs as $prodIdx => $productValue) {
                $ary_product = explode('_',$productValue);
                $productID = $ary_product[0];
                $uuid = $ary_product[1];
                $request->productID = $productID;
                $request->productQty = $request['quantity'][$prodIdx];
                $productionBOM = ProductionBOM::create([
                    'productionID' => $production->productionID,
                    'productID' => $productID,
                    'quantity' => $request->productQty,
                    'productionStageID' => $request->productionStageID,
                    'createdByUserID' => Auth::id()
                ]);

                $productItemIDValue = 'productItemIDs_' . $uuid;
                $productItemIDQty = 'productItemQuantitys_' . $uuid;
                $productItemIDPrice = 'productItemPrices_' . $uuid;

                foreach ($request[$productItemIDValue] as $idx => $thisProductItemID) {
                    $productionBOMItem = ProductionBOMItem::create([
                        'productionBOMID' => $productionBOM->productionBOMID,
                        'productID' => $thisProductItemID,
                        'quantity' => $request[$productItemIDQty][$idx],
                        'unitPrice' => $request[$productItemIDPrice][$idx],
                        'createdByUserID' => Auth::id()
                    ]);
                }
    
                if ($request->filled('expenseHeadIDs')) {
                    foreach ($request->expenseHeadIDs as $idx => $thisExpenseHeadID) {
                        $productionBOMExpense = ProductionBOMExpense::create([
                            'productionBOMID' => $productionBOM->productionBOMID,
                            'expenseHeadID' => $thisExpenseHeadID,
                            'amount' => $request->expenseAmounts[$idx],
                            'createdByUserID' => Auth::id()
                        ]);
                    }
                }

                //Add stockDetailStatus with statusID =  $retStatus = $this->addStockDetailStatus($production->productionBOMID);
    
                // if ($productionBOM->productionStageID == \Config::get('constants.production_stages.finished')) {}
    
                $retStatus = $this->addStockDetailStatus($productionBOM->productionBOMID);
                $is_success = $retStatus['success'];
                $errorMsg = $retStatus['message'];

                if (!$is_success) {
                    $request->session()->flash('error', $errorMsg);
                    return $is_success;
                } else {
                    $stockToAdd = [];
                    foreach ($request[$productItemIDValue] as $idx => $thisProductItemID) {
                        $thisProduct = $productionBOM->items()->where('productID', $thisProductItemID)->first();
                        $thisProduct->update([
                            'consumed' => $request[$productItemIDQty][$idx]
                        ]);
        
                        // If Quantity is Less, then put back to Factory Stock
                        if ($request[$productItemIDQty][$idx] < $thisProduct->quantity) {
                            // Add this product to stock
                            $stockToAdd[] = ['productID' => $thisProductItemID, 'quantityToAdd' => $thisProduct->quantity - $request->productItemQuantityConsumed[$idx]];
                        }
                    }
        
                    if (count($stockToAdd) > 0) {
                        $stock = \App\Models\Stock::create([
                            'productionID' => $production->productionID,
                            'createdByUserID' => Auth::id()
                        ]);
        
                        foreach ($stockToAdd as $thisStock) {
                            $stockDetail = $stock->stockDetails()->create([
                                'productID' => $thisStock['productID'],
                                'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                                'quantity' => $thisStock['quantityToAdd'],
                                'quantityUnits' => $thisStock['quantityToAdd'],
                                'purchasePrice' => 0
                            ]);
        
                            $stockDetail->stockDetailStatuses()->create([
                                'statusID' => \Config::get('constants.stock_status.quetta_godown'),
                                'batchID' => \App\Services\BatchService::getCurrentBatch()->batchID,
                                'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                                'quantity' => $thisStock['quantityToAdd'],
                                'transferDate' => date('Y-m-d'),
                                'quantityUnits' => $thisStock['quantityToAdd'],
                                'createdByUserID' => Auth::id()
                            ]);
                        }
                    }
        
                    $stock = \App\Models\Stock::create([
                        'productionID' => $production->productionID,
                        'createdByUserID' => Auth::id()
                    ]);
                    $stockDetail = $stock->stockDetails()->create([
                        'productID' => $request->productID,
                        'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                        'quantity' => $request->productQty,
                        'quantityUnits' => $request->productQty,
                        'purchasePrice' => 0
                    ]);
        
                    $stockDetail->stockDetailStatuses()->create([
                        'statusID' => \Config::get('constants.stock_status.quetta_godown'),
                        'batchID' => \App\Services\BatchService::getCurrentBatch()->batchID,
                        'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                        'quantity' => $request->productQty,
                        'transferDate' => date('Y-m-d'),
                        'quantityUnits' => $request->productQty,
                        'createdByUserID' => Auth::id()
                    ]);
                }
            }
        } catch (\Exception $e) {
            $is_success = false;
			$request->session()->flash('error', 'An error occurred while creating production! ' . $e->getMessage());
        }
        return $is_success;
    }

    /**
      * Display the specified resource.
      *
      * @param  \App\Models\ProductionBOM  $productionBOM
      * @return \Illuminate\Http\Response
      */
    public function show(ProductionBOM $production)
    {
        abort_if(Gate::denies('production_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        dd($production);
    }

    public function view(Production $production)
    {
        abort_if(Gate::denies('production_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $production->load(['boms.product','boms.items','boms.expenses']);
        // dd($production);
        return view('admin.production.view', compact('production'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductionBOM  $productionBOM
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductionBOM $production)
    {
        abort_if(Gate::denies('production_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$BOMProducts = \App\Models\Product::with(['BOM.items','BOM.expenses.head'])->where('isBOM',1)->get()->sortBy('productName');
		$production->load(['items','expenses.head']);
        return view('admin.production.edit',compact('BOMProducts','production'));
    }

    public function change(Production $production)
    {
        abort_if(Gate::denies('production_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$BOMProducts = \App\Models\Product::with(['BOM.items','BOM.expenses.head'])->where('isBOM',1)->get()->sortBy('productName');
		$production->load(['boms.product','boms.items','boms.expenses']);
        return view('admin.production.change',compact('BOMProducts','production'));
    }

    public function changeUpdate(Production $production, Request $request) 
    {
        DB::beginTransaction();
        if ($this->delete($production) && $this->add($request)) {
            DB::commit();
            $request->session()->flash('message', 'Production created successfully!');
        } else {
            DB::rollback();
        }
        return redirect()->route('production.list');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductionBOM  $productionBOM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductionBOM $production)
    {
        abort_if(Gate::denies('production_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
            $is_success = false;
            if ($request->has('stage') && $request->get('stage') == 'finished') {
                $production->update([
                    'productionStageID' => \Config::get('constants.production_stages.finished')
                ]);

                $stockToAdd = [];
                foreach ($request->productItemID as $idx => $thisProductItemID) {
                    $thisProduct = $production->items()->where('productID', $thisProductItemID)->first();
                    $thisProduct->update([
                        'consumed' => $request->productItemQuantityConsumed[$idx]
                    ]);

                    // If Quantity is Less, then put back to Factory Stock
                    if ($request->productItemQuantityConsumed[$idx] < $thisProduct->quantity) {
                        // Add this product to stock
                        $stockToAdd[] = ['productID' => $thisProductItemID, 'quantityToAdd' => $thisProduct->quantity - $request->productItemQuantityConsumed[$idx]];
                    }
                }

                if (count($stockToAdd) > 0) {
                    $stock = \App\Models\Stock::create([
                        'productionID' => $production->productionID,
                        'createdByUserID' => Auth::id()
                    ]);

                    foreach ($stockToAdd as $thisStock) {
                        $stockDetail = $stock->stockDetails()->create([
                            'productID' => $thisStock['productID'],
                            'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                            'quantity' => $thisStock['quantityToAdd'],
                            'quantityUnits' => $thisStock['quantityToAdd'],
                            'purchasePrice' => 0
                        ]);

                        $stockDetail->stockDetailStatuses()->create([
                            'statusID' => \Config::get('constants.stock_status.quetta_godown'),
                            'batchID' => \App\Services\BatchService::getCurrentBatch()->batchID,
                            'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                            'quantity' => $thisStock['quantityToAdd'],
                            'transferDate' => date('Y-m-d'),
                            'quantityUnits' => $thisStock['quantityToAdd'],
                            'createdByUserID' => Auth::id()
                        ]);
                    }
                }

                $stock = \App\Models\Stock::create([
                    'productionID' => $production->productionID,
                    'createdByUserID' => Auth::id()
                ]);
                $stockDetail = $stock->stockDetails()->create([
                    'productID' => $request->productID,
                    'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                    'quantity' => $request->quantity,
                    'quantityUnits' => $request->quantity,
                    'purchasePrice' => 0
                ]);

                $stockDetail->stockDetailStatuses()->create([
                    'statusID' => \Config::get('constants.stock_status.quetta_godown'),
                    'batchID' => \App\Services\BatchService::getCurrentBatch()->batchID,
                    'godownID' => \Config::get('constants.production_stages.default_factory_id'),
                    'quantity' => $request->quantity,
                    'transferDate' => date('Y-m-d'),
                    'quantityUnits' => $request->quantity,
                    'createdByUserID' => Auth::id()
                ]);
                $is_success = true;
            } else {
                $production->update($request->all());
    			$production->expenses()->delete();
    			$production->items()->delete();

                foreach ($request->productItemID as $idx => $thisProductItemID) {
                    $productionBOMItem = ProductionBOMItem::create([
                        'productionBOMID' => $production->productionBOMID,
                        'productID' => $thisProductItemID,
                        'quantity' => $request->productItemQuantity[$idx],
                        'unitPrice' => $request->productItemPrice[$idx],
                        'createdByUserID' => Auth::id()
                    ]);
                }

				if ($request->filled('expenseHeadID')) {
					foreach ($request->expenseHeadID as $idx => $thisExpenseHeadID) {
						$productionBOMExpense = ProductionBOMExpense::create([
							'productionBOMID' => $production->productionBOMID,
							'expenseHeadID' => $thisExpenseHeadID,
							'amount' => $request->expenseAmount[$idx],
							'createdByUserID' => Auth::id()
						]);
					}
				}

                $is_success = true;
                if ($production->productionStageID == 1) {
                    \App\Models\StockDetailStatus::where('productionBOMID', $production->productionBOMID)->delete();
                    $retStatus = $this->addStockDetailStatus($production->productionBOMID);
                    $is_success = $retStatus['success'];
                    $errorMsg = $retStatus['message'];
                }
            }

            if ($is_success) {
                DB::commit();
    			$request->session()->flash('message', 'Production updated successfully!');
            } else {
                DB::rollback();
                $request->session()->flash('error', 'An error occurred while updating production!');
            }
		} catch (Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating production!');
		}

        return redirect()->route('production.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductionBOM  $productionBOM
     * @return \Illuminate\Http\Response
     */
    public function destroy(Production $production,Request $request)
    {
		DB::beginTransaction();
        if ($this->delete($production)) {
            DB::commit();
			$request->session()->flash('message', 'Production deleted successfully!');
        } else {
            DB::rollback();
            $request->session()->flash('error', 'An error occurred while deleting production!');
        }

        return redirect()->route('production.list');
    }

    private function delete(Production $production) 
    {
        abort_if(Gate::denies('production_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $is_success = true;
        try {
            $stocks = \App\Models\Stock::where('productionID', $production->productionID)->get();
            foreach ($stocks as $stock) {
                foreach ($stock->stockDetails as $stockDetail) {
                    foreach ($stockDetail->stockDetailStatuses as $stockDetailStatus) {
                        $stockDetailStatus->delete();
                    }
                    $stockDetail->delete();
                }
                $stock->delete();
            }

            \App\Models\StockDetailStatus::where('productionID', $production->productionID)->delete();

            foreach ($production->boms as $bom) {
                foreach ($bom->items as $item) {
                    $item->delete();
                }
                foreach ($bom->expenses as $expense) {
                    $expense->delete();
                }
                $bom->delete();
            }

            $production->delete();
			
		} catch (Exception $e) {
			$is_success = false;	
		}
        return $is_success;
    }



    public function nextStage(ProductionBOM $production,Request $request)
    {
        $errorMsg = "An error occurred while changing production stage!";
        abort_if(Gate::denies('production_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
            if ($production->productionStageID == \Config::get('constants.production_stages.draft')) {
                $retStatus = $this->addStockDetailStatus($production->productionBOMID);
                $is_success = $retStatus['success'];
                $errorMsg = $retStatus['message'];
            } elseif ($production->productionStageID == \Config::get('constants.production_stages.in_process')) {
                $production->productionStageID = \Config::get('constants.production_stages.finished');
                return $this->edit($production);
            } else {
                dd('Please Contact Admin!');
            }

            if ($is_success == true) {
                DB::commit();
    			$request->session()->flash('message', 'Production stage changed successfully!');
            }
		} catch (Exception $e) {
            $is_success = false;
			DB::rollback();
		}

        if (!$is_success) {
            $request->session()->flash('error', $errorMsg);
        }

        return redirect()->route('production.index');
    }

    public function addStockDetailStatus(int $productionBOMID) {
        $is_success = true;
        $errorMsg = '';
        $productionBOM = ProductionBOM::with('items')->where('productionBOMID',$productionBOMID)->first();
        foreach ($productionBOM->items as $productionBOMItem) {
            // Check for stock for this product
			$stockInfo = \App\Models\Stock::getStock($productID = $productionBOMItem->productID,false,true,\Config::get('constants.production_stages.default_factory_id'));
            if ($stockInfo == null) {
                $is_success = false;
                $errorMsg = 'Please make sure "' . $productionBOMItem->product->productName . '" have been shifted to Factory!';
                break;
            }
            $productStockQty = Arr::first($stockInfo)->inStockQuantity;
            // dd($productStockQty,$production,$productionBOMItem);
            $quantityRemaining = $productionBOMItem->quantity * $productionBOM->quantity;
            $quantityUnitsRemaining = $quantityRemaining;
            if ($productStockQty >= $quantityRemaining) {
                // Change stock status to manufacturing
                // Moving Stock to Manufacturing Status
                $stockDetails = \App\Models\Stock::getProductStockDetails($productID = $productionBOMItem->productID,\Config::get('constants.production_stages.default_factory_id'));
				foreach ($stockDetails as $stockDetailInfo) {
                    // dd($stockDetailInfo);
                    if ($quantityRemaining == 0) {
                        break;
                    } else {
                        $stockDetailStatus = new \App\Models\StockDetailStatus();
                        $stockDetailStatus->stockDetailID = $stockDetailInfo->stockDetailID;
                        $stockDetailStatus->statusID = \Config::get('constants.stock_status.manufacturing');
                        $stockDetailStatus->batchID = \App\Services\BatchService::getCurrentBatch()->batchID;
                        $stockDetailStatus->godownID = \Config::get('constants.production_stages.default_factory_id');
                        $stockDetailStatus->salePrice = null;
                        $stockDetailStatus->transferDate = date('Y-m-d');
                        $stockDetailStatus->createdByUserID = Auth::id();
                    }
                    if ($stockDetailInfo->quantityAvailable >= $quantityRemaining) {
                        $stockDetailStatus->quantity = $quantityRemaining;
                        $stockDetailStatus->quantityUnits = $quantityUnitsRemaining;
                        $stockDetailStatus->productionID = $productionBOM->productionID;
                        $stockDetailStatus->save();
                        break;
                    } else {
                        if ($stockDetailInfo->quantityAvailable >= $quantityRemaining) {
                            dd('Please contact Admin...');
                            $stockDetailStatus->quantity = $quantityRemaining;
                            $quantityRemaining = 0;
                            $quantityUnitsRemaining = 0;
                        } elseif ($stockDetailInfo->quantityAvailable == 0) {
                            $stockDetailStatus->quantity = 0;
                        } else {
                            $quantityRemaining -= $stockDetailInfo->quantityAvailable;
							$quantityUnitsRemaining-=$stockDetailInfo->quantityAvailable;
                            $stockDetailStatus->quantity = $stockDetailInfo->quantityAvailable;
                        }

                        $stockDetailStatus->quantityUnits = $stockDetailInfo->quantityAvailable;
                        $stockDetailStatus->godownID = $stockDetailInfo->godownID;
                        $stockDetailStatus->productionID = $productionBOM->productionID;
                        $stockDetailStatus->save();
                        // Insert stockDetailStatusID in salesOrderDetail
                    }
                }
                // End

                // $production->update([
                //     'productionStageID' => \Config::get('constants.production_stages.in_process')
                // ]);
            } else {
                $is_success = false;
                $errorMsg = 'Required quantity for ' . $productionBOMItem->product->productName . ' is ' . $productionBOMItem->quantity * $productionBOM->quantity  . ' and stock has ' . $productStockQty;
                break;
            }
        }
        return ['success' => $is_success, 'message' => $errorMsg];
    }
}
