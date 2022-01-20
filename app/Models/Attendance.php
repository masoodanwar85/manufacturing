<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendance';
    protected $primaryKey = 'attendanceID';
	protected $with = ['leaveType'];
    public $timestamps = false;
    protected $fillable = ['attendanceID','leaveTypeID','staffID','attendanceDate','description','createdByUserID'];

    public function leaveType()
    {
        return $this->belongsTo('App\Models\LeaveType','leaveTypeID','leaveTypeID');
    }
}
