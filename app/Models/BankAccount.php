<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bankAccount';
    protected $primaryKey = 'bankAccountID';
	protected $with = ['bank','head'];
    public $timestamps = false;

    protected $fillable = ['bankAccountID','bankID','headID','accountTitle','accountNumber','branchCode','branchName','branchLocation','description','createdByUserID'];

	public function bank()
    {
        return $this->belongsTo('App\Models\Bank','bankID','bankID');
    }

	public function head()
	{
		return $this->belongsTo('App\Models\AccountHead','headID','headID');
	}

	public function bankInstrumentDetails() {
		return $this->hasMany('App\Models\BankInstrumentDetail','bankAccountID','bankAccountID');
	}
}
