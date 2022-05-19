@section('plugins.Select2', true)

<script type="text/javascript">
	{{ \App\Services\CurrencyService::strJSConvertToPKR() }}

	var productsInfo = {
        @foreach ($BOMProducts as $product)
        "{{$product->productID}}" : {
            "productName" : "{{ $product->productName }}",
            "items" : {
				@foreach ($product->BOMProducts->items as $BOMProductItem)
					"productID" : "{{ $BOMProductItem->productID }}",
					"productName" : "{{ $BOMProductItem->product->productName }}",
					"quantity" : "{{ $BOMProductItem->quantity }}",
					"price" : "{{ $BOMProductItem->product->unitPurchasePrice }}"
				@endforeach
			},
			"expenses" : {
				@foreach ($product->BOMProducts->expenses as $BOMProductExpenses)
					"headID" : "{{ $BOMProductExpenses->expenseHeadID }}",
					"headName" : "{{ $BOMProductExpenses->head->headName }}",
					"price" : "{{ $BOMProductExpenses->amount }}"
				@endforeach
			}
        },
        @endforeach
    };

	function BOMProductChanged(productID) {
		$('#product-bom-items').html('');
		$('#product-bom-expenses').html('');

		var strItemsHTML = "<tr>";
		
		strItemsHTML += "</tr>";
	}

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
            var unitPrice = '';
			var productUnit = '';
            if (productID != '') {
                unitPrice = productsInfo[productID].purchasePrice;
				productUnit = productsInfo[productID].symbol;
            }
            trElem.find('input[name="perUnitPrice[]"]').val(unitPrice);
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
		// var isSelect2Implemented = false;
		// $('select[name="productID[]"]').map(function(){
		// 	if(!$(this).hasClass('select2-hidden-accessible') && !isSelect2Implemented) {
		// 		$(this).select2({ width: 'resolve' });
		// 		isSelect2Implemented = true;
		// 	}
		// });
	}

	function calculateProductRowTotal(elem) {
		var jQElem = $(elem);
		var trElem = jQElem.parent().parent().parent().parent();
		var areAllValuesFilled = true;
		var productID = 0;
		var quantity, perUnitPrice, totalInPKR, totalUnits;

		if (!isNaN(parseInt(trElem.find('select[name="productID[]"]').val()))) {
			productID = trElem.find('select[name="productID[]"]').val();
		}

		if (isNaN(parseInt(trElem.find('input[name="quantity[]"]').val()))) {
			areAllValuesFilled = false;
		} else {
			quantity = parseInt(trElem.find('input[name="quantity[]"]').val());
		}
		if (isNaN(parseFloat(trElem.find('input[name="perUnitPrice[]"]').val()))) {
			areAllValuesFilled = false;
		}

		if (isNaN(quantity)) {
			totalUnits = 0;
		} else {
			totalUnits = quantity;
		}

		if (areAllValuesFilled === true) {
			perUnitPrice = parseFloat(trElem.find('input[name="perUnitPrice[]"]').val());
			total = totalUnits * perUnitPrice;
		} else {
			total = 0;
		}

		trElem.find('input[name="totalUnits[]"]').val(totalUnits);
		trElem.find('input[name="total[]"]').val(total);
		// if (isNaN(parseInt(perUnitPrice))) {
		// 	trElem.find('div.perUnitPriceInUrdu').html('');
		// } else {
		// 	trElem.find('div.perUnitPriceInUrdu').html(translate(perUnitPrice));
		// }

		calculateGrandTotal();
	}

	function calculateGrandTotal() {
		var totalFields = $('input[name="total[]"]');
		var total = 0;
		var totalInPKR = 0;
		$(totalFields).each(function(x,y){
			if (!isNaN(parseFloat($(y).val()))) {
				total+=parseFloat($(y).val());
			}
		});

		$('#gTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;' + total);
		// $('#moneyInUrdu').html('&nbsp;&nbsp;&nbsp;&nbsp;' + translate(totalInPKR));
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

	function calculateRowPerUnitExpense(elem) {
		var jQElem = $(elem);
		var trElem = jQElem.parent().parent().parent().parent();
		var expenseAmount = trElem.find('input[name="amount[]"]').val();
		// trElem.find('div.expenseAmountInUrdu').html(translate(expenseAmount));
		calculateExpenseRowTotal(elem);
	}

	function calculateExpenseRowTotal(elem) {
		var jQElem = $(elem);
		var trElem = jQElem.parent().parent().parent().parent();
		var areAllValuesFilled = true;
		var amount;

		if (isNaN(parseFloat(trElem.find('input[name="amount[]"]').val()))) {
			areAllValuesFilled = false;
		}

		if (areAllValuesFilled === true) {
			amount = parseFloat(trElem.find('input[name="amount[]"]').val());
		} else {
			amount = 0;
		}

		calculateExpenseGrandTotal();
	}

	function calculateExpenseGrandTotal() {
		var totalInPKRFields = $('input[name="amount[]"]');
		var totalInPKR = 0;
		$(totalInPKRFields).each(function(x,y){
			if (!isNaN(parseFloat($(y).val()))) {
				totalInPKR+=parseFloat($(y).val());
			}
		});

		$('#expenseGTotalInPKR').html('&nbsp;&nbsp;&nbsp;&nbsp;Rs. ' + totalInPKR.toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
		// $('#expenseInUrdu').html('&nbsp;&nbsp;&nbsp;&nbsp;' + translate(totalInPKR));
	}
</script>
