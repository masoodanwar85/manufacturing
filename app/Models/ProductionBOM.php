<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductionBOM extends Model
{
    use HasFactory;
    protected $table = 'productionBOM';
    protected $primaryKey = 'productionBOMID';
	protected $with = ['product'];
    public $timestamps = false;
    protected $fillable = ['productionID','productID','quantity','productionStageID','createdByUserID'];

    public function production()
    {
        return $this->belongsTo('App\Models\Production','productionID','productionID');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product','productID','productID');
    }

    public function items()
    {
        return $this->hasMany('App\Models\ProductionBOMItem','productionBOMID','productionBOMID');
    }

    public function expenses()
    {
        return $this->hasMany('App\Models\ProductionBOMExpense','productionBOMID','productionBOMID');
    }
}
