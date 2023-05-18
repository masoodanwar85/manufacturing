<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;
use App\Models\Delivery;
use App\Models\DeliveryDetails;
use App\Models\Product;
use App\Models\Route;
use App\Models\Godown;
use App\Models\SalesOrder;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('delivery_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Delivery::with(['route','godown','transport']);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'delivery_read';
                $editGate      = 'delivery_update';
                $deleteGate    = 'delivery_delete';
                $crudRoutePart = 'delivery';
                $primaryKey = 'deliveryID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('deliveryDate', function ($row) {
                return $row->deliveryDate ? $row->deliveryDate : "";
            });
            $table->editColumn('route', function ($row) {
                return $row->route->route ? $row->route->route : "";
            });
            $table->editColumn('godown', function ($row) {
                return $row->godown->name ? $row->godown->name : "";
            });
            $table->editColumn('transport', function ($row) {
                return $row->transport->name ? $row->transport->name : "";
            });
            $table->editColumn('createdByUser', function ($row) {
                return $row->createdByUserID ? $row->createdByUserID : "";
            });
            $table->editColumn('updatedByUser', function ($row) {
                return $row->createdByUserID ? $row->createdByUserID : "";
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->editColumn('dateUpdated', function ($row) {
                return $row->dateUpdated ? $row->dateUpdated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.delivery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('delivery_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $routes = Route::all();
        $godowns = Godown::all();
        $transports = Transport::all();
        $salesOrders = SalesOrder::getSaleOrders(0,'');
        return view('admin.delivery.create',compact('routes','godowns','transports','salesOrders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDeliveryRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->request->add(['createdByUserID' => Auth::id()]);
            $request->request->add(['updatedByUserID' => Auth::id()]);
            $delivery = Delivery::create($request->except('salesOrderID'));
            $delivery->salesOrders()->attach($request->salesOrderID);
            foreach ($request->salesOrderID as $id){
                //change salesOrderStatus to "OUT FOR DELIVERY"
                SalesOrder::where('salesOrderID',$id)->update(['salesOrderStatusID'=>2]);
            }
            $request->session()->flash('message', 'Delivery created successfully!');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'An error occurred while adding delivery!');
        }
        return redirect()->route('delivery.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        abort_if(Gate::denies('delivery_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.delivery.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        abort_if(Gate::denies('delivery_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $routes = Route::all();
        $godowns = Godown::all();
        $transports = Transport::all();
        $salesOrders = SalesOrder::getSaleOrders(0,'');
        return view('admin.delivery.edit',compact('routes','godowns','transports','delivery','salesOrders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDeliveryRequest $request, Delivery $delivery)
    {
        DB::beginTransaction();
        try {
            $request->request->add(['updatedByUserID' => Auth::id()]);
            $delivery->update($request->except('salesOrderID'));
            $delivery->salesOrders()->sync($request->salesOrderID);
            foreach ($request->salesOrderID as $id){
                //change salesOrderStatus to "OUT FOR DELIVERY"
                SalesOrder::where('salesOrderID',$id)->update(['salesOrderStatusID'=>2]);
            }
            $request->session()->flash('message', 'Delivery updated successfully!');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('error', 'An error occurred while updating delivery!');
        }
        return redirect()->route('delivery.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery, Request $request)
    {
        abort_if(Gate::denies('delivery_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delivery->salesOrders()->detach();
        if ($delivery->delete()) {
            $request->session()->flash('message', 'Delivery deleted successfully!');
        } else {
            $request->session()->flash('error', 'An error occurred while deleting delivery!');
        }
        return redirect()->route('delivery.index');
    }

    public function deliveryReport(Delivery $delivery)
    {
        $delivery->load(['salesOrders','salesOrders.stockDetailStatuses.stockDetail.product'])->get();
        return view('admin.delivery.deliveryReport',compact('delivery'));
    }
}
