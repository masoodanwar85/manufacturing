<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BookSerials extends Model
{
    use HasFactory;
    protected $table = 'bookSerials';
    protected $primaryKey = 'bookSerialID';
	public $timestamps = false;
    protected $fillable = ['bookSerialID','invoiceBookID','serialNumber','reason','createdByUserID'];

    public function invoiceBook()
    {
        return $this->belongsTo('App\Models\InvoiceBook','invoiceBookID','invoiceBookID');
    }
}
