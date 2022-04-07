<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductionBOMItem extends Model
{
    use HasFactory;
    protected $table = 'ProductionBOMItem';
    protected $primaryKey = 'productionBOMItemID';
	protected $with = ['product'];
    public $timestamps = false;
    protected $fillable = ['productionBOMID','productID','quantity','consumed','createdByUserID'];

    public function productionBOM()
    {
        return $this->belongsTo('App\Models\ProductionBOM','productionBOMID','productionBOMID');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product','productID','productID');
    }
}
