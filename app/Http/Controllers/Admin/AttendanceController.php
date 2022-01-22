<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('attendance_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $today = \Carbon\Carbon::now();
        $staffMonthlyAttendance = Attendance::whereBetween('attendanceDate',[$today->firstOfMonth()->format('Y-m-d'),$today->lastOfMonth()->format('Y-m-d')])->get();
        $monthDates = Attendance::getMonthDates();
        return view('admin.attendance.index',compact('staffMonthlyAttendance','monthDates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('attendance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $staff = Staff::all()->sortBy('staffName');
		return view('admin.attendance.create',compact('staff'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendanceRequest $request)
    {
		// DB::beginTransaction();
		// try {
		// 	$request->request->add(['createdByUserID' => Auth::id()]);
		// 	$request->request->add(['headID' => \App\Models\AccountHead::addAccountHead($request->staffName . ' (Staff)',Auth::id(),\Config::get('constants.account_heads.staff'))]);
	    //     $staff = Staff::create($request->all());
		// 	DB::commit();
		// 	$request->session()->flash('message', 'Staff added successfully!');
		// } catch (\Exception $e) {
		// 	DB::rollback();
		// 	$request->session()->flash('error', 'An error occurred while creating staff!');
		// }
		return redirect()->route('attendance.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
		abort_if(Gate::denies('attendance_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $staffTransactions = \App\Services\TransactionService::getSubHeadTransactions(Staff::find($staff->staffID)->headID);
        // return view('admin.attendance.show', compact('staff','staffTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
		abort_if(Gate::denies('attendance_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $staffTypes = \App\Models\StaffType::all()->sortBy('staffType');
        // return view('admin.attendance.edit', compact('staff','staffTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendanceRequest $request, Staff $staff)
    {
		// DB::beginTransaction();
		// try {
		// 	\App\Models\AccountHead::updateAccountHead($staff->headID,$request->staffName . ' (Staff)');
		// 	$staff->update($request->all());
		// 	DB::commit();
		// 	$request->session()->flash('message', 'Staff updated successfully!');
		// } catch (\Exception $e) {
		// 	DB::rollback();
		// 	$request->session()->flash('error', 'An error occurred while updating staff!');
		// }
		return redirect()->route('attendance.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff,Request $request)
    {
		// abort_if(Gate::denies('attendance_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		// DB::beginTransaction();
		// try {
		// 	$staff->delete();
		// 	$staff->head->delete();
		// 	DB::commit();
		// 	$request->session()->flash('message', 'Staff deleted successfully!');
		// } catch (\Exception $e) {
		// 	DB::rollback();
		// 	dd($e);
		// 	$request->session()->flash('error', 'An error occurred while deleting staff!');
		// }

        return redirect()->route('attendance.index');
    }

    // public function getBalance(int $staffID) {
	// 	$staffBalance = Staff::getBalance($staffID);
	// 	return $staffBalance;
	// }
}
