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
	protected $with = ['leaveType','staff'];
    public $timestamps = false;
    protected $fillable = ['attendanceID','leaveTypeID','staffID','attendanceDate','description','createdByUserID'];

    public function leaveType()
    {
        return $this->belongsTo('App\Models\LeaveType','leaveTypeID','leaveTypeID');
    }

    public function staff()
    {
        return $this->hasOne('App\Models\Staff','staffID','staffID');
    }

    public static function getStaffMonthlyAttendance($date = null) {
        if ($date == null) {
            $date = date('Y-m-d');
        }

        $rawSQL = "
            select attendance.leaveTypeID,attendance.staffID,attendance.description,attendance.createdByUserID,dates.date
            from attendance
            right join (
            	select a.Date
            from (
                select last_day('".$date."') - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
                from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
                cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
                cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
            ) a
            where a.Date between CONCAT_WS('-', YEAR('".$date."'), MONTH('".$date."'), '01') and last_day('".$date."') order by a.Date
            ) as dates on dates.date = attendance.attendanceDate
            order by dates.date DESC
        ";
        return DB::select($rawSQL);
    }

    public static function getMonthDates($date = null) {
        if ($date == null) {
            $date = date('Y-m-d');
        }

        $rawSQL = "
            select a.Date
            from (
                select last_day('".$date."') - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
                from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
                cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
                cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
            ) a
            where a.Date between CONCAT_WS('-', YEAR('".$date."'), MONTH('".$date."'), '01') and last_day('".$date."') order by a.Date
        ";
        return DB::select($rawSQL);
    }
}
