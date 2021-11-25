@extends('adminlte::page')

@section('title', 'Product Category')

@section('content_header')
    <h1>Product Category</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user"></i> {{ $category->categoryName }}
            </h3>
            @can('product_category_update')
            <a class="btn btn-primary btn-sm float-right" href="{{route('category.edit',$category->categoryID)}}">
                <i class="fas fa-edit"></i> Edit Category
            </a>
            @endcan
        </div>
        <div class="card-body">
			<dt>
				<dd class="font-weight-bold">Category: </dd>
				<dl>{{ $category->categoryName }}</dl>
				<dd class="font-weight-bold">Created On: </dd>
				<dl>{{ $category->dateCreated}}</dl>
			</dt>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
