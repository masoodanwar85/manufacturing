@extends('adminlte::page')

@section('title','Staff')

@section('content_header')
    <h1>Staff</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-users"></i> Staff
			</h3>
			@can('staff_create')
			<a class="btn btn-primary btn-sm float-right" href="{{ route('staff.create') }}">
				<i class="fas fa-plus-circle"></i> Add Staff
			</a>
			@endcan
		</div>
		<div class="card-body">
            <form id="filterForm">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="inputTransactionTypeNumber">Status</label>
                        <select name="isActive" id="isActive" class="form-control">
                            <option value="">All</option>
                            <option value="1" @if( $filters['isActive'] == 1 ) selected @endif>Active</option>
                            <option value="0" @if( filled($filters['isActive']) && $filters['isActive'] == 0 ) selected @endif>In-Active</option>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <button style="margin-top:30px;" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
			<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-staff">
				<thead>
					<tr>
						<th>Name</th>
						<th>Staff Type</th>
						<th>Date Joined</th>
						<th>Salary</th>
                        <th>Balance</th>
                        <th>Status</th>
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
                ajax: {
                    url: "{{ route('staff.index') }}",
                    data: function (d) {
                        console.log(d);
                        d.isActive = $('#isActive').val();
                    },
                },
                columns: [
                    { data: 'staffName', name: 'staffName' },
                    { data: 'staffType', name: 'staffType' },
                    { data: 'dateJoined', name: 'dateJoined' },
					{ data: 'salary', name: 'salary' },
                    { data: 'balance', name: 'balance' },
                    { data: 'isActive', name: 'isActive' },
                    { data: 'dateCreated', name: 'dateCreated' },
                    { data: 'actions', name: 'Actions' }
                ],
                order: [[ 1, 'desc' ]],
                pageLength: 100,
            };

            $('.datatable-staff').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@stop
