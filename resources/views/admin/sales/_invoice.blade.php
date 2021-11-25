@extends('adminlte::page')

@section('title', 'Sales Order')

@section('content_header')
    <h1>Sales Order Invoice</h1>
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<!-- Main content -->
			<div class="invoice p-3 mb-3">
				<!-- title row -->
				@include('admin.sales.invoiceData')
				<!-- /.row -->

				<!-- this row will not appear when printing -->
				<div class="row no-print">
					<div class="col-12">
						<a href="javascript:void(0);" onclick="javascript:window.print();" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
						<a href="{{ route('sales.invoicePDF',$salesOrder->salesOrderID) }}" class="btn btn-primary float-right" style="margin-right: 5px;"><i class="fas fa-download"></i> Generate PDF </a>
					</div>
				</div>
			</div>
			<!-- /.invoice -->
		</div><!-- /.col -->
	</div><!-- /.row -->
@stop
