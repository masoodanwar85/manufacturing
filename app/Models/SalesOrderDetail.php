<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;
    protected $table = 'salesOrderDetail';
    protected $primaryKey = 'salesOrderDetailID';
    public $timestamps = false;
    protected $fillable = ['salesOrderDetailID','salesOrderID','stockDetailStatusID'];

	public function salesOrder()
	{
		return $this->belongsTo('App\Models\SalesOrder','salesOrderID','salesOrderID');
	}

	public function stockDetailStatus()
	{
		return $this->belongsTo('App\Models\StockDetailStatus','stockDetailStatusID','stockDetailStatusID');
	}
}
