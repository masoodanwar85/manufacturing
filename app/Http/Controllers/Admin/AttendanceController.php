<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $monthAttendance = date('Y') . '-' . date('m');

        if (!empty($request->get('monthAttendance')) && strlen($request->get('monthAttendance'))) {
            $monthAttendance = $request->get('monthAttendance');
        }

        $aryMonthAttendance = explode("-",$monthAttendance);

        $staffMonthlyAttendance = Staff::where('paymentAmount','>','0')->with(['attendance' => function($query) use($aryMonthAttendance) {
            $query->whereYear('attendanceDate','=',$aryMonthAttendance[0])->whereMonth('attendanceDate','=',$aryMonthAttendance[1]);
        }])->get();

        $monthDates = Attendance::getMonthDates($monthAttendance . '-01');
        return view('admin.attendance.index',compact('staffMonthlyAttendance','monthDates','monthAttendance'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		abort_if(Gate::denies('attendance_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $attendanceDate = \Carbon\Carbon::now()->format('Y-m-d');
        if ($request->get('attendanceDate') && strlen($request->get('attendanceDate'))) {
            $attendanceDate = \Carbon\Carbon::parse($request->get('attendanceDate'))->format('Y-m-d');
        }
        $staffAttendance = Staff::where('paymentAmount','>','0')->with(['attendance' => function($query) use($attendanceDate) {
            $query->where('attendanceDate','=',$attendanceDate);
        }])->get();
        $leaveTypes = \App\Models\LeaveType::all();
        return view('admin.attendance.create',compact('staffAttendance','attendanceDate','leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
		try {
            $staffIDs = $request->get('staffIDs');
            $aryStaffAttendance = $request->get('staffAttendances');
            $aryDescriptions = $request->get('descriptions');
            foreach ($staffIDs as $idx => $staffID) {
                $Staff = \App\Models\Staff::find($staffID);
                $transaction = \App\Models\Transaction::where('transactionDate',\Carbon\Carbon::parse($request->get('attendanceDate'))->toDateString())
    				->whereHas('transactionDetails', function($query) use ($Staff) {
    					$query->where('headID',\Config::get('constants.account_heads.salaries_payable'))->where('subHeadID',$Staff->headID)->where('isDebit',0);
    				})->get();

                if (count($transaction)) {
    				// Delete it
                    $transaction[0]->delete();
    			}

                $attendance = Attendance::where('staffID',$staffID)->where('attendanceDate',$request->get('attendanceDate'))->first();
                if ($attendance) {
                    $attendance->delete();
                }

                Attendance::updateOrCreate([
                    'leaveTypeID' => $aryStaffAttendance[$idx],
                    'staffID' => $staffID,
                    'attendanceDate' => $request->get('attendanceDate'),
                    'hours' => 8,
                    'description' => $aryDescriptions[$idx],
                    'createdByUserID' => Auth::id()
                ]);
            }
			DB::commit();
			$request->session()->flash('message', 'Attendance created successfully!');
		} catch (\Exception $e) {
			DB::rollback();
            $request->session()->flash('error', 'An error occurred while creating attendance!');
		}
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
