<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    use HasFactory;

	protected $table = 'openingBalance';
    protected $primaryKey = 'openingBalanceID';
	protected $with = ['batch','transaction'];
    public $timestamps = false;

    protected $fillable = ['openingBalanceID','batchID','transactionID','createdByUserID'];

	public function batch() {
        return $this->belongsTo('App\Models\Batch','batchID','batchID');
    }

	public function transaction() {
        return $this->belongsTo('App\Models\Transaction','transactionID','transactionID');
    }
}
