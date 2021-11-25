<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$totalPurchases = \App\Models\PurchaseOrder::count();
		$totalSales = \App\Models\SalesOrder::count();
		$thresholdStocks = \App\Models\Stock::getStock(NULL,TRUE);
        $receivables['customers'] = \App\Models\Customer::getBalance()[0]->totalPayable;
		$receivables['staff'] = \App\Models\Staff::getBalance()[0]->totalPayable;
		$receivables['suppliers'] = \App\Models\Supplier::getBalance()[0]->totalPayable;
        $customers = \App\Models\Customer::orderBy('customerName')->get();
		return view('admin.dashboard',compact('totalPurchases','totalSales','thresholdStocks','receivables','customers'));
    }

    public function search(Request $request)
    {
        switch ($request->searchBy) {
            case 'RB':
                return redirect()->route('accountHead.paymentsReceipts',['isIgnoreDates' => 1,'transactionTypeNumber' => $request->transactionTypeNumber]);
                break;
            case 'BB':
            case 'CB':
                $searchCriteria = $request->transactionTypeNumber;
				if (!is_numeric(strpos($searchCriteria,$request->searchBy.'-'))) {
                    if (substr_count($searchCriteria,'-') == 1) {
                        $searchCriteria = $request->searchBy.'-'.$searchCriteria;
                    } else {
                        $searchCriteria = $request->searchBy.'-'.'%'.$searchCriteria.'%';
                    }
                }
				$salesOrder = \App\Models\SalesOrder::where('bookSerial','LIKE',$searchCriteria)->get();
				if (!count($salesOrder)) {
                    die('No Record Exists');
                } else {
                    if (count($salesOrder) > 1) {
                        return redirect()->route('sales.index',['bookSerial' => $request->transactionTypeNumber]);
                    } else {
                        return redirect()->route('sales.show',$salesOrder[0]->salesOrderID);
                    }
                }
                break;
            default:
                die('Invalid Search');
        }
    }

    public function test() {

        dd($accountsReceivables);
    }
}
