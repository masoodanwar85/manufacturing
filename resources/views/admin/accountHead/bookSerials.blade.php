@extends('adminlte::page')

@section('title', 'Book Serials Voids')

@section('content_header')
    <h1>Book Serials Voids</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-hand-holding-usd"></i> Book Serials Voids
			</h3>
			@can('account_head_create')
			<a class="btn btn-primary btn-sm float-right" style="margin-right:10px;" href="{{ route('accountHead.formBookSerial',1) }}">
				<i class="fas fa-plus-circle"></i> Add Void Book Serial
			</a>
			@endcan

		</div>
		<div class="card-body">
			<table class="table table-sm table-hover">
                <tr>
                    <th>#</th>
                    <th>Book Type &amp; Number</th>
                    <th>Serial Numbers &amp Reasons</th>
                    <th>Action</th>
                </tr>
                @if (count($bookSerials))
                    @foreach ($bookSerials as $bookSerial)
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $bookSerial->bookType }}-{{ $bookSerial->bookNumber }}</td>
							<td>{{ $bookSerial->serialNumbers }}</td>
							<td>
								<a href="{{ route('accountHead.formBookSerial', 0) }}/?bookType={{$bookSerial->bookType}}&amp;bookNumber={{$bookSerial->bookNumber}}">Edit</a>
							</td>
						</tr>
                    @endforeach
                @else
                    <tr><td colspan="4">No Record Found.</td></tr>
                @endif
            </table>
        </div>
    </div>
@endsection
@section('js')
	<script>

    </script>
@stop
