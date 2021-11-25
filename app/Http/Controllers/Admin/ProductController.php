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
			$table->editColumn('unitsInProduct', function ($row) {
                return $row->unitsInProduct ? $row->unitsInProduct : "0";
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
		return view('admin.product.create',compact('categories','measurementUnits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $request->request->add(['createdByUserID' => Auth::id()]);
        $request->request->add(['minimumUnitID' => $request->maximumUnitID]);
        $request->request->add(['unitPurchasePrice' => 0]);
        $request->request->add(['unitSalePrice' => 0]);
        $product = Product::create($request->all());
        $request->session()->flash('message', 'Product added successfully!');
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
        return view('admin.product.edit', compact('product','categories','measurementUnits'));
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
        $product->update($request->all());
        $request->session()->flash('message', 'Product updated successfully!');
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
        if ($product->delete()) {
			$request->session()->flash('message', 'Product deleted successfully!');
		} else {
			$request->session()->flash('error', 'An error occurred while deleting product!');
		}
        return redirect()->route('product.index');
    }
}
