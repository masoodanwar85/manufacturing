<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

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
}
