<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    use HasFactory;
	protected $table = 'fieldType';
    protected $primaryKey = 'fieldTypeID';
    public $timestamps = false;
    protected $fillable = ['fieldTypeID','fieldTypeCode','fieldType','sortOrder'];

    public function settings()
    {
        return $this->hasMany('App\Models\Setting','fieldTypeID','fieldTypeID');
    }
}
