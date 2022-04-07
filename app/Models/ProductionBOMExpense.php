<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductionBOMExpense extends Model
{
    use HasFactory;
    protected $table = 'productionBOMExpense';
    protected $primaryKey = 'productionBOMExpenseID';
	public $timestamps = false;
    protected $fillable = ['productionBOMID','expenseHeadID','amount','createdByUserID'];

    public function productionBOM()
    {
        return $this->belongsTo('App\Models\ProductionBOM','productionBOMID','productionBOMID');
    }

    public function head()
    {
        return $this->belongsTo('App\Models\AccountHead','expenseHeadID','headID');
    }
}
