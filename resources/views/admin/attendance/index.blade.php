@extends('adminlte::page')
@section('title', 'Purchase Order')

@section('content_header')
    <h1>Purchase Order</h1>
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
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable">
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
                        <tr>
                            <td>{{ $staffAtt->staffName }}</td>
                            @foreach ($staffAtt->attendance as $att)
                                <td>{{ $att->leaveType->leaveType }}</td>
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
@stop

@section('js')
    <script>
        $(function () {

        });
    </script>
@stop
