<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('staff_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = DB::table('staff')
                        ->join('staffType','staff.staffTypeID','=','staffType.staffTypeID')
						->select('staff.*','staffType.staffType')
                        ->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'staff_read';
                $editGate      = 'staff_update';
                $deleteGate    = 'staff_delete';
                $crudRoutePart = 'staff';
                $primaryKey = 'staffID';
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('balance', function($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted(Staff::getBalance($row->staffID)[0]->totalPayable);
            });

            $table->editColumn('staffName', function ($row) {
                return $row->staffName ? $row->staffName : "";
            });
            $table->editColumn('staffType', function ($row) {
                return $row->staffType ? $row->staffType : "";
            });
			$table->editColumn('dateJoined', function ($row) {
                return $row->dateJoined ? $row->dateJoined : "";
            });
			$table->editColumn('salary', function ($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted($row->paymentAmount) . " (" . ($row->paymentFrequencyID == 1 ? 'Monthly' : 'Daily') . ")";
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.staff.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('staff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $staffTypes = \App\Models\StaffType::all()->sortBy('staffType');
		return view('admin.staff.create',compact('staffTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffRequest $request)
    {
		DB::beginTransaction();
		try {
			$request->request->add(['createdByUserID' => Auth::id()]);
			$request->request->add(['headID' => \App\Models\AccountHead::addAccountHead($request->staffName . ' (Staff)',Auth::id(),\Config::get('constants.account_heads.staff'))]);
	        $staff = Staff::create($request->all());
			DB::commit();
			$request->session()->flash('message', 'Staff added successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while creating staff!');
		}
		return redirect()->route('staff.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
		abort_if(Gate::denies('staff_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $staffTransactions = \App\Services\TransactionService::getSubHeadTransactions(Staff::find($staff->staffID)->headID);
        return view('admin.staff.show', compact('staff','staffTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
		abort_if(Gate::denies('staff_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $staffTypes = \App\Models\StaffType::all()->sortBy('staffType');
        return view('admin.staff.edit', compact('staff','staffTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
		DB::beginTransaction();
		try {
			\App\Models\AccountHead::updateAccountHead($staff->headID,$request->staffName . ' (Staff)');
			$staff->update($request->all());
			DB::commit();
			$request->session()->flash('message', 'Staff updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating staff!');
		}
		return redirect()->route('staff.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff,Request $request)
    {
		abort_if(Gate::denies('staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
			$staff->delete();
			$staff->head->delete();
			DB::commit();
			$request->session()->flash('message', 'Staff deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while deleting staff!');
		}

        return redirect()->route('staff.index');
    }

    public function getBalance(int $staffID) {
		$staffBalance = Staff::getBalance($staffID);
		return $staffBalance;
	}
}
