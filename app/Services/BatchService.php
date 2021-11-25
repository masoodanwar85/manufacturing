<?php

namespace App\Services;

class BatchService {

	public static function getCurrentBatch() {
		$now = date('Y-m-d');
		return \App\Models\Batch::where('startDate', '<=', $now)->where('endDate', '>=', $now)->first();
	}
}
