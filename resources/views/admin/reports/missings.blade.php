@extends('adminlte::page')

@section('title', 'Missing Report')

@section('content_header')
    <h1>Missing Report</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i> Missing Report
            </h3>
        </div>
        <div class="card-body">
			<table class="table table-hover table-sm">
                <thead>
                    <tr class="table-info">
                        <th width="3%">#</th>
                        <th width="8%">Book #</th>
						<th>Missing Serials</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($missings_grouped as $key => $missing)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $key }}</td>
							<td>
								@foreach ($missing as $missed)
                                    <span class="badge badge-info">{{$missed->sequenceNumber}}</span>
                                @endforeach
							</td>
						</tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
