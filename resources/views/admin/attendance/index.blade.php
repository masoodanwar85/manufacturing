@extends('adminlte::page')
@section('title', 'Staff Monthly Attendance')

@section('content_header')
    <h1>Staff Monthly Attendance</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-cart-plus"></i> Attendance
			</h3>
			@can('attendance_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('attendance.create') }}">
				<i class="fas fa-plus-circle"></i> Add Attendance
			</a>
			@endcan
		</div>
		<div class="card-body">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputFromDate">Month</label>
                        <input type="month" class="form-control" name="monthAttendance" value="{{ $monthAttendance }}" />
                    </div>
					<div class="form-group col-md-2">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped table-hover ajaxTable attendance">
                <thead>
                    <tr>
                        <td>Staff</td>
                        @foreach ($monthDates as $monthDays)
                            <td>{{ $monthDays->Date }}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffMonthlyAttendance as $staffAtt)
                        <tr id="st-{{$staffAtt->staffID}}">
                            <td>{{ $staffAtt->staffName }}</td>
                            @foreach ($monthDates as $monthDays)
                                <td id="st-{{$staffAtt->staffID}}-{{$monthDays->Date}}" class="table-warning"></td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        table.table.table-bordered.table-striped.table-hover.ajaxTable.attendance thead tr td {
            rotate: degree('-90');
        }
        table.table.table-bordered.table-striped.table-hover.ajaxTable.attendance {
            font-size:smaller;
        }
    </style>
@stop

@section('js')
    <script>
        var staffAttendance = {
            @foreach ($staffMonthlyAttendance as $staffAtt)
                "st-{{$staffAtt->staffID}}" : {
                    "attendance" : [
                    @foreach ($staffAtt->attendance as $att)
                        {
                            "attDate" : "{{$att->attendanceDate}}",
                            "attLeaveType" : "{{$att->leaveType->leaveType}}",
                            "tableClass" : "{!! $att->leaveType->isPaid ? 'table-success' : 'table-danger' !!}"
                        },
                    @endforeach
                    ]
                },
            @endforeach
        };

        $(function () {
            jQuery('table.attendance tbody tr').each(function(idx,elem) {
                var thisTR = jQuery(elem);
                var rowID = thisTR.attr('id');
                var staffAtt = staffAttendance[rowID].attendance;
                if (staffAtt.length) {
                    staffAtt.forEach(function(v,i) {
                        var colID = jQuery('td#'+rowID+'-'+v.attDate);
                        colID.removeClass().addClass(v.tableClass);
                        colID.text(v.attLeaveType);
                    });
                }
            });
        });
    </script>
@stop
