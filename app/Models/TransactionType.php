<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;
    protected $table = 'transactionType';
    protected $primaryKey = 'transactionTypeID';
    public $timestamps = false;
    protected $fillable = ['transactionTypeID','transactionType'];

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction','transactionTypeID','transactionTypeID');
    }
}
