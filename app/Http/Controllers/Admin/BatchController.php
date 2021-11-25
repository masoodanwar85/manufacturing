<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Gate;
use App\Http\Requests\StoreBatchRequest;
use App\Http\Requests\UpdateBatchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('batch_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Batch::all()->sortBy('startDate');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'batch_read';
                $editGate      = 'batch_update';
                $deleteGate    = 'batch_delete';
                $crudRoutePart = 'batch';
                $primaryKey = 'batchID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('batchName', function ($row) {
                return $row->batchName ? $row->batchName : "";
            });
			$table->editColumn('startDate', function ($row) {
                return $row->startDate ? $row->startDate : "";
            });
			$table->editColumn('endDate', function ($row) {
                return $row->endDate ? $row->endDate : "";
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.batch.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('batch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.batch.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBatchRequest $request)
    {
        $request->request->add(['createdByUserID' => Auth::id()]);
        $batch = Batch::create($request->all());
        $request->session()->flash('message', 'Batch created successfully!');
        return redirect()->route('batch.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch)
    {
		abort_if(Gate::denies('batch_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.batch.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function edit(Batch $batch)
    {
		abort_if(Gate::denies('batch_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.batch.edit', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBatchRequest $request, Batch $batch)
    {
        $batch->update($request->all());
        $request->session()->flash('message', 'Batch updated successfully!');
        return redirect()->route('batch.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch, Request $request)
    {
		abort_if(Gate::denies('batch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($batch->delete()) {
			$request->session()->flash('message', 'Batch deleted successfully!');
		} else {
			$request->session()->flash('error', 'An error occurred while deleting batch!');
		}
        return redirect()->route('batch.index');
    }
}
