<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customer = Customer::query();
            $salesAgentID = $this->salesAgentID = Auth::user()->staff ? Auth::user()->staff->staffID : null;
            if ($salesAgentID != null) {
                $customer->where('salesAgentID', $salesAgentID);
            }

            $query = $customer->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'customer_read';
                $editGate = 'customer_update';
                $deleteGate = 'customer_delete';
                $crudRoutePart = 'customer';
                $primaryKey = 'customerID';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row',
                        'primaryKey'
                    )
                );
            });

            $table->editColumn('salesAgent', function ($row) {
                return $row->salesAgent ? $row->salesAgent->staffName : '';
            });

            $table->editColumn('balance', function ($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted(Customer::getBalance($row->customerID)[0]->totalPayable);
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $saleAgents = \App\Models\Staff::salesAgents()->get();
        return view('admin.customer.create', compact('saleAgents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->merge(['createdByUserID' => Auth::id()]);
            $request->merge(['headID' => \App\Models\AccountHead::addAccountHead($request->customerName . ' (' . $request->shopName . ')' . ' (Customer)', Auth::id(), \Config::get('constants.account_heads.customer'), 1, 0, 0, 0, 0, 0)]);
            $customer = Customer::create($request->all());
            DB::commit();
            $request->session()->flash('message', 'Customer created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'An error occurred while creating customer!');
        }
        return redirect()->route('customer.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        abort_if(Gate::denies('customer_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customer->load('salesAgent');
        $salesAgentID = Auth::user()->staff ? Auth::user()->staff->staffID : null;
        if ($salesAgentID != null && ($customer->salesAgent == null || $customer->salesAgent->staffID != $salesAgentID)) {
            return response()->json(['message' => '403 Forbidden'], 403);
        }
        $totalPayable = Customer::getBalance($customer->customerID)[0]->totalPayable;
        $customerTransactions = \App\Services\TransactionService::getSubHeadTransactions(Customer::find($customer->customerID)->headID, 'salesOrders');
        return view('admin.customer.show', compact('customer', 'totalPayable', 'customerTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        abort_if(Gate::denies('customer_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $saleAgents = \App\Models\Staff::salesAgents()->get();
        return view('admin.customer.edit', compact('customer', 'saleAgents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            \App\Models\AccountHead::updateAccountHead($customer->headID, $request->customerName . ' (' . $request->shopName . ')' . ' (Customer)');
            $customer->update($request->all());
            DB::commit();
            $request->session()->flash('message', 'Customer updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'An error occurred while updating customer!');
        }
        return redirect()->route('customer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer, Request $request)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();
        try {
            $customer->delete();
            $customer->head->delete();
            DB::commit();
            $request->session()->flash('message', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'An error occurred while deleting customer!');
        }

        return redirect()->route('customer.index');
    }

    public function getBalance(int $customerID)
    {
        $customerBalance = Customer::getBalance($customerID);
        return $customerBalance;
    }
}