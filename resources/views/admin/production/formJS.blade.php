@section('plugins.Select2', true)

<script type="text/javascript">
	{{ \App\Services\CurrencyService::strJSConvertToPKR() }}

	$(function() {
		addProductRow();
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

	function bindRemoveClick() {
		$('button.removeProductRow').bind('click', function() {
			$(this).closest('tr').remove();
			calculateProductRowTotal();
		});
	}

	function addProductRow() {
		const uniqueID = create_UUID();
		const regExp = /product_unique_id/gi;
		var strPORowHTML = $('#product-row').html();
        strPORowHTML = strPORowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
		strPORowHTML = strPORowHTML.replace(regExp,uniqueID);
        $('table#main > tbody').append('<tr><td>' + strPORowHTML + '</td></tr>');
        bindRemoveClick();
        bindQuantityChanged();
	}

	function BOMProductChanged(elem) {
		var jQElem = $(elem);
        var productValue = jQElem.find(':selected').val();
		var aryProductValue = productValue.split('_');
		var productID = aryProductValue[0];
		let uniqueID = aryProductValue[1];
		var trElem = jQElem.closest('tr');
		var strItemsHTML = "";
		var strExpensesHTML = "";
		productsInfo[productID].items.forEach(function(val,idx) {
			strItemsHTML += "<tr>";
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12">'+val.productName+'</div><input type="hidden" name="productItemIDs_'+uniqueID+'[]" value="'+val.productID+'" /></div></td>';
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" onchange="calculateProductRowTotal();" name="productItemQuantitys_'+uniqueID+'[]" value="'+val.quantity+'" /></div></div></td>';
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="productItemPrices_'+uniqueID+'[]" onchange="calculateProductRowTotal();" value="'+val.price+'" /></div></div></td>';
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12 row-total-'+uniqueID+'">'+(val.price * val.quantity)+'</div></div></td>';
			strItemsHTML += "</tr>";
		});
		trElem.find('.product-bom-items').html(strItemsHTML);

		productsInfo[productID].expenses.forEach(function(val,idx) {
			strExpensesHTML += "<tr>";
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12">'+val.headName+'</div><input type="hidden" name="expenseHeadIDs_'+uniqueID+'[]" value="'+val.headID+'" /></div></td>';
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="hidden" class="form-control" value="1" name="baseExpenseQtys_'+uniqueID+'[]" /><input type="number" class="form-control" value="1" name="expenseQuantity[]" /></div></div></td>';
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="expenseAmounts_'+uniqueID+'[]" onchange="calculateProductRowTotal();" value="'+val.price+'" /></div></div></td>';
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12">'+val.price+'</div></div></td>';
			strExpensesHTML += "</tr>";
		});
		trElem.find('.product-bom-expenses').html(strExpensesHTML);
		calculateProductRowTotal();
	}

	function bindQuantityChanged() {
        $('input[name="quantity"]').bind('keydown mouseup keypress blur keyup change', function(e) {
			calculateProductRowTotal();
        });
    }

	function calculateProductRowTotal() {
		$('select[name^="productIDs"] option:selected').each(function(idx,item) {
			let productValue = item.value;
			let productItem = $(item).closest('tr');
			let aryProduct = productValue.split('_');
			let productItemsTotal = 0;
			let productQty = 0;
			if (productValue != '') {
				let productID = aryProduct[0];
				let uuid = aryProduct[1];
				let productItemRows = $('input[name="productItemQuantitys_'+uuid+'[]"]');
				productItemRows.each(function(qtyIdx,itemQty) {
					let itemRow = $(itemQty).closest('tr');
					let qty = itemRow.find('input[name^="productItemQuantitys_"]').val();
					let price = itemRow.find('input[name^="productItemPrices_"]').val();
					let total = parseInt(qty) * parseInt(price);
					productItemsTotal+=total;
					itemRow.find('.row-total-'+uuid).text(total);
				});
				productQty = productItem.find('input[name="quantity[]"]').val();
			}
			productItem.find('input[name="total[]"]').val(productItemsTotal*productQty);
		});
	}

	// function calculateProductRowTotal() {
	// 	var quantity = $('input[name="quantity[]"]').val();
	// 	var itemsTotal = expensesTotal = 0;

	// 	var bomItemRows = $('#product-bom-items tr');
	// 	var bomExpenseRows = $('#product-bom-expenses tr');

	// 	bomItemRows.each(function(idx) {
	// 		var rowTotal = 0;
	// 		$(this).find('td:odd').each(function(index) {
	// 			if (index == 0) {
	// 				let baseQty = $(this).find('input[name="productItemQuantity[]"]').val();
	// 				rowTotal = baseQty*quantity;
	// 				// $(this).find('input[name="productItemQuantity[]"]').val(rowTotal);
	// 			} else if (index == 1) {
	// 				let unitPrice = $(this).prev().find('input').val();
	// 				itemsTotal += unitPrice * rowTotal;
	// 				$(this).find('div > div').text(unitPrice * rowTotal);
	// 			}
	// 		});
	// 	});

	// 	$('#product-bom-item-total').text(itemsTotal);

	// 	bomExpenseRows.each(function(idx) {
	// 		var rowTotal = 0;
	// 		$(this).find('td:odd').each(function(index) {
	// 			if (index == 0) {
	// 				let baseQty = $(this).find('input[name="baseExpenseQty[]"]').val();
	// 				rowTotal = baseQty*quantity;
	// 				// $(this).find('input[name="expenseQuantity[]"]').val(rowTotal);
	// 			} else if (index == 1) {
	// 				let unitPrice = $(this).prev().find('input').val();
	// 				expensesTotal += unitPrice * rowTotal;
	// 				$(this).find('div > div').text(unitPrice * rowTotal);
	// 			}
	// 		});
	// 	});

	// 	$('#product-bom-expense-total').text(expensesTotal);
	// 	$('#product-bom-item-expense-grand-total').text(itemsTotal+expensesTotal);
	// }

	function create_UUID() {
		var dt = new Date().getTime();
		var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
			var r = (dt + Math.random()*16)%16 | 0;
			dt = Math.floor(dt/16);
			return (c=='x' ? r :(r&0x3|0x8)).toString(16);
		});
		return uuid;
	}
</script>
