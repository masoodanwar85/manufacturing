<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    use HasFactory;
    protected $table = 'measurementUnit';
    protected $primaryKey = 'unitID';
    public $timestamps = false;
    protected $fillable = ['unitName','symbol','unitType'];

    public function products()
    {
        return $this->hasMany('App\Models\Product','unitID','unitID');
    }
}
