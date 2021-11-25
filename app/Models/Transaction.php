<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transaction';
    protected $primaryKey = 'transactionID';
	protected $with = ['transactionDetails'];
    public $timestamps = false;
    protected $fillable = ['transactionID','transactionTypeID','transactionTypeNumber','isPaymentReceipt','transactionDate','batchID','exchangeRate','createdByUserID'];

    public function transactionType()
    {
        return $this->belongsTo('App\Models\TransactionType','transactionTypeID','transactionTypeID');
    }

    public function transactionDetails()
    {
        return $this->hasMany('App\Models\TransactionDetail','transactionID','transactionID');
    }

	public function purchaseOrders()
	{
		return $this->belongsToMany('App\Models\PurchaseOrder','purchaseOrderTransaction','transactionID','purchaseOrderID');
	}

	public function salesOrders()
	{
		return $this->belongsToMany('App\Models\SalesOrder','salesOrderTransaction','transactionID','salesOrderID');
	}

	public function openingBalance() {
		return $this->hasOne('App\Models\OpeningBalance','transactionID','transactionID');
	}
}
