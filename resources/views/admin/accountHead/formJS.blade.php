@section('plugins.Select2', true)

<script type="text/javascript">
	$(function(){
		$('select.select2').select2();

		$('input[name="paymentVia[]"]').bind('change',function(event) {
			$checkedCheckboxes = $('input[name="paymentVia[]"]:checked');
			$('.viaCashDiv, .viaBankDiv, .viaStaffDiv').hide();
			$('.viaCashDiv input, .viaBankDiv select, .viaBankDiv input, .viaStaffDiv select, .viaStaffDiv input').removeAttr('required');
			if ($checkedCheckboxes.length) {
				var isChequeChecked = false;
				$checkedCheckboxes.each(function(idx,val) {
					var checkedValue = $(val).val();
					switch (checkedValue) {
						case 'cash':
							$('.viaCashDiv').show();
							$('.viaCashDiv input').attr('required','true');
							break;
						case 'cheque':
							if ($('.viaChequeDiv').is(':visible')) {
								isChequeChecked = true;
							} else {
								addChequeRow();
								$('.viaChequeDiv').show();
								isChequeChecked = true;
							}
							break;
						case 'bank':
							$('.viaBankDiv').show();
							$('.viaBankDiv select, .viaBankDiv input').attr('required','true');
							break;
						case 'staff':
							$('.viaStaffDiv').show();
							$('.viaStaffDiv select, .viaStaffDiv input').attr('required','true');
							break;
						default:
							//console.log(val);
					}
				});
				if (isChequeChecked == false) {
					$('.viaChequeDiv table tbody').html('');
					$('.viaChequeDiv').hide();
				}
				$('select.select2').select2({ width: 'resolve' });
			} else {
				alert('At least one payment method should be selected!');
				$('input[name="paymentVia[]"]')[0].checked = true;
				$('.viaCashDiv').show();
			}
		});
	});


	function changePaymentForField(value) {
		$('.supplierDiv select, .customerDiv select, .expenseDiv select, .staffDiv select, .bankDiv select, .incomeDiv select, .godownDiv select, .transportDiv select').removeAttr('required');
		$('.supplierDiv, .customerDiv, .expenseDiv, .staffDiv, .bankDiv, .incomeDiv, .godownDiv, .transportDiv').hide();
		@if($isPayment == true)
		$('input#paymentViaStaff').removeAttr('disabled');
		@else
		$('input#paymentViaCheque').removeAttr('disabled');
		@endif
		switch (value) {
			case 'supplier':
				$('.supplierDiv').show();
				$('.supplierDiv select').attr('required',true);
				break;
			case 'customer':
				$('.customerDiv').show();
				$('.customerDiv select').attr('required',true);
				break;
			case 'expense':
				$('.expenseDiv').show();
				$('.expenseDiv select').attr('required',true);
				break;
			case 'godown':
				$('.godownDiv').show();
				$('.godownDiv select').attr('required',true);
				break;
			case 'transport':
				$('.transportDiv').show();
				$('.transportDiv select').attr('required',true);
				break;
			case 'staff':
			case 'salaries':
				$('.staffDiv').show();
				$('.staffDiv select').attr('required',true);
				@if($isPayment == true)
				$('input#paymentViaStaff').attr('disabled',true);
				@endif
				break;
			case 'bank':
				$('.bankDiv').show();
				$('.bankDiv select').attr('required',true);
				@if($isPayment == false)
				$('input#paymentViaCheque').attr('disabled',true);
				@endif
				break;
			case 'income':
				$('.incomeDiv').show();
				$('.incomeDiv select').attr('required',true);
				break;
			default:
				alert('Invalid Value');
		}
		$('select.select2').select2({ width: 'resolve' });
	}

	function bindRemoveClick() {
		$('button.removeChequeRow').bind('click', function() {
			$(this).parent().parent().remove();
			calculateChequeTotal();
		});
	}

	function addChequeRow() {
		var strChequeRowHTML = $('#cheque-row').html();
		strChequeRowHTML = strChequeRowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
		$('table#myTable tbody').append('<tr><td>' + strChequeRowHTML + '</td></tr>');
		bindRemoveClick();
	}

	function calculateChequesTotal() {
		var total = 0;
		$('input[name="chequeAmount[]"]').each(function(idx,elem){
			if (!isNaN(parseInt($(elem).val()))) {
				total+=parseInt($(elem).val());
				$('#chequeTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;' + total);
			}
		});
	}

	function getSupplierBalance(supplierID) {
		$('#supplierBalance').text('');
		if (!isNaN(parseInt(supplierID))) {
			//{{ route("customer.balance",1) }}
			$.ajax({
				url: `/admin/supplier/${supplierID}/balance`,
				success: function(returnedBalance) {
					$('#supplierBalance').text('Balance: ' + parseFloat(returnedBalance[0].totalPayable * -1).toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
				}
			});
		}
	}

	function getCustomerBalance(customerID) {
		$('#customerBalance').text('');
		if (!isNaN(parseInt(customerID))) {
			//{{ route("customer.balance",1) }}
			$.ajax({
				url: `/admin/customer/${customerID}/balance`,
				success: function(returnedBalance) {
					$('#customerBalance').text('Balance: ' + parseFloat(returnedBalance[0].totalPayable).toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
				}
			});
		}
	}

	function getBankAccountBalance(bankAccountID) {
		$('#bankAccountBalance').text('Balance: 0.00');
	}

	function getStaffBalance(staffID) {
		$('#staffBalance').text('');
		if (!isNaN(parseInt(staffID))) {
			//{{ route("customer.balance",1) }}
			$.ajax({
				url: `/admin/staff/${staffID}/balance`,
				success: function(returnedBalance) {
					$('#staffBalance').text('Balance: ' + parseFloat(returnedBalance[0].totalPayable).toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
				}
			});
		}
	}

	function getGodownBalance(godownID) {
		$('#godownBalance').text('');
		if (!isNaN(parseInt(godownID))) {
			//{{ route("customer.balance",1) }}
			$.ajax({
				url: `/admin/godown/${godownID}/balanceByHead`,
				success: function(returnedBalance) {
					$('#godownBalance').text('Balance: ' + parseFloat(returnedBalance[0].totalPayable).toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
				}
			});
		}
	}

	function getTransportBalance(transportID) {
		$('#transportBalance').text('');
		if (!isNaN(parseInt(transportID))) {
			//{{ route("customer.balance",1) }}
			$.ajax({
				url: `/admin/transport/${transportID}/balanceByHead`,
				success: function(returnedBalance) {
					$('#transportBalance').text('Balance: ' + parseFloat(returnedBalance[0].totalPayable).toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
				}
			});
		}
	}
</script>
