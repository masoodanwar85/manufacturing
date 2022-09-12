<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;
    protected $table = 'productType';
    protected $primaryKey = 'productTypeID';
    public $timestamps = false;
    protected $fillable = ['productType','createdByUserID'];

    public function products()
    {
        return $this->hasMany('App\Models\Product','productTypeID','productTypeID');
    }
}
