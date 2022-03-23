<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function index() {
		return view('admin.reports.index');
	}

	public function profitLoss() {
		abort_if(Gate::denies('report_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Profit Or Loss = (Revenues / Income / Sales) - (Expense)
        $aryIncomeExpenseHeadIDs = [\Config::get('constants.account_heads.expense'),\Config::get('constants.account_heads.revenue')];
        $aryIncomeExpenseAllHeadIDs = \App\Models\AccountHead::whereIn('rootHeadID',$aryIncomeExpenseHeadIDs)->orWhereIn('headID',$aryIncomeExpenseHeadIDs)->pluck('headID')->toArray();
        $allTransactions = \App\Models\Transaction::with('transactionDetails')->whereHas('transactionDetails', function($query) use ($aryIncomeExpenseAllHeadIDs) {
            return $query->whereIn('headID',$aryIncomeExpenseAllHeadIDs);
        })->orderBy('transactionID', 'asc')->get();

        $profitLoss = \App\Services\ReportService::getProfitLoss();
        return view('admin.reports.profitLoss', compact('profitLoss','allTransactions','aryIncomeExpenseAllHeadIDs'));
	}

    public function cashInOut(Request $request) {
        abort_if(Gate::denies('report_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $aryCashAllHeadIDs = \App\Models\AccountHead::whereIn('headID',[\Config::get('constants.account_heads.cash'),\Config::get('constants.account_heads.petty_cash')])->pluck('headID')->toArray();

        $monthReport = date('Y') . '-' . date('m');

        if (!empty($request->monthReport) && strlen($request->monthReport)) {
            $monthReport = $request->monthReport;
        }

        $fromDate = $monthReport.'-01';
        if (!empty($request->fromDate) && strlen($request->fromDate)) {
            $fromDate = $request->fromDate;
        }

        $tsFromDate = strtotime('+23 hour +59 minutes +59 seconds',strtotime($fromDate));
        $toDate = date("Y-m-t H:i:s", $tsFromDate);

        if (!empty($request->toDate) && strlen($request->toDate)) {
            $toDate = date("Y-m-d H:i:s", strtotime('+23 hour +59 minutes +59 seconds',strtotime($request->toDate)));
        }

        // TODO:: Convert amount into its exchange rate based on config settings

        $obFromDate = date('Y-m-d',strtotime('2018-01-01'));
        $obToDate = date_add(new \DateTime(date("Y-m-d H:i:s", $tsFromDate)),date_interval_create_from_date_string("-1 days"));

        // $openingBalance = \App\Models\TransactionDetail::whereIn('headID',$aryCashAllHeadIDs)->whereIn('transactionID',\App\Models\Transaction::whereBetween('transactionDate',[$obFromDate,$obToDate])->pluck('transactionID')->toArray())->get()->map(function($item,$key) {
        //     return ($item->isDebit == 1 ? $item->amount : $item->amount * -1);
        // })->sum();

        $openingBalance = DB::table('transaction')
                            ->join('transactionDetail','transaction.transactionID','=','transactionDetail.transactionID')
                            ->select(DB::raw('SUM(CASE WHEN transactionDetail.isDebit THEN transactionDetail.amount ELSE (transactionDetail.amount*-1) END) AS openingBalance'))
                            ->whereIn('transactionDetail.headID',$aryCashAllHeadIDs)
                            ->whereBetween('transaction.transactionDate',[$obFromDate,$obToDate->format('Y-m-d')])
                            ->first()->openingBalance;

        // $openingBalance = \App\Models\TransactionDetail::whereIn('headID',$aryCashAllHeadIDs)->with(['transaction' =>
        //                                                                                                     function($query) use ($obFromDate,$obToDate) {
        //                                                                                                         return $query->whereBetween('transactionDate',[$obFromDate,$obToDate]);
        //                                                                                                     }
        //                                                                                                 ])->get()->map(function($item,$key) {
        //     return ($item->isDebit == 1 ? $item->amount : $item->amount * -1);
        // })->sum();
        //
        // $openingBalance2 = \App\Models\Transaction::whereBetween('transactionDate',[$obFromDate,$obToDate])->with(['transactionDetails' => function($query) {
        //
        // }]);



        // dd($openingBalance2);

        $allTransactions = \App\Models\Transaction::with('transactionDetails')->whereBetween('transactionDate',[$fromDate,$toDate])->whereHas('transactionDetails', function($query) use ($aryCashAllHeadIDs) {
            return $query->whereIn('headID',$aryCashAllHeadIDs);
        })->orderBy('transactionID', 'desc')->get();

        return view('admin.reports.cashInOut', compact('allTransactions','aryCashAllHeadIDs','monthReport','openingBalance','fromDate','toDate'));
    }

    public function accounts() {
        abort_if(Gate::denies('report_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $allTransactions = \App\Models\Transaction::with('transactionDetails.head')->orderBy('transactionID', 'desc')->get();
        return view('admin.reports.accounts', compact('allTransactions'));
    }

	// public function ledgers() {
	// 	abort_if(Gate::denies('report_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    //     return view('admin.reports.ledgers');
	// }

    public function sales(Request $request) {
        abort_if(Gate::denies('report_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (!empty($request->isSearchByBillBook)) {
            $isSearchByBillBook = $request->isSearchByBillBook;
        } else {
            $isSearchByBillBook = 0;
        }
		if (!empty($request->isSearchByCashBook)) {
			$isSearchByCashBook = $request->isSearchByCashBook;
		} else {
			$isSearchByCashBook = 0;
		}

		$monthReport = date('Y') . '-' . date('m');

        if (!empty($request->monthReport) && strlen($request->monthReport)) {
            $monthReport = $request->monthReport;
        }

        $fromDate = $monthReport.'-01';
        if (!empty($request->fromDate) && strlen($request->fromDate)) {
            $fromDate = $request->fromDate;
        }

        $tsFromDate = strtotime('+23 hour +59 minutes +59 seconds',strtotime($fromDate));
        $toDate = date("Y-m-t H:i:s", $tsFromDate);

        if (!empty($request->toDate) && strlen($request->toDate)) {
            $toDate = date("Y-m-d H:i:s", strtotime('+23 hour +59 minutes +59 seconds',strtotime($request->toDate)));
        }

		// DB::enableQueryLog();

		if ($isSearchByBillBook == 1 && $isSearchByCashBook == 1) {
			$allSalesOrder = \App\Models\SalesOrder::with('transactions')->where('bookSerial','LIKE','CB-%')->orWhere('bookSerial','LIKE','BB-%')->whereBetween('orderDate',[$fromDate,$toDate])->orderBy('orderDate', 'desc')->get();
		} elseif ($isSearchByBillBook == 1) {
			$allSalesOrder = \App\Models\SalesOrder::with('transactions')->where('bookSerial','LIKE','BB-%')->whereBetween('orderDate',[$fromDate,$toDate])->orderBy('orderDate', 'desc')->get();
		} elseif ($isSearchByCashBook == 1) {
			$allSalesOrder = \App\Models\SalesOrder::with('transactions')->where('bookSerial','LIKE','CB-%')->whereBetween('orderDate',[$fromDate,$toDate])->orderBy('orderDate', 'desc')->get();
		} else {
			$allSalesOrder = \App\Models\SalesOrder::with('transactions')->whereBetween('orderDate',[$fromDate,$toDate])->orderBy('orderDate', 'desc')->get();
		}

		// dd(DB::getQueryLog());

		return view('admin.reports.sales', compact('allSalesOrder','monthReport','isSearchByBillBook','isSearchByCashBook'));
	}

    public function duplicates(Request $request) {
		abort_if(Gate::denies('report_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $allTransactions = \App\Models\Transaction::select('transactionTypeNumber')->selectRaw('count(transactionTypeNumber) AS billNo')->groupBy('transactionTypeNumber')->orderBy('billNo', 'desc')->having('billNo','>',1)->get();
        return view('admin.reports.duplicates', compact('allTransactions'));
    }

    public function missings(Request $request) {
		abort_if(Gate::denies('report_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $missings = \App\Models\InvoiceBooks::getInvoiceBooksMissingSerialNumbers();
        $missings_grouped = array();
        foreach ($missings as $key => $item) {
            $missings_grouped[$item->bookType . '-' . $item->bookNumber][] = $item;
        }
        return view('admin.reports.missings', compact('missings_grouped'));
    }
}
