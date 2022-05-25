@section('plugins.Select2', true)

<script type="text/javascript">
	{{ \App\Services\CurrencyService::strJSConvertToPKR() }}

	$(function() {
		bindQuantityChanged();
	});

	var productsInfo = {
        @foreach ($BOMProducts as $product)
			"{{$product->productID}}" : {
	            "productName" : "{{ $product->productName }}",
	            "items" : [
					@foreach ($product->BOM->items as $BOMProductItem)
						{
							"productID" : "{{ $BOMProductItem->productID }}",
							"productName" : "{{ $BOMProductItem->product->productName }}",
							"quantity" : "{{ $BOMProductItem->quantity }}",
							"price" : "{{ round($BOMProductItem->product->unitPurchasePrice,2) }}"
						},
					@endforeach
				],
				"expenses" : [
					@foreach ($product->BOM->expenses as $BOMProductExpenses)
						{
							"headID" : "{{ $BOMProductExpenses->expenseHeadID }}",
							"headName" : "{{ $BOMProductExpenses->head->headName }}",
							"price" : "{{ round($BOMProductExpenses->amount,2) }}"
						},
					@endforeach
				]
	        },
        @endforeach
    };

	function BOMProductChanged(productID) {
		var strItemsHTML = "";
		var strExpensesHTML = "";
		productsInfo[productID].items.forEach(function(val,idx) {
			strItemsHTML += "<tr>";
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12">'+val.productName+'</div><input type="hidden" name="productItemID[]" value="'+val.productID+'" /></div></td>';
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="productItemQuantity[]" value="'+val.quantity+'" /></div></div></td>';
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="productItemPrice[]" value="'+val.price+'" /></div></div></td>';
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12">'+(val.price * val.quantity)+'</div></div></td>';
			strItemsHTML += "</tr>";
		});
		$('#product-bom-items').html(strItemsHTML);

		productsInfo[productID].expenses.forEach(function(val,idx) {
			strExpensesHTML += "<tr>";
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12">'+val.headName+'</div><input type="hidden" name="expenseHeadID[]" value="'+val.headID+'" /></div></td>';
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" value="1" name="expenseQuantity[]" /></div></div></td>';
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="productItemPrice[]" value="'+val.price+'" /></div></div></td>';
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12">'+val.price+'</div></div></td>';
			strExpensesHTML += "</tr>";
		});
		$('#product-bom-expenses').html(strExpensesHTML);

	}

	function bindQuantityChanged() {
        $('input[name="quantity"]').bind('keydown mouseup keypress blur keyup change', function(e) {
			calculateProductRowTotal();
        });
    }

	function calculateProductRowTotal() {
		var quantity = $('input[name="quantity"]').val();
		var itemsTotal = expensesTotal = 0;

		var bomItemRows = $('#product-bom-items tr');
		var bomExpenseRows = $('#product-bom-expenses tr');

		bomItemRows.each(function(idx) {
			$(this).find('td:not(:first)').each(function() {
				if (elem = $(this).find('input')) {
					console.log((typeof elem) === undefined);
					console.log($(elem).val());
				}
			});
		});

		// if (!isNaN(parseInt(trElem.find('select[name="productID[]"]').val()))) {
		// 	productID = trElem.find('select[name="productID[]"]').val();
		// }
		//
		// if (isNaN(parseInt(trElem.find('input[name="quantity[]"]').val()))) {
		// 	areAllValuesFilled = false;
		// } else {
		// 	quantity = parseInt(trElem.find('input[name="quantity[]"]').val());
		// }
		// if (isNaN(parseFloat(trElem.find('input[name="perUnitPrice[]"]').val()))) {
		// 	areAllValuesFilled = false;
		// }
		//
		// if (isNaN(quantity)) {
		// 	totalUnits = 0;
		// } else {
		// 	totalUnits = quantity;
		// }
		//
		// if (areAllValuesFilled === true) {
		// 	perUnitPrice = parseFloat(trElem.find('input[name="perUnitPrice[]"]').val());
		// 	total = totalUnits * perUnitPrice;
		// } else {
		// 	total = 0;
		// }
		//
		// trElem.find('input[name="totalUnits[]"]').val(totalUnits);
		// trElem.find('input[name="total[]"]').val(total);
		// calculateGrandTotal();
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
