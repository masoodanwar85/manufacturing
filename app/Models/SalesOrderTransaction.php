<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderTransaction extends Model
{
    use HasFactory;
    protected $table = 'salesOrderTransaction';
    protected $primaryKey = 'salesOrderTransactionID';
    public $timestamps = false;
    protected $fillable = ['salesOrderTransactionID','salesOrderID','transactionID','createdByUserID'];

	public function salesOrder()
	{
		return $this->belongsTo('App\Models\SalesOrder','salesOrderID','salesOrderID');
	}
}
