<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInstrumentDetail extends Model
{
    use HasFactory;
    protected $table = 'bankInstrumentDetail';
    protected $primaryKey = 'bankInstrumentDetailID';
    public $timestamps = false;
    protected $fillable = ['bankInstrumentDetailID','transactionDetailID','bankInstrumentTypeID','bankID','bankAccountID','instrumentNumber','instrumentDate','instrumentAmount','description'];

    public function bankInstrumentStages()
    {
        return $this->hasMany('App\Models\BankInstrumentStage','bankInstrumentDetailID','bankInstrumentDetailID');
    }

	public function transactionDetail() {
		return $this->belongsTo('App\Models\TransactionDetail','transactionDetailID','transactionDetailID');
	}

	public function bankInstrumentType() {
		return $this->belongsTo('App\Models\BankInstrumentType','bankInstrumentTypeID','bankInstrumentTypeID');
	}

	public function bank() {
		return $this->belongsTo('App\Models\Bank','bankID','bankID');
	}

	public function bankAccount() {
		return $this->belongsTo('App\Models\BankAccount','bankAccountID','bankAccountID');
	}
}
