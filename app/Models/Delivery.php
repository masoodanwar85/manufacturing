<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $table = 'delivery';
    protected $primaryKey = 'deliveryID';
    protected $with = ['deliveryDetails'];
    public $timestamps = false;
    protected $guarded = [];

    public function deliveryDetails()
    {
        return $this->hasMany('App\Models\DeliveryDetails','deliveryID','deliveryID');
    }

    public function salesOrders(){
        return $this->belongsToMany('App\Models\SalesOrder', 'deliveryDetails', 'deliveryID', 'salesOrderID');
    }

    public function route()
    {
        return $this->belongsTo('App\Models\Route','routeID','routeID');
    }

    public function godown()
    {
        return $this->belongsTo('App\Models\Godown','godownID','godownID');
    }

    public function transport()
    {
        return $this->belongsTo('App\Models\Transport','transportID','transportID');
    }

}
