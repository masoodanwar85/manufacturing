@extends('adminlte::page')

@section('title', 'Batch')

@section('content_header')
    <h1>Batch</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-stopwatch"></i> {{ $batch->batchName }}
            </h3>
            @can('batch_update')
            <a class="btn btn-primary btn-sm float-right" href="{{ route('batch.edit',$batch->batchID) }}">
                <i class="fas fa-edit"></i> Edit Batch
            </a>
            @endcan
        </div>
        <div class="card-body">
			<dt>
				<dd class="font-weight-bold">Batch Name: </dd>
				<dl>{{ $batch->batchName }}</dl>
				<dd class="font-weight-bold">Start Date: </dd>
				<dl>{{ $batch->startDate }}</dl>
				<dd class="font-weight-bold">End Date: </dd>
				<dl>{{ $batch->endDate }}</dl>
				<dd class="font-weight-bold">Description: </dd>
				<dl>{{ $batch->description }}</dl>
				<dd class="font-weight-bold">Created On: </dd>
				<dl>{{ $batch->dateCreated}}</dl>
			</dt>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
