<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDetailStatus extends Model
{
    use HasFactory;
    protected $table = 'stockDetailStatus';
    protected $primaryKey = 'stockDetailStatusID';
    public $timestamps = false;
    protected $fillable = ['stockDetailStatusID','stockDetailID','statusID','batchID','godownID','discount','quantity','quantityUnits','salePrice','createdByUserID'];

	public function stockStatus()
    {
        return $this->belongsTo('App\Models\StockStatus','statusID','statusID');
    }

	public function batch()
    {
        return $this->belongsTo('App\Models\Batch','batchID','batchID');
    }

	public function stockDetail()
    {
        return $this->belongsTo('App\Models\StockDetail','stockDetailID','stockDetailID');
    }

    public function godown()
	{
		return $this->belongsTo('App\Models\Godown','godownID','godownID');
	}

	public function salesOrders()
	{
		return $this->belongsToMany('App\Models\StockDetailStatus','salesOrderDetail','stockDetailStatusID','salesOrderID');
	}
}
