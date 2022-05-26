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
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="hidden" class="form-control" name="baseQty[]" value="'+val.quantity+'" /><input type="number" class="form-control" name="productItemQuantity[]" value="'+val.quantity+'" /></div></div></td>';
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="productItemPrice[]" onchange="calculateProductRowTotal();" value="'+val.price+'" /></div></div></td>';
			strItemsHTML += '<td><div class="form-group"><div class="col-sm-12">'+(val.price * val.quantity)+'</div></div></td>';
			strItemsHTML += "</tr>";
		});
		$('#product-bom-items').html(strItemsHTML);

		productsInfo[productID].expenses.forEach(function(val,idx) {
			strExpensesHTML += "<tr>";
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12">'+val.headName+'</div><input type="hidden" name="expenseHeadID[]" value="'+val.headID+'" /></div></td>';
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="hidden" class="form-control" value="1" name="baseExpenseQty[]" /><input type="number" class="form-control" value="1" name="expenseQuantity[]" /></div></div></td>';
			strExpensesHTML += '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control" name="expenseAmount[]" onchange="calculateProductRowTotal();" value="'+val.price+'" /></div></div></td>';
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
			var rowTotal = 0;
			$(this).find('td:odd').each(function(index) {
				if (index == 0) {
					let baseQty = $(this).find('input[name="baseQty[]"]').val();
					rowTotal = baseQty*quantity;
					$(this).find('input[name="productItemQuantity[]"]').val(rowTotal);
				} else if (index == 1) {
					let unitPrice = $(this).prev().find('input').val();
					itemsTotal += unitPrice * rowTotal;
					$(this).find('div > div').text(unitPrice * rowTotal);
				}
			});
		});

		$('#product-bom-item-total').text(itemsTotal);

		bomExpenseRows.each(function(idx) {
			var rowTotal = 0;
			$(this).find('td:odd').each(function(index) {
				if (index == 0) {
					let baseQty = $(this).find('input[name="baseExpenseQty[]"]').val();
					rowTotal = baseQty*quantity;
					$(this).find('input[name="expenseQuantity[]"]').val(rowTotal);
				} else if (index == 1) {
					let unitPrice = $(this).prev().find('input').val();
					expensesTotal += unitPrice * rowTotal;
					$(this).find('div > div').text(unitPrice * rowTotal);
				}
			});
		});

		$('#product-bom-expense-total').text(expensesTotal);
		$('#product-bom-item-expense-grand-total').text(itemsTotal+expensesTotal);
	}
</script>
