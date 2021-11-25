@extends('adminlte::page')

@section('title','Bank Account')

@section('content_header')
    <h1>Bank Account</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-university"></i> Bank Accounts
			</h3>
			@can('bank_account_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('bankAccount.create') }}">
				<i class="fas fa-plus-circle"></i> Add Bank Account
			</a>
			@endcan
		</div>
		<div class="card-body">
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-bankAccount">
				<thead>
					<tr>
						<th>Bank</th>
						<th>Account Title</th>
						<th>Account Number</th>
						<th>Date Created</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('plugins.Datatables', true)
@section('js')
    <script>
        $(function () {
            let dtOverrideGlobals = {
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('bankAccount.index') }}",
                columns: [
                    { data: 'bank', name: 'bank' },
                    { data: 'accountTitle', name: 'accountTitle' },
                    { data: 'accountNumber', name: 'accountNumber' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 0, 'asc' ]],
                pageLength: 100,
            };

            $('.datatable-bankAccount').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
