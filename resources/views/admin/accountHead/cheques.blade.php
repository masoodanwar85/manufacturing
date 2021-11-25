@extends('adminlte::page')

@section('title', 'Cheques')

@section('content_header')
    <h1>Cheques</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-money-check"></i> Cheques
			</h3>
		</div>
		<div class="card-body">
			<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
				@foreach ($chequesStatuses as $chequeStatus)
					<li class="nav-item">
						<a class="nav-link @if ($loop->first) active @endif" id="tab-{{$chequeStatus->bankInstrumentStatusID}}" data-toggle="pill" href="#chequeStatus-{{$chequeStatus->bankInstrumentStatusID}}" role="tab" aria-controls="custom-content-below-home" aria-selected="true">{{$chequeStatus->bankInstrumentStatus}}</a>
					</li>
				@endforeach
			</ul>
			<div class="tab-content" id="custom-content-below-tabContent">
				@foreach ($chequesStatuses as $chequeStatus)
					<div class="tab-pane fade @if ($loop->first)active show @endif" id="chequeStatus-{{$chequeStatus->bankInstrumentStatusID}}" role="tabpanel" aria-labelledby="tab-{{$chequeStatus->bankInstrumentStatusID}}">
						<table class="table">
							<thead>
								<tr>
									<th>Particular</th>
									<th>Cheque #</th>
									<th>Date</th>
									<th>Amount</th>
									<th>Submitted Bank</th>
									<th>Stages</th>
									@if ($chequeStatus->bankInstrumentStatusID == $processingStatusID) <th>Action</th> @endif
								</tr>
							</thead>
							<tbody>
								@foreach ($receiptCheques as $cheque)
									@if ($chequeStatus->bankInstrumentStatusID == $cheque->bankInstrumentStages[count($cheque->bankInstrumentStages) - 1]->bankInstrumentStatus->bankInstrumentStatusID)
										<tr>
											<td>
												{{ \App\Models\TransactionDetail::where('transactionID',$cheque->transactionDetail->transactionID)->where('isDebit',0)->first()->subHead->headName }}
											</td>
											<td>{{ $cheque->instrumentNumber }}</td>
											<td>{{ $cheque->instrumentDate }}</td>
											<td>Rs.{{ $cheque->instrumentAmount }}</td>
											<td>{{ $cheque->bank->bankName }} @if (strlen($cheque->bankAccount->accountNumber)) <br />Title:{{ $cheque->bankAccount->accountTitle }}<br />Account #:{{ $cheque->bankAccount->accountNumber }} @endif</td>
											<td>
												@foreach ($cheque->bankInstrumentStages as $bankInstrumentStageInfo)
													{{ date('d-m-Y',strtotime($bankInstrumentStageInfo->dateCreated)) }}
													<span class="badge badge-success">{{ $bankInstrumentStageInfo->bankInstrumentStatus->bankInstrumentStatus }}</span><br />
												@endforeach
											</td>
											@if ($chequeStatus->bankInstrumentStatusID == $processingStatusID)
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-default">Action</button>
													<button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
														<span class="sr-only">Toggle Dropdown</span>
													</button>
													<div class="dropdown-menu" role="menu" style="">
														@foreach ($chequesStatuses as $chequeStatusNew)
															@if ($chequeStatusNew->bankInstrumentStatusID != $processingStatusID)
																<a class="dropdown-item" onClick="return confirm('Are you sure you want to change the status of this cheque?');" href="{{ route('accountHead.updateCheques', ['bankInstrumentDetailID' => $cheque->bankInstrumentDetailID, 'bankInstrumentStatusID' => $chequeStatusNew->bankInstrumentStatusID]) }}">{{ $chequeStatusNew->bankInstrumentStatus }}</a>
															@endif
														@endforeach
													</div>
												</div>
											</td>
											@endif
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				@endforeach
			</div>
        </div>
    </div>
@stop
