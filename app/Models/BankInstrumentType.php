<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInstrumentType extends Model
{
    use HasFactory;
    protected $table = 'bankInstrumentType';
    protected $primaryKey = 'bankInstrumentTypeID';
    public $timestamps = false;
    protected $fillable = ['bankInstrumentTypeID','bankInstrumentType'];

    public function bankInstrumentDetails()
    {
        return $this->hasMany('App\Models\BankInstrumentDetail','bankInstrumentTypeID','bankInstrumentTypeID');
    }
}
