@extends('adminlte::page')

@section('title', 'Account Head')

@section('content_header')
    <h1>Account Head</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-hand-holding-usd"></i> {{ $accountHead->headName }}
            </h3>
			@if ($accountHead->isEditable == 1)
	            @can('account_head_update')
		            <a class="btn btn-primary btn-sm float-right" href="{{route('accountHead.edit',$accountHead->headID)}}">
		                <i class="fas fa-edit"></i> Edit Account Head
		            </a>
	            @endcan
			@endif
        </div>
        <div class="card-body">
			<dt>
				<dd class="font-weight-bold">Head Name: </dd>
				<dl>{{ $accountHead->headName }}</dl>
				<dd class="font-weight-bold">Parent Head: </dd>
				<dl>
					@if ($accountHead->parentHeadID != null)
						{{ $accountHead->parentHead->headName }}
					@else
						None
					@endif
				</dl>
                <dd class="font-weight-bold">Root Head: </dd>
				<dl>
					@if ($accountHead->rootHeadID != null)
						{{ $accountHead->rootHead->headName }}
					@else
						None
					@endif
				</dl>
				<dd class="font-weight-bold">Created On: </dd>
				<dl>{{ $accountHead->dateCreated}}</dl>
			</dt>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
