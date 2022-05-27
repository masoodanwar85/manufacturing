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
                return view('partials.datatablesActions', compact(
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
		$BOMProducts = \App\Models\Product::with(['BOM.items','BOM.expenses.head'])->where('isBOM',1)->get()->sortBy('productName');
		$production->load(['items','expenses']);
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
        //
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
}
