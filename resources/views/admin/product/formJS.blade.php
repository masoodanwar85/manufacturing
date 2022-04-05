@section('plugins.Select2', true)

<script type="text/javascript">
	{{ \App\Services\CurrencyService::strJSConvertToPKR() }}
	var productsInfo = {
        @foreach ($products as $product)
        "{{$product->productID}}" : {
            "productName" : "{{$product->productName}}",
            "purchasePrice" : "@money('$product->purchasePrice','')",
            "unitsAvailable" : "{{$product->unitsAvailable}}",
			"unitsInProduct" : "{{$product->unitsInProduct}}",
			"areUnitsFixed" : "{{$product->isUnitsInProductFixed}}",
			"symbol" : "{{ $product->maximumUnit->symbol }}"
        },
        @endforeach
    };

	$(function() {
		@if ($isNew == 1)
			addBOMRow();
			addBOMExpenseRow();
		@else
			// For Translation of money into URDU
			calculateGrandTotal();
			calculateExpenseGrandTotal();
			$('button.removeBOMRow').bind('click', function() {
				$(this).parent().parent().remove();
				calculateGrandTotal();
			});
			$('button.removeBOMExpenseRow').bind('click', function() {
				$(this).parent().parent().remove();
				calculateExpenseGrandTotal();
			});
		@endif

		bindQuantityChanged();

		$('select.select2').select2();

		$('input[name="isSupplier"]').bind('change', function(event) {
			if ($(event.target).val() == 1) {
				$('.supplierDiv').show();
				$('.customerDiv').hide();
				$('select[name="supplierID"]').attr("required","true");
				$('select[name="customerID"]').removeAttr("required");
				$('select[name="customerID"] option:eq(0)').prop("selected", true).trigger('change');
				$('select[name="supplierID"]').select2({ width: 'resolve' });
			} else {
				$('.customerDiv').show();
				$('.supplierDiv').hide();
				$('select[name="customerID"]').attr("required","true");
				$('select[name="supplierID"]').removeAttr("required");
				$('select[name="supplierID"] option:eq(0)').prop("selected", true).trigger('change');
				$('select[name="customerID"]').select2({ width: 'resolve' });
			}
		});
	});

	function bindQuantityChanged() {
        $('input[name="quantity[]"]').bind('keydown mouseup keypress blur keyup change', function(e) {
			calculateProductRowTotal(e.target);
        });
    }

	function checkProductSelected(productID) {
        var productFields = $('select[name="productID[]"]');
        var totalProductDDs = 0;
        if (productID != '') {
            $(productFields).each(function(x,elem) {
                if ($(elem).find(':selected').val() == productID) {
                    if (totalProductDDs == 1) {
                        alert('This product is already selected.');
                        $(elem).val(null).trigger('change');
                    }
                    totalProductDDs++;
                }
            });
        }
        if (totalProductDDs > 1) {
            return false;
        } else {
            return true;
        }
    }

	function productChanged(selectProduct) {
        var jQElem = $(selectProduct);
        var productID = jQElem.find(':selected').val();
        if (checkProductSelected(productID)) {
			var trElem = jQElem.parent().parent().parent().parent();
            var unitsInProduct = '';
			var productUnit = '';
            if (productID != '') {
                unitsInProduct = productsInfo[productID].unitsInProduct;
				productUnit = productsInfo[productID].symbol;
            }
            trElem.find('input[name="unitsInProduct[]"]').val(unitsInProduct);
			trElem.find('input[name="totalUnits[]"]').val(unitsInProduct);
			trElem.find('input[name="productUnit[]"]').text(productUnit+'(s)');
			calculateProductRowTotal(selectProduct);
        }
    }

	function bindRemoveClick() {
		$('button.removeBOMRow').bind('click', function() {
			$(this).parent().parent().remove();
			calculateGrandTotal();
		});
	}

	function addBOMRow() {
		var strBOMRowHTML = $('#bom-row').html();
		//strBOMRowHTML = strBOMRowHTML.replace(/_ctr/g,'_'+bomDetailCounter);
		strBOMRowHTML = strBOMRowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
		$('table#myTable tbody').append('<tr><td>' + strBOMRowHTML + '</td></tr>');
		bindRemoveClick();
		bindQuantityChanged();
		var isSelect2Implemented = false;
		$('select[name="productID[]"]').map(function(){
			if(!$(this).hasClass('select2-hidden-accessible') && !isSelect2Implemented) {
				$(this).select2();
				isSelect2Implemented = true;
			}
		});
	}

	function calculateProductRowTotal(elem) {
		var jQElem = $(elem);
		var trElem = jQElem.parent().parent().parent().parent();
		var areAllValuesFilled = true;
		var productID = 0;
		var areUnitsFixed = 0;
		var quantity, exchangeRate, unitsInProduct, perUnitPrice, total, totalInPKR, totalUnits;

		if (!isNaN(parseInt(trElem.find('select[name="productID[]"]').val()))) {
			productID = trElem.find('select[name="productID[]"]').val();
			areUnitsFixed = parseInt(productsInfo[productID].areUnitsFixed);
		}

		if (isNaN(parseInt(trElem.find('input[name="quantity[]"]').val()))) {
			areAllValuesFilled = false;
		} else {
			quantity = parseInt(trElem.find('input[name="quantity[]"]').val());
			unitsInProduct = parseInt(trElem.find('input[name="unitsInProduct[]"]').val());
		}
		if (isNaN(parseFloat(trElem.find('input[name="exchangeRate[]"]').val()))) {
			areAllValuesFilled = false;
		}
		if (isNaN(parseFloat(trElem.find('input[name="perUnitPrice[]"]').val()))) {
			areAllValuesFilled = false;
		}

		if (isNaN(quantity)) {
			totalUnits = 0;
		} else {
			totalUnits = quantity * unitsInProduct;
		}

		@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
			var prevQty = 0;
			if (!isNaN(parseInt(trElem.find('input[name="prevQty[]"]').val()))) {
				prevQty = trElem.find('input[name="prevQty[]"]').val();
			}
			if (prevQty == quantity) {
				var prevTotalUnits = trElem.find('input[name="totalUnits[]"]').val();
				if (prevTotalUnits != 0) {
					totalUnits = trElem.find('input[name="totalUnits[]"]').val();
				}
			}

			trElem.find('input[name="totalUnits[]"]').attr('readonly',true);

			if (areUnitsFixed == 0) {
				trElem.find('input[name="totalUnits[]"]').removeAttr('readonly');
			}
		@endif

		if (areAllValuesFilled === true) {
			exchangeRate = parseFloat(trElem.find('input[name="exchangeRate[]"]').val());
			perUnitPrice = parseFloat(trElem.find('input[name="perUnitPrice[]"]').val());
			total = totalUnits * perUnitPrice;
			totalInPKR = convertToPKR(exchangeRate, total);
		} else {
			total = 0;
			totalInPKR = 0;
		}

		trElem.find('input[name="totalUnits[]"]').val(totalUnits);
		trElem.find('input[name="total[]"]').val(total);
		trElem.find('input[name="totalInPKR[]"]').val(totalInPKR);
		trElem.find('input[name="prevQty[]"]').val(quantity);
		if (isNaN(parseInt(perUnitPrice))) {
			trElem.find('div.perUnitPriceInUrdu').html('');
		} else {
			trElem.find('div.perUnitPriceInUrdu').html(translate(perUnitPrice));
		}

		calculateGrandTotal();
	}

	function calculateGrandTotal() {
		var totalFields = $('input[name="total[]"]');
		var totalInPKRFields = $('input[name="totalInPKR[]"]');
		var total = 0;
		var totalInPKR = 0;
		$(totalFields).each(function(x,y){
			if (!isNaN(parseFloat($(y).val()))) {
				total+=parseFloat($(y).val());
			}
		});

		$(totalInPKRFields).each(function(x,y){
			if (!isNaN(parseFloat($(y).val()))) {
				totalInPKR+=parseFloat($(y).val());
			}
		});

		$('#gTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;' + total);
		$('#gTotalInPKR').html('&nbsp;&nbsp;&nbsp;&nbsp;Rs. ' + totalInPKR.toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
		$('#moneyInUrdu').html('&nbsp;&nbsp;&nbsp;&nbsp;' + translate(totalInPKR));
	}

	function bindExpenseRemoveClick() {
		$('button.removeBOMExpenseRow').bind('click', function() {
			$(this).parent().parent().remove();
			calculateExpenseGrandTotal();
		});
	}

	function addBOMExpenseRow() {
		var strBOMExpenseRowHTML = $('#bom-expense-row').html();
		strBOMExpenseRowHTML = strBOMExpenseRowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
		$('table#expenseTable tbody').append('<tr><td>' + strBOMExpenseRowHTML + '</td></tr>');
		bindExpenseRemoveClick();
	}

	function getTotalUnits() {
		var totalUnits = 0;
		$('input[name="totalUnits[]"]').each(function() {
			var currentUnit = parseInt(this.value);
			if (!isNaN(currentUnit)) {
				totalUnits += currentUnit;
			}
		});
		return totalUnits;
	}

	function calculateRowPerUnitExpense(elem) {
		var jQElem = $(elem);
		var trElem = jQElem.parent().parent().parent().parent();
		var totalUnits = getTotalUnits();
		var expenseAmount = trElem.find('input[name="amount[]"]').val();
		trElem.find('input[name="perUnitExpense[]"]').val(expenseAmount/totalUnits);
		trElem.find('div.expenseAmountInUrdu').html(translate(expenseAmount));
		calculateExpenseRowTotal(elem);
	}

	function calculateRowExpenseAmount(elem) {
		var jQElem = $(elem);
		var trElem = jQElem.parent().parent().parent().parent();
		var totalUnits = getTotalUnits();
		var perUnitExpense = trElem.find('input[name="perUnitExpense[]"]').val();
		trElem.find('input[name="amount[]"]').val(totalUnits*perUnitExpense);
		calculateExpenseRowTotal(elem);
	}

	function calculateExpenseRowTotal(elem) {
		var jQElem = $(elem);
		var trElem = jQElem.parent().parent().parent().parent();
		var areAllValuesFilled = true;
		var exchangeRate, amount, totalInPKR;

		if (isNaN(parseFloat(trElem.find('input[name="expenseExchangeRate[]"]').val()))) {
			areAllValuesFilled = false;
		}
		if (isNaN(parseFloat(trElem.find('input[name="amount[]"]').val()))) {
			areAllValuesFilled = false;
		}

		if (areAllValuesFilled === true) {
			exchangeRate = parseFloat(trElem.find('input[name="expenseExchangeRate[]"]').val());
			amount = parseFloat(trElem.find('input[name="amount[]"]').val());
			totalInPKR = convertToPKR(exchangeRate, amount);
		} else {
			totalInPKR = 0;
		}

		trElem.find('input[name="totalExpenseInPKR[]"]').val(totalInPKR);

		calculateExpenseGrandTotal();
	}

	function calculateExpenseGrandTotal() {
		var totalInPKRFields = $('input[name="totalExpenseInPKR[]"]');
		var totalInPKR = 0;
		$(totalInPKRFields).each(function(x,y){
			if (!isNaN(parseFloat($(y).val()))) {
				totalInPKR+=parseFloat($(y).val());
			}
		});

		$('#expenseGTotalInPKR').html('&nbsp;&nbsp;&nbsp;&nbsp;Rs. ' + totalInPKR.toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
		$('#expenseInUrdu').html('&nbsp;&nbsp;&nbsp;&nbsp;' + translate(totalInPKR));
	}

	function getSupplierBalance(supplierID) {
		$('#supplierBalance').text('');
		if (!isNaN(parseInt(supplierID))) {
			//{{ route("customer.balance",1) }}
			$.ajax({
				url: `/admin/supplier/${supplierID}/balance`,
				success: function(returnedBalance) {
					var amount = parseFloat(returnedBalance[0].totalPayable) * -1;
					$('#supplierBalance').text('Balance: ' + amount.toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
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
					var amount = parseFloat(returnedBalance[0].totalPayable) * -1;
					$('#customerBalance').text('Balance: ' + amount.toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
				}
			});
		}
	}
</script>
