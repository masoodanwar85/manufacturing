<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Production extends Model
{
    use HasFactory;
    protected $table = 'production';
    protected $primaryKey = 'productionID';
	public $timestamps = false;
    protected $fillable = ['serial','description','isCompleted','createdByUserID'];

    public function boms()
    {
        return $this->hasMany('App\Models\ProductionBOM','productionID','productionID');
    }

    public function stock()
    {
        return $this->hasOne('App\Models\Stock','productionID','productionID');
    }
}
