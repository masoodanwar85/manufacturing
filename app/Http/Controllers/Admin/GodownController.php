<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Godown;
use Gate;
use App\Http\Requests\StoreGodownRequest;
use App\Http\Requests\UpdateGodownRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GodownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('godown_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Godown::all()->sortBy('name');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'godown_read';
                $editGate      = 'godown_update';
                $deleteGate    = 'godown_delete';
                $crudRoutePart = 'godown';
                $primaryKey = 'godownID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
			$table->editColumn('balance', function ($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted(Godown::getBalance($row->godownID)[0]->totalPayable);
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

        return view('admin.godown.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('godown_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.godown.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGodownRequest $request)
    {
		DB::beginTransaction();
		try {
			$request->request->add(['createdByUserID' => Auth::id()]);
			//$request->request->add(['headID' => \App\Models\AccountHead::addAccountHead($request->name . ' (Godown)',Auth::id(),\Config::get('constants.account_heads.godown'),1,0,1,0,0,0)]);
			$request->request->add(['headID' => \App\Models\AccountHead::addAccountHead($request->name . ' (Godown)',Auth::id(),\Config::get('constants.account_heads.godown_rent'),1,0,1,0,0,0)]);
	        $godown = Godown::create($request->all());
			DB::commit();
			$request->session()->flash('message', 'Godown created successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while creating godown!');
		}

        return redirect()->route('godown.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Godown  $godown
     * @return \Illuminate\Http\Response
     */
    public function show(Godown $godown)
    {
		abort_if(Gate::denies('godown_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$godownStocks = \App\Models\Stock::getGodownStock($godown->godownID);
        $totalPayable = Godown::getBalance($godown->godownID)[0]->totalPayable;
		$godownTransactions = \App\Services\TransactionService::getSubHeadTransactions(Godown::find($godown->godownID)->headID);
		$purchaseOrders = \App\Models\PurchaseOrder::where('isLocked',0)->where('lastGodownID',$godown->godownID)->get();
        return view('admin.godown.show', compact('godown','totalPayable','godownTransactions','godownStocks','purchaseOrders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Godown  $godown
     * @return \Illuminate\Http\Response
     */
    public function edit(Godown $godown)
    {
		abort_if(Gate::denies('godown_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.godown.edit', compact('godown'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Godown  $godown
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGodownRequest $request, Godown $godown)
    {
		DB::beginTransaction();
		try {
			\App\Models\AccountHead::updateAccountHead($godown->headID,$request->name . ' (Godown)');
			$godown->update($request->all());
			DB::commit();
			$request->session()->flash('message', 'Godown updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating godown!');
		}

        return redirect()->route('godown.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Godown  $godown
     * @return \Illuminate\Http\Response
     */
    public function destroy(Godown $godown, Request $request)
    {
		abort_if(Gate::denies('godown_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		DB::beginTransaction();
		try {
			$godown->delete();
			$godown->head->delete();
			DB::commit();
			$request->session()->flash('message', 'Godown deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while deleting godown!');
		}
        return redirect()->route('godown.index');
    }

	public function getBalance(int $godownID) {
		$godownBalance = Godown::getBalance($godownID);
		return $godownBalance;
	}

	public function getBalanceByHeadID(int $godownHeadID) {
		$godownBalance = Godown::getBalance($godownHeadID,TRUE);
		return $godownBalance;
	}
}
