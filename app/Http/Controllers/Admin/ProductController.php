<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('product_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = DB::table('product')
                        ->join('category','product.categoryID','=','category.categoryID')
						->join('measurementUnit','product.maximumUnitID','=','measurementUnit.unitID')
                        ->select('product.*','measurementUnit.symbol as maximumUnitSymbol','category.categoryName')
                        ->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'product_read';
                $editGate      = 'product_update';
                $deleteGate    = 'product_delete';
                $crudRoutePart = 'product';
                $primaryKey = 'productID';
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
                return $row->productName ? $row->productName : "";
            });
            $table->editColumn('unitSalePrice', function ($row) {
                return $row->unitSalePrice ? $row->unitSalePrice : "0";
            });
			$table->editColumn('unitPurchasePrice', function ($row) {
                return $row->unitPurchasePrice ? $row->unitPurchasePrice : "0";
            });
            $table->editColumn('categoryName', function ($row) {
                return $row->categoryName ? $row->categoryName : "";
            });
			$table->editColumn('thresholdUnit', function ($row) {
                return $row->thresholdUnit ? $row->thresholdUnit : "0";
            });
            $table->editColumn('maximumUnitSymbol', function ($row) {
                return $row->maximumUnitSymbol ? $row->maximumUnitSymbol : "";
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $categories = \App\Models\Category::all()->sortBy('categoryName');
		$measurementUnits = \App\Models\MeasurementUnit::all()->sortBy('unitID');

		$products = \App\Models\Product::with('maximumUnit')->get()->sortBy('productName');
        $BOMExpense = \App\Models\AccountHead::with('childrenAccountHeads')->whereRaw('parentHeadID = ' . \Config::get('constants.account_heads.expense') . ' AND isShowForBOMExpense = 1')->get();

		return view('admin.product.create',compact('categories','measurementUnits','products','BOMExpense'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->request->add(['createdByUserID' => Auth::id()]);
            $request->request->add(['minimumUnitID' => $request->maximumUnitID]);
//            $request->request->add(['unitSalePrice' => 0]);
            $product = Product::create($request->all());

            if ($request->get('isBOM') == 1) {
                // Add BOM Product Items
                $productBOM = \App\Models\ProductBOM::create(['productID' => $product->productID, 'createdByUserID' => Auth::id()]);
                foreach ($request->productID as $key => $value) {
                    \App\Models\ProductBOMItem::create([
                        'productBOMID' => $productBOM->productBOMID,
                        'productID' => $value,
                        'quantity' => $request->quantity[$key],
                        'isConsumeable' => 1,
                        'createdByUserID' => Auth::id()
                    ]);
                }

                if ($request->get('headID') != NULL) {
                    foreach ($request->headID as $key => $value) {
                        \App\Models\ProductBOMExpense::create([
                            'productBOMID' => $productBOM->productBOMID,
                            'expenseHeadID' => $value,
                            'amount' => $request->amount[$key],
                            'createdByUserID' => Auth::id()
                        ]);
                    }
                }
            }
            DB::commit();
            $request->session()->flash('message', 'Product added successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            $request->session()->flash('error', 'An Error Occurred while adding Product!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
		abort_if(Gate::denies('product_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$productHistory = Product::getHistory($product->productID);
        return view('admin.product.show', compact('product','productHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
		abort_if(Gate::denies('product_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $categories = \App\Models\Category::all()->sortBy('categoryName');
		$measurementUnits = \App\Models\MeasurementUnit::all()->sortBy('unitID');
		$products = \App\Models\Product::with('maximumUnit')->get()->sortBy('productName');
        if ($product->isBOM == 1) {
            $product->load(['BOM.items','BOM.expenses']);
        }
        $BOMExpense = \App\Models\AccountHead::with('childrenAccountHeads')->whereRaw('parentHeadID = ' . \Config::get('constants.account_heads.expense') . ' AND isShowForBOMExpense = 1')->get();
        return view('admin.product.edit', compact('product','categories','measurementUnits','products','BOMExpense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        try {
			$product->load('BOM');
            $product->update($request->all());

			if ($product->BOM != NULL) {
				$product->BOM->items()->delete();
				$product->BOM->expenses()->delete();
				$product->BOM->delete();
			}


            if ($request->get('isBOM') == 1) {
                // Add BOM Product Items
                $productBOM = \App\Models\ProductBOM::create(['productID' => $product->productID, 'createdByUserID' => Auth::id()]);
                foreach ($request->productID as $key => $value) {
                    \App\Models\ProductBOMItem::create([
                        'productBOMID' => $productBOM->productBOMID,
                        'productID' => $value,
                        'quantity' => $request->quantity[$key],
                        'isConsumeable' => 1,
                        'createdByUserID' => Auth::id()
                    ]);
                }

                if ($request->get('headID') != NULL) {
                    foreach ($request->headID as $key => $value) {
                        \App\Models\ProductBOMExpense::create([
                            'productBOMID' => $productBOM->productBOMID,
                            'expenseHeadID' => $value,
                            'amount' => $request->amount[$key],
                            'createdByUserID' => Auth::id()
                        ]);
                    }
                }
            }

            DB::commit();
            $request->session()->flash('message', 'Product updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            $request->session()->flash('error', 'An Error Occurred while updating Product!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product,Request $request)
    {
		abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();

        try {
            if ($product->BOMs) {
                $product->BOMs->items()->delete();
                $product->BOMs->expenses()->delete();
                $product->BOMs->delete();
            }
            $product->delete();
            DB::commit();
            $request->session()->flash('message', 'Product deleted successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', 'An error occurred while deleting product!');
        }
        return redirect()->route('product.index');
    }
}
