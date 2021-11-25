<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Gate;
use App\Http\Requests\StoreTransportRequest;
use App\Http\Requests\UpdateTransportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('transport_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Transport::all()->sortBy('name');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'transport_read';
                $editGate      = 'transport_update';
                $deleteGate    = 'transport_delete';
                $crudRoutePart = 'transport';
                $primaryKey = 'transportID';

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
                return \App\Services\CurrencyService::getCurrencyFormatted(Transport::getBalance($row->transportID)[0]->totalPayable);
            });
			$table->editColumn('owner', function ($row) {
                return $row->owner ? $row->owner : "";
            });
			$table->editColumn('vehicleNumber', function ($row) {
                return $row->vehicleNumber ? $row->vehicleNumber : "";
            });
			$table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.transport.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('transport_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.transport.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransportRequest $request)
    {
		DB::beginTransaction();
		try {
			$request->request->add(['createdByUserID' => Auth::id()]);
			// $request->request->add(['headID' => \App\Models\AccountHead::addAccountHead($request->name . '-' . $request->vehicleNumber . ' (' . $request->owner . ')' . ' (Transport)',Auth::id(),\Config::get('constants.account_heads.transport'),1,0,1,0,0,0)]);
			$request->request->add(['headID' => \App\Models\AccountHead::addAccountHead($request->name . '-' . $request->vehicleNumber . ' (' . $request->owner . ')' . ' (Transport)',Auth::id(),\Config::get('constants.account_heads.transport_expense'),1,0,1,0,0,0)]);
			$transport = Transport::create($request->all());
			DB::commit();
			$request->session()->flash('message', 'Transport created successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while creating transport!');
		}

        return redirect()->route('transport.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function show(Transport $transport)
    {
		abort_if(Gate::denies('transport_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$totalPayable = Transport::getBalance($transport->transportID)[0]->totalPayable;
		$transportTransactions = \App\Services\TransactionService::getSubHeadTransactions(Transport::find($transport->transportID)->headID);
        return view('admin.transport.show', compact('transport','totalPayable','transportTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function edit(Transport $transport)
    {
		abort_if(Gate::denies('transport_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.transport.edit', compact('transport'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransportRequest $request, Transport $transport)
    {
		DB::beginTransaction();
		try {
			\App\Models\AccountHead::updateAccountHead($transport->headID,$request->name . '-' . $request->vehicleNumber . ' (' . $request->owner . ')' . ' (Transport)');
			$transport->update($request->all());
			DB::commit();
			$request->session()->flash('message', 'Transport updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating transport!');
		}

        return redirect()->route('transport.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transport $transport, Request $request)
    {
		abort_if(Gate::denies('transport_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		DB::beginTransaction();
		try {
			$transport->delete();
			$transport->head->delete();
			DB::commit();
			$request->session()->flash('message', 'Transport deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while deleting transport!');
		}
        return redirect()->route('transport.index');
    }

	public function getBalance(int $transportID) {
		$transportBalance = Transport::getBalance($transportID);
		return $transportBalance;
	}

	public function getBalanceByHeadID(int $transportHeadID) {
		$transportBalance = Transport::getBalance($transportHeadID,TRUE);
		return $transportBalance;
	}
}
