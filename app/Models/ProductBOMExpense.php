<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductBOMExpense extends Model
{
    use HasFactory;
    protected $table = 'productBOMExpense';
    protected $primaryKey = 'productBOMExpenseID';
	public $timestamps = false;
    protected $fillable = ['productBOMID','expenseHeadID','amount','createdByUserID'];

    public function productBOM()
    {
        return $this->belongsTo('App\Models\ProductBOM','productBOMID','productBOMID');
    }

    public function head()
    {
        return $this->belongsTo('App\Models\AccountHead','expenseHeadID','headID');
    }
}
