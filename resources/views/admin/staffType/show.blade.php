@extends('adminlte::page')

@section('title', 'Staff Type')

@section('content_header')
    <h1>Staff Type</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users-cog"></i> {{ $staffType->staffType }}
            </h3>
            @can('staff_update')
            <a class="btn btn-primary btn-sm float-right" href="{{route('staffType.edit',$staffType->staffTypeID)}}">
                <i class="fas fa-edit"></i> Edit Staff Type
            </a>
            @endcan
        </div>
        <div class="card-body">
			<dt>
				<dd class="font-weight-bold">Staff Type: </dd>
				<dl>{{ $staffType->staffType }}</dl>
				<dd class="font-weight-bold">Created On: </dd>
				<dl>{{ $staffType->dateCreated}}</dl>
			</dt>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
