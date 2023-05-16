<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderStatus extends Model
{
    use HasFactory;
    protected $table = 'salesOrderStatus';
    protected $primaryKey = 'salesOrderStatusID';
    public $timestamps = false;
    protected $guarded = [];

    public function salesOrder()
    {
        return $this->belongsTo('App\Models\SalesOrder','salesOrderStatusID','salesOrderStatusID');
    }
}
