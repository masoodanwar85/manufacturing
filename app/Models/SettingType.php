<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingType extends Model
{
    use HasFactory;
	protected $table = 'settingType';
    protected $primaryKey = 'settingTypeID';
    public $timestamps = false;
    protected $fillable = ['settingTypeID','settingTypeCode','settingType'];

    public function settingValues()
    {
        return $this->hasMany('App\Models\SettingValue','settingTypeID','settingTypeID');
    }

	public function settings()
    {
        return $this->hasMany('App\Models\Setting','settingTypeID','settingTypeID');
    }
}
