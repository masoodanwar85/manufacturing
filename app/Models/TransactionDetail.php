<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transactionDetail';
    protected $primaryKey = 'transactionDetailID';
	protected $with = ['head','subHead'];
    public $timestamps = false;

    protected $fillable = ['transactionDetailID','transactionID','headID','subHeadID','isDebit','amount','description'];

	public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transactionID','transactionID');
    }

	public function head()
	{
		return $this->belongsTo('App\Models\AccountHead','headID','headID');
	}

	public function subHead()
	{
		return $this->belongsTo('App\Models\AccountHead','subHeadID','headID');
	}

	public function bankInstrumentDetails() {
		return $this->hasMany('App\Models\BankInstrumentDetail','transactionDetailID','transactionDetailID');
	}
}
