<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingValue extends Model
{
    use HasFactory;
	protected $table = 'settingValue';
    protected $primaryKey = 'settingValueID';
    public $timestamps = false;
    protected $fillable = ['settingValueID','settingTypeID','foreignID','settingValue','createdByUserID'];

    public function setting() {
        return $this->belongsTo('App\Models\Setting','settingID','settingID');
    }

	public function settingType() {
        return $this->belongsTo('App\Models\SettingType','settingTypeID','settingTypeID');
    }
}
