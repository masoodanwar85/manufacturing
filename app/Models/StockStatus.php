<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockStatus extends Model
{
    use HasFactory;
    protected $table = 'stockStatus';
    protected $primaryKey = 'statusID';
    public $timestamps = false;
    protected $fillable = ['statusID','status','isAvailableForSale'];

	public function stockDetailStatuses()
    {
        return $this->hasMany('App\Models\StockDetailStatus','statusID','statusID');
    }
}
