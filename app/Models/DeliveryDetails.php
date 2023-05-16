<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetails extends Model
{
    use HasFactory;
    protected $table = 'deliveryDetails';
    protected $primaryKey = 'deliveryDetailID';
    public $timestamps = false;
    protected $guarded = [];

    public function delivery()
    {
        return $this->belongsTo('App\Models\Delivery','deliveryID','deliveryID');
    }

    public function salesOrder()
    {
        return $this->belongsTo('App\Models\SalesOrder','salesOrderID','salesOrderID');
    }
}
