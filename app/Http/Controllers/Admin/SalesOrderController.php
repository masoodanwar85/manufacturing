<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use Gate;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Dompdf\Dompdf;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('sales_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $table = Datatables::of(SalesOrder::getSaleOrders());

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate   = 'sales_read';
				$editGate 	= 'sales_update';
				$deleteGate = 'sales_delete';
				$crudRoutePart = 'sales';
                $primaryKey = 'salesOrderID';

				$startButton = '<a class="btn btn-xs btn-warning" href="' . route('sales.invoice', $row->salesOrderID) . '"> Invoice </a>';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey',
					'startButton'
                ));
            });

            $table->editColumn('customerName', function ($row) {
                return $row->customerName ? $row->customerName . ' ( ' . $row->shopName . ')' : "";
            });
			$table->editColumn('orderDate', function ($row) {
                return $row->orderDate ? $row->orderDate : "";
            });
			$table->editColumn('totalAmount', function ($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted($row->totalAmount);
            });
			$table->editColumn('totalPaid', function ($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted($row->totalPaid);
            });
			$table->editColumn('remaining', function ($row) {
                return \App\Services\CurrencyService::getCurrencyFormatted($row->remaining);
            });
            $table->editColumn('paymentDueDate', function ($row) {
                return $row->paymentDueDate ? $row->paymentDueDate : "";
            });
			$table->editColumn('invoiceNumber', function ($row) {
                return $row->invoiceNumber ? $row->invoiceNumber : "";
            });
            $table->editColumn('bookSerial', function ($row) {
                return $row->bookSerial ? $row->bookSerial : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.sales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('sales_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$products = \App\Models\Stock::getProducts();
		$godownProducts = \App\Models\Stock::getGodownProducts();
		$now = date('Y-m-d');
		$customers = \App\Models\Customer::with('salesAgent')->get()->sortBy('customerName');
        $saleAgents = \App\Models\Staff::salesAgents()->orderBy('staffName')->get(['staffID','staffName','headID']);
        return view('admin.sales.create',compact('products','customers','now','godownProducts','saleAgents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalesOrderRequest $request)
    {
        (new \App\Services\SalesOrderService())->save($request);
        return redirect()->route('sales.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SalesOrder $sale)
    {
		abort_if(Gate::denies('sales_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$salesOrder = SalesOrder::with(['transactions.transactionDetails','stockDetailStatuses.stockDetail.product','stockDetailStatuses.godown'])->find($sale->salesOrderID);
		$cashHeadID = \Config::get('constants.account_heads.cash');
		return view('admin.sales.show', compact('salesOrder','cashHeadID'));
    }

	public function invoice(int $salesOrderID)
    {
		abort_if(Gate::denies('sales_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$salesOrder = SalesOrder::with(['transactions.transactionDetails','stockDetailStatuses.stockDetail.product'])->find($salesOrderID);
		$cashHeadID = \Config::get('constants.account_heads.cash');
		$client = \App\Models\Client::find(\App\Models\User::find(Auth::id())->clientID);
		return view('admin.sales.invoice', compact('salesOrder','cashHeadID','client'));
    }

	public function invoicePDF(int $salesOrderID)
    {
		abort_if(Gate::denies('sales_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$salesOrder = SalesOrder::with(['transactions.transactionDetails','stockDetailStatuses.stockDetail.product'])->find($salesOrderID);
		$cashHeadID = \Config::get('constants.account_heads.cash');
		$client = \App\Models\Client::find(\App\Models\User::find(Auth::id())->clientID);
		// $pdf = PDF::loadView('admin.sales.invoicePDF', compact('salesOrder','cashHeadID','isPDF'))->setOptions(['fontDir' => asset('vendor/fontawesome-free/css/all.min.css')]);
		$html = view('admin.sales.pdf', compact('salesOrder','cashHeadID','client'))->render();
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		// Render the HTML as PDF
		$dompdf->render();
		return $dompdf->stream("invoice.pdf", array("Attachment" => false));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesOrder $sale)
    {
        abort_if(Gate::denies('sales_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $products = \App\Models\Stock::getProducts();
		$flattenedStockProducts = [];
		foreach ($products as $stockProduct) {
			$flattenedStockProducts["product_" . $stockProduct->productID] = $stockProduct;
		}
		$godownProducts = \App\Models\Stock::getGodownProducts();
        $customers = \App\Models\Customer::with('salesAgent')->get()->sortBy('customerName');
		$cashHeadID = \Config::get('constants.account_heads.cash');
		$totalPayable = \App\Models\Customer::getBalance($sale->customerID)[0]->totalPayable;
		$salesOrder = SalesOrder::with(['transactions.transactionDetails','stockDetailStatuses.stockDetail.product','stockDetailStatuses.godown'])->find($sale->salesOrderID);
        $saleAgents = \App\Models\Staff::salesAgents()->orderBy('staffName')->get(['staffID','staffName','headID']);
        return view('admin.sales.edit', compact('salesOrder','products','customers','cashHeadID','totalPayable','godownProducts','flattenedStockProducts','saleAgents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalesOrderRequest $request, SalesOrder $sale)
    {
        (new \App\Services\SalesOrderService())->update($request, $sale);
        return redirect()->route('sales.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesOrder $sale, Request $request)
    {
        abort_if(Gate::denies('sales_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
			$SOTransactions = $sale->transactions;
			$sale->transactions()->detach();
			foreach ($SOTransactions as $transaction) {
				\App\Services\TransactionService::deleteTransaction($transaction->transactionID);
			}
			$sale->salesOrderDetails()->delete();
            $stockDetailStatusIDs = $sale->salesOrderDetails->pluck('stockDetailStatusID')->toArray();
			DB::table('stockDetailStatus')->whereIn('stockDetailStatusID', $stockDetailStatusIDs)->delete();
			$sale->delete();
			DB::commit();
			$request->session()->flash('message', 'Sales Order deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while deleting sales order!');
		}
        return redirect()->route('sales.index');
    }
}
