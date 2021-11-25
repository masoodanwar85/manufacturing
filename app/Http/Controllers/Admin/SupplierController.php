<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Gate;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('supplier_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Supplier::all()->sortBy('supplierName');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'supplier_read';
                $editGate      = 'supplier_update';
                $deleteGate    = 'supplier_delete';
                $crudRoutePart = 'supplier';
                $primaryKey = 'supplierID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('supplierName', function ($row) {
                return $row->supplierName ? $row->supplierName : "";
            });
			$table->editColumn('balance', function ($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted(Supplier::getBalance($row->supplierID)[0]->totalPayable);
            });
			$table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : "";
            });
			$table->editColumn('address', function ($row) {
                return $row->address ? $row->address : "";
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.supplier.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('supplier_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupplierRequest $request)
    {
		DB::beginTransaction();
		try {
			$request->request->add(['createdByUserID' => Auth::id()]);
			$request->request->add(['headID' => \App\Models\AccountHead::addAccountHead($request->supplierName . ' (Supplier)',Auth::id(),\Config::get('constants.account_heads.supplier'),1,0,0,0,0,0)]);
			$supplier = Supplier::create($request->all());
			DB::commit();
			$request->session()->flash('message', 'Supplier created successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while creating supplier!');
		}
        return redirect()->route('supplier.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
		abort_if(Gate::denies('supplier_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$totalPayable = Supplier::getBalance($supplier->supplierID)[0]->totalPayable;
		$supplierTransactions = \App\Services\TransactionService::getSubHeadTransactions(Supplier::find($supplier->supplierID)->headID);
        return view('admin.supplier.show', compact('supplier','totalPayable','supplierTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
		abort_if(Gate::denies('supplier_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
		DB::beginTransaction();
		try {
			\App\Models\AccountHead::updateAccountHead($supplier->headID,$request->supplierName . ' (Supplier)');
			$supplier->update($request->all());
			DB::commit();
			$request->session()->flash('message', 'Supplier updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating supplier!');
		}

        return redirect()->route('supplier.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier, Request $request)
    {
		abort_if(Gate::denies('supplier_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
			$supplier->delete();
			$supplier->head->delete();
			DB::commit();
			$request->session()->flash('message', 'Supplier deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while deleting supplier!');
		}

        return redirect()->route('supplier.index');
    }

	public function getBalance(int $supplierID) {
		$supplierBalance = Supplier::getBalance($supplierID);
		return $supplierBalance;
	}
}
