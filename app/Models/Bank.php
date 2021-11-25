<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'bank';
    protected $primaryKey = 'bankID';
    public $timestamps = false;

    protected $fillable = ['bankID','bankName'];

	public function bankAccounts()
    {
        return $this->hasMany('App\Models\BankAccount','bankID','bankID');
    }

	public function bankInstrumentDetails() {
		return $this->hasMany('App\Models\BankInstrumentDetail','bankID','bankID');
	}
}
