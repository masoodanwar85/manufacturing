<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $table = 'leaveType';
    protected $primaryKey = 'leaveTypeID';
    public $timestamps = false;
    protected $fillable = ['leaveTypeID','leaveType','isPaidToMonthly','isPaidToDaily','createdByUserID'];

    public function attendances()
    {
        return $this->hasMany('App\Models\Attendance','leaveTypeID','leaveTypeID');
    }
}
