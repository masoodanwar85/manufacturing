<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductBOM extends Model
{
    use HasFactory;
    protected $table = 'productBOM';
    protected $primaryKey = 'productBOMID';
	protected $with = ['product'];
    public $timestamps = false;
    protected $fillable = ['productID','createdByUserID'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','productID','productID');
    }

    public function items()
    {
        return $this->hasMany('App\Models\ProductBOMItem','productBOMID','productBOMID');
    }

    public function expenses()
    {
        return $this->hasMany('App\Models\ProductBOMExpense','productBOMID','productBOMID');
    }
}
