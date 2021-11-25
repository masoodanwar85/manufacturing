<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffType;
use App\Http\Requests\StoreStaffTypeRequest;
use App\Http\Requests\UpdateStaffTypeRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StaffTypeController extends Controller
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
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $query = DB::table('staffType')
                        ->leftJoin('staff','staff.staffTypeID','=','staffType.staffTypeID')
                        ->select(DB::raw('staffType.*,count(staff.staffTypeID) as noOfStaff'))
                        ->groupBy('staffType.staffTypeID')
                        ->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'staff_read';
                $editGate      = 'staff_update';
                $deleteGate    = 'staff_delete';
                $crudRoutePart = 'staffType';
                $primaryKey = 'staffTypeID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('staffType', function ($row) {
                return $row->staffType ? $row->staffType : "";
            });
            $table->editColumn('noOfStaff', function ($row) {
                return $row->noOfStaff ? $row->noOfStaff : "0";
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.staffType.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('staff_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.staffType.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffTypeRequest $request)
    {
        $request->request->add(['createdByUserID' => Auth::id()]);
        $staffType = StaffType::create($request->all());
        $request->session()->flash('message', 'Staff type created successfully!');
        return redirect()->route('staffType.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function show(StaffType $staffType)
    {
		abort_if(Gate::denies('staff_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.staffType.show', compact('staffType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffType $staffType)
    {
		abort_if(Gate::denies('staff_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.staffType.edit', compact('staffType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStaffTypeRequest $request, StaffType $staffType)
    {
        $staffType->update($request->all());
        $request->session()->flash('message', 'Staff type updated successfully!');
        return redirect()->route('staffType.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffType $staffType, Request $request)
    {
		abort_if(Gate::denies('staff_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		if (count($staffType->staffs->toArray()) >= 1) {
			$request->session()->flash('warning', 'Staff type cannot be deleted due to assigned Staff!');
		} else {
			$staffType->delete();
			$request->session()->flash('message', 'Staff type deleted successfully!');
		}

        return redirect()->route('staffType.index');
    }
}
