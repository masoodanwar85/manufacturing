@extends('adminlte::page')

@section('title', 'Bank Account')

@section('content_header')
    <h1>Bank Account</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-university"></i> Bank Account
			</h3>
			@can('bank_account_update')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('bankAccount.edit',$bankAccount->bankAccountID) }}">
				<i class="fas fa-edit"></i> Edit Bank Account
			</a>
			@endcan
		</div>
		<div class="card-body">
			<dl>
				<dt>Bank: </dt>
				<dd>{{$bankAccount->bank->bankName}}</dd>
				<dt>Account Title: </dt>
				<dd>{{$bankAccount->accountTitle}}</dd>
				<dt>Account Number: </dt>
				<dd>{{$bankAccount->accountNumber}}</dd>
				<dt>Branch Code: </dt>
				<dd>{{$bankAccount->branchCode}}</dd>
				<dt>Branch Name: </dt>
				<dd>{{$bankAccount->branchName}}</dd>
				<dt>Branch Location: </dt>
				<dd>{{$bankAccount->branchLocation}}</dd>
				<dt>Description: </dt>
				<dd>{{$bankAccount->description}}</dd>
				<dt>Date Created: </dt>
				<dd>{{$bankAccount->dateCreated}}</dd>
			</dl>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
