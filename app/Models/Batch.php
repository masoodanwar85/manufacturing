<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batch';
    protected $primaryKey = 'batchID';
    public $timestamps = false;

    protected $fillable = ['batchID','batchName','startDate','endDate','description','createdByUserID'];

	public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder','batchID','batchID');
    }

	public function stockDetailStatuses()
    {
        return $this->hasMany('App\Models\StockDetailStatus','batchID','batchID');
    }

	public function openingBalances() {
		return $this->hasMany('App\Models\OpeningBalance','batchID','batchID');
	}
}
