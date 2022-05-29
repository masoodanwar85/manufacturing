<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('production_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $BOMProducts = \App\Models\Product::with(['BOM.items','BOM.expenses.head'])->where('isBOM',1)->get()->sortBy('productName');
        return view('admin.production.create',compact('BOMProducts'));
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
		$request->merge(['createdByUserID' => Auth::id()]);

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

        foreach ($request->expenseHeadID as $idx => $thisExpenseHeadID) {
            $productionBOMExpense = ProductionBOMExpense::create([
                'productionBOMID' => $productionBOM->productionBOMID,
                'expenseHeadID' => $thisExpenseHeadID,
                'amount' => $request->expenseAmount[$idx],
                'createdByUserID' => Auth::id()
            ]);
        }

        $request->session()->flash('message', 'Production created successfully!');
        return redirect()->route('production.index');
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

            foreach ($request->expenseHeadID as $idx => $thisExpenseHeadID) {
                $productionBOMExpense = ProductionBOMExpense::create([
                    'productionBOMID' => $production->productionBOMID,
                    'expenseHeadID' => $thisExpenseHeadID,
                    'amount' => $request->expenseAmount[$idx],
                    'createdByUserID' => Auth::id()
                ]);
            }

			DB::commit();
			$request->session()->flash('message', 'Production updated successfully!');
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
    public function destroy(ProductionBOM $production,Request $request)
    {
		abort_if(Gate::denies('production_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
			$production->expenses()->delete();
			$production->items()->delete();
			$production->delete();
			DB::commit();
			$request->session()->flash('message', 'Production deleted successfully!');
		} catch (Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while deleting production!');
		}

        return redirect()->route('production.index');
    }

    public function nextStage(ProductionBOM $production,Request $request)
    {
        $errorMsg = "An error occurred while changing production stage!";
        $is_success = true;
		abort_if(Gate::denies('production_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
            $production->load('items');
            if ($production->productionStageID == 0) {
                foreach ($production->items as $productionBOMItem) {
                    // Check for stock for this product
                    $productStockQty = \App\Models\Stock::getStock($productID = $productionBOMItem->productID,false,true,2);
                    dd($productStockQty);
                    if ($productStockQty >= $productionBOMItem->quantity) {
                        // Change stock status to manufacturing

                        
                    } else {
                        DB::rollback();
                        $is_success = false;
                        $errorMsg = 'Required quantity for ' . $productionBOMItem->product->productName . ' is less in stock!';
                        break;
                    }
                }
            } elseif ($production->productionStageID == 1) {

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
}
