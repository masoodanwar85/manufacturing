@extends('adminlte::page')

@section('title', 'Opening Balance')

@section('content_header')
    <h1>Opening Balance</h1>
@stop

@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-balance-scale"></i> Opening Balance
                <span style="font-weight:bold;color:red;position: absolute;right: 20px;top: 13px;">Note: When having Payables then make entry in negative amount</span>
            </h3>
        </div>
        <div class="card-body">
			<form class="form-horizontal" action="{{ route('accountHead.updateOpeningBalance') }}" method="POST">
				@csrf
				<table class="table" id="myTable">
					<thead>
						<tr class="text-center">
							<th style="text-align:center;width:65%;">Heads</th>
                            <th style="text-align:center;width:15%;">Date</th>
							<th style="text-align:center;width:15%;">Opening Balance</th>
							<th style="text-align:center;width:5%;">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($openingBalances as $openingBalance)
							<tr>
								<td>
									{{ $openingBalance->transaction->transactionDetails[0]->subHead->headName }} <br />
									---------------------> {{ $openingBalance->transaction->transactionDetails[1]->head->headName }}
								</td>
                                <td>
                                    {{ $openingBalance->transaction->transactionDate }}
                                </td>
								<td>
									Rs. {{ explode('.',$openingBalance->transaction->transactionDetails[0]->amount)[0] }}
									<input type="hidden" name="openingBalanceIDs[]" value="{{$openingBalance->openingBalanceID}}" />
								</td>
								<td>
									<button class="btn btn-danger btn-sm pull-right removeOBRow" type="button" title="Delete Item">
										<i class="nav-icon fas fa-fw fa-trash"></i>
									</button>
								</td>
							</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr id="actionRow">
							<td colspan="3"><span style="font-weight:bold;color:red;float:right;">Note: When having Payables then make entry in negative amount</span></td>
							<td>
								<button class="btn btn-primary btn-sm pull-right " onclick="addOBRow()" type="button" title="Add New Item">
									<i class="nav-icon fas fa-fw fa-plus"></i>
								</button>
							</td>
						</tr>
					</tfoot>
				</table>
				<div>
					<input class="btn btn-primary" type="submit" value="Save">
				</div>
			</form>
        </div>
    </div>
	<div id="ob-row" style="display:none;">
		<div class="form-group">
			<div class="col-sm-12">
				@include('partials.accountHeadsDropdown',['accountHeads' => $openingBalanceHeads,'name' => 'headID[]', 'value' => 0,'isRequired' => true,'additionalClass' => 'select2'])
			</div>
		</div>
		<span class="separator"></span>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="date" name="transactionDate[]" value="{{ date('Y-m-d') }}" class="form-control" required>
			</div>
		</div>
        <span class="separator"></span>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="number" name="amount[]" value="0" class="form-control" placeholder="Opening Balance" required>
			</div>
		</div>
		<span class="separator"></span>
		<button class="btn btn-danger btn-sm pull-right removeOBRow" type="button" title="Delete Item">
			<i class="nav-icon fas fa-fw fa-trash"></i>
		</button>
	</div>
@stop

@section('plugins.Select2', true)

@section('js')
	<script type="text/javascript">
		$(function() {
			addOBRow();
			bindRemoveClick();
		});

		function bindRemoveClick() {
			$('button.removeOBRow').bind('click', function() {
				$(this).parent().parent().remove();
			});
		}

		function addOBRow() {
			var strOBRowHTML = $('#ob-row').html();
			strOBRowHTML = strOBRowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
			$('table#myTable tbody').append('<tr><td>' + strOBRowHTML + '</td></tr>');
			bindRemoveClick();
			var isSelect2Implemented = false;
			$('select[name="headID[]"]').map(function(){
				if(!$(this).hasClass('select2-hidden-accessible') && !isSelect2Implemented) {
					$(this).select2({ width: 'resolve' });
					isSelect2Implemented = true;
				}
			});
		}
	</script>
@stop
