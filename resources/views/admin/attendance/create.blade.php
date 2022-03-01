@extends('adminlte::page')
@section('title', 'Mark Attendance')

@section('content_header')
    <h1>Mark Attendance</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-cart-plus"></i> Mark Attendance
			</h3>
		</div>
		<div class="card-body">
            <form class="form-horizontal" action="{{ route('attendance.store') }}" method="POST">
				@csrf
                <div class="form-group row">
                    <label for="attendanceDate" class="col-sm-2 col-form-label">Attendance Date: *</label>
					<div class="col-sm-5">
	                    <input type="date" name="attendanceDate" class="form-control" value="{{ $attendanceDate }}" required readonly>
					</div>
                    <div class="col-sm-5">
	                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($attendanceDate)->isoFormat('dddd, DD MMMM, GGGG') }}" disabled>
					</div>
                </div>
                <table class="table table-bordered table-striped table-hover ajaxTable attendance">
                    <thead>
                        <tr>
                            <td>Staff</td>
                            <td>Attendance</td>
                            <td>Description</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $isAlreadyExists = FALSE;
                            $ctr = -1;
                        ?>
                        @foreach ($staffAttendance as $staffAtt)
                            <tr>
                                <td>{{ $staffAtt->staffName }}</td>

                                <?php
                                    $ctr++;
                                    $thisStaffLeaveTypeID = 1;
                                    $thisStaffDescription = "";
                                    $isAlreadyExists = FALSE;
                                    if (!empty($staffAtt->attendance[0]) && $staffAtt->attendance[0]->leaveTypeID) {
                                        $thisStaffLeaveTypeID = $staffAtt->attendance[0]->leaveTypeID;
                                        $thisStaffDescription = $staffAtt->attendance[0]->description;
                                        $isAlreadyExists = TRUE;
                                    }
                                ?>
                                @if (!$isAlreadyExists || $canOverrideToday)
                                <input type="hidden" name="staffIDs[]" value="{{ $staffAtt->staffID }}" />
                                @endif
                                <td>
                                    @foreach ($leaveTypes as $leaveType)
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="staffAttendances[{{$ctr}}]" @if ($isAlreadyExists && !$canOverrideToday) disabled @endif value="{{ $leaveType->leaveTypeID }}" {!!  ($leaveType->leaveTypeID == $thisStaffLeaveTypeID ? 'checked' : '') !!}> {{ $leaveType->leaveType }}
                                            </label>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <input type="text" name="descriptions[]" class="form-control" value="{{ $thisStaffDescription }}" @if ($isAlreadyExists && !$canOverrideToday) disabled @endif />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>
                    <input class="btn btn-primary btn-block" type="submit" @if ($isAlreadyExists && !$canOverrideToday) disabled @endif value="Save Attendance">
                </div>
            </form>
        </div>
    </div>
@stop
