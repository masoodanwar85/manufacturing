<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'purchaseOrderDetail';
    protected $primaryKey = 'purchaseOrderDetailID';
	protected $with = ['product'];
    public $timestamps = false;

    protected $fillable = ['purchaseOrderDetailID','purchaseOrderID','productID','quantity','quantityUnits','exchangeRate','perUnitPrice'];

	public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder','purchaseOrderID','purchaseOrderID');
    }

	public function product()
	{
		return $this->belongsTo('App\Models\Product','productID','productID');
	}

	public function stockDetail()
    {
        return $this->hasOne('App\Models\StockDetail','purchaseOrderDetailID','purchaseOrderDetailID');
    }
}
