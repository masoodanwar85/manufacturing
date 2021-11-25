<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchaseOrder';
    protected $primaryKey = 'purchaseOrderID';
	protected $with = ['supplier','customer','purchaseOrderDetails'];
    public $timestamps = false;

    protected $fillable = ['purchaseOrderID','parentID','supplierID','customerID','batchID','lastGodownID','purchaseOrderDate','description','isLocked','createdByUserID'];

	public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier','supplierID','supplierID');
    }

	public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customerID','customerID');
    }

	public function batch()
    {
        return $this->belongsTo('App\Models\Batch','batchID','batchID');
    }

    public function godown() {
        return $this->belongsTo('App\Models\Godown','lastGodownID','godownID');
    }

	public function purchaseOrderDetails()
    {
        return $this->hasMany('App\Models\PurchaseOrderDetail','purchaseOrderID','purchaseOrderID');
    }

	public function transactions()
	{
		return $this->belongsToMany('App\Models\Transaction','purchaseOrderTransaction','purchaseOrderID','transactionID')->withPivot('purchaseOrderDetailID','isExpense');
	}

	public function stock()
    {
        return $this->hasOne('App\Models\Stock','purchaseOrderID','purchaseOrderID');
    }
}
