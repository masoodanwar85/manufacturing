<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDetail extends Model
{
    use HasFactory;
    protected $table = 'stockDetail';
    protected $primaryKey = 'stockDetailID';
    public $timestamps = false;
    protected $fillable = ['stockDetailID','stockID','purchaseOrderDetailID','productID','godownID','quantity','quantityUnits','purchasePrice'];

	public function stock()
    {
        return $this->belongsTo('App\Models\Stock','stockID','stockID');
    }

	public function purchaseOrderDetail()
    {
        return $this->belongsTo('App\Models\PurchaseOrderDetail','purchaseOrderDetailID','purchaseOrderDetailID');
    }

	public function product()
	{
		return $this->belongsTo('App\Models\Product','productID','productID');
	}

	public function godown()
	{
		return $this->belongsTo('App\Models\Godown','godownID','godownID');
	}

	public function stockDetailStatuses()
	{
		return $this->hasMany('App\Models\StockDetailStatus','stockDetailID','stockDetailID');
	}
}
