<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffType extends Model
{
    use HasFactory;
    protected $table = 'staffType';
    protected $primaryKey = 'staffTypeID';
    public $timestamps = false;
    protected $fillable = ['staffTypeID','staffType','createdByUserID'];

    public function staffs()
    {
        return $this->hasMany('App\Models\Staff','staffTypeID','staffTypeID');
    }
}
