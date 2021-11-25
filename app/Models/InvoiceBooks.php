<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvoiceBooks extends Model
{
    use HasFactory;
    protected $table = 'invoiceBooks';
    protected $primaryKey = 'invoiceBookID';
	public $timestamps = false;
    protected $fillable = ['invoiceBookID','bookType','bookNumber','startPage','endPage','createdByUserID'];

    public function serials()
    {
        return $this->hasMany('App\Models\BookSerials','invoiceBookID','invoiceBookID');
    }
}
