<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInstrumentStatus extends Model
{
    use HasFactory;
    protected $table = 'bankInstrumentStatus';
    protected $primaryKey = 'bankInstrumentStatusID';
    public $timestamps = false;
    protected $fillable = ['bankInstrumentStatusID','bankInstrumentStatus'];

    public function bankInstrumentStages()
    {
        return $this->hasMany('App\Models\BankInstrumentStage','bankInstrumentStatusID','bankInstrumentStatusID');
    }
}
