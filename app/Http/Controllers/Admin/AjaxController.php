<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
	public function handle($method, Request $request) {
        return $this->$method($request->all());
    }

	private function getReceivableInfo($arguments)
    {
		$receivables_info = [];
		$month = $arguments['month'];
		$start_date = $month .'-01';
		$end_date = Carbon::parse($start_date)->endOfMonth()->toDateString();
		$receivables_info['customer'] = \App\Models\Customer::getReceivablesReceived($customerID = NULL,$start_date = $start_date, $end_date = $end_date)[0];

		return response()->json($receivables_info);
	}

	private function getStockTransferDetail($arguments)
	{
		$stockTransferDetail = DB::table("stockDetailStatus")
                            ->join('stockDetail','stockDetail.stockDetailID','=','stockDetailStatus.stockDetailID')
							->join('product','product.productID','=','stockDetail.productID')
                            ->join('godown AS newGodown','newGodown.godownID','=','stockDetailStatus.godownID')
                            ->join('godown AS prevGodown','prevGodown.godownID','=','stockdetail.godownID')
                            ->select("stockDetailStatus.statusID", "stockDetailStatus.quantity", "stockDetailStatus.bookSerial", "stockDetailStatus.transferDate", "product.productName", "newGodown.name AS newGodownName", "prevGodown.name AS prevGodownName")
                            ->where("stockDetailStatus.bookSerial", $arguments['bookSerial'])
                            ->get();
		return response()->json($stockTransferDetail);
	}
}
