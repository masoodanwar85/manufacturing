<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    protected $table = 'route';
    protected $primaryKey = 'routeID';
    public $timestamps = false;
    protected $guarded = [];

    public function deliveries()
    {
        return $this->hasMany('App\Models\Delivery','routeID','routeID');
    }
}
