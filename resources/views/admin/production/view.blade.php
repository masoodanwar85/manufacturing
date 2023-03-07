@extends('adminlte::page')

@section('title', 'Product')

@section('content_header')
    <h1>Production</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-list"></i> {{ $production->serial }}
			</h3>
			@can('product_update')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('production.edit',$production->productionID) }}">
				<i class="fas fa-edit"></i> Edit Production
			</a>
			@endcan
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="font-weight-bold">Product:</div>
				</div>
				<div class="col">
					<div class="font-weight-bold">Date Created:</div>
					{{ $production->dateCreated }}
				</div>
			</div>
        </div>
    </div>
@stop
