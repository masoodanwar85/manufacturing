<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductBOMItem extends Model
{
    use HasFactory;
    protected $table = 'productBOMItem';
    protected $primaryKey = 'productBOMItemID';
	protected $with = ['product'];
    public $timestamps = false;
    protected $fillable = ['productBOMID','productID','quantity','isConsumeable','createdByUserID'];

    public function productBOM()
    {
        return $this->belongsTo('App\Models\ProductBOM','productBOMID','productBOMID');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product','productID','productID');
    }
}
