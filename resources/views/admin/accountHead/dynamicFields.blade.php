<div id="cheque-row" style="display:none;">
	<div class="form-group">
		<div class="col-sm-12">
			<select name="viaChequeBankAccountID[]" class="form-control">
				@foreach ($bankAccounts as $bankAccount)
				<option value="{{$bankAccount->bankAccountID}}">{{$bankAccount->bank->bankName}} - {{$bankAccount->accountTitle}} - {{$bankAccount->accountNumber}}</option>
				@endforeach
			</select>
		</div>
	</div>
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" name="chequeNo[]" value="" class="form-control" placeholder="Cheque #" required>
		</div>
	</div>
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="date" name="chequeDate[]" class="form-control" required>
		</div>
	</div>
	<span class="separator"></span>
	<div class="form-group">
		<div class="col-sm-12">
			<input type="text" onkeyUp="calculateChequesTotal();" name="chequeAmount[]" value="" class="form-control" min="1" placeholder="Cheque Amount" required>
		</div>
	</div>
	<span class="separator"></span>
	<button class="btn btn-danger btn-sm pull-right removeChequeRow" type="button" title="Delete Cheque">
		<i class="nav-icon fas fa-fw fa-trash"></i>
	</button>
</div>
