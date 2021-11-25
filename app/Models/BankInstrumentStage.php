<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInstrumentStage extends Model
{
    use HasFactory;
    protected $table = 'bankInstrumentStage';
    protected $primaryKey = 'bankInstrumentStageID';
    public $timestamps = false;
    protected $fillable = ['bankInstrumentStageID','bankInstrumentDetailID','bankInstrumentStatusID'];

    public function bankInstrumentDetail()
    {
        return $this->belongsTo('App\Models\BankInstrumentDetail','bankInstrumentDetailID','bankInstrumentDetailID');
    }
	public function bankInstrumentStatus()
    {
        return $this->belongsTo('App\Models\BankInstrumentStatus','bankInstrumentStatusID','bankInstrumentStatusID');
    }
}
