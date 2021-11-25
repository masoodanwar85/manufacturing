<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
	protected $table = 'setting';
    protected $primaryKey = 'settingID';
    public $timestamps = false;
    protected $fillable = ['settingID','settingTypeID','fieldTypeID','settingName','settingCode','label','tab','group','sortOrder','defaultValue'];

    public function settingType()
    {
        return $this->belongsTo('App\Models\SettingType','settingTypeID','settingTypeID');
    }

	public function fieldType()
    {
        return $this->belongsTo('App\Models\FieldType','fieldTypeID','fieldTypeID');
    }

	public function settingValues()
    {
        return $this->hasMany('App\Models\SettingValue','settingID','settingID');
    }
}
