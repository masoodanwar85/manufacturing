@section('plugins.Select2', true)

<script type="text/javascript">
    $(function() {
        $('select[name="customerID"]').focus();
        $('input.datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

		@if ($isNew == 1)
        	addSORow();
		@else
			calculateGrandTotal();
		@endif
        // $('select.select2').select2();
        bindRemoveClick();
        bindQuantityChanged();
		@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
		bindQuantityUnitChanged();
		@endif
    });


    var productsInfo = {
        @foreach ($products as $product)
        "{{$product->productID}}" : {
            "productName" : "{{$product->productName}}",
            "purchasePrice" : "@money('$product->purchasePrice','')",
            "quantityAvailable" : "{{$product->quantityAvailable}}",
			"unitsAvailable" : "{{$product->unitsAvailable}}",
			"unitsInProduct" : "{{$product->unitsInProduct}}"
        },
        @endforeach
    };

	var godownProductsInfo = {
		<?php
			$selectedProductID = 0;
		?>
		@foreach ($godownProducts as $godownProduct)
			@if ($selectedProductID == $godownProduct->productID)
				{
					"purchasePrice" : "@money('$godownProduct->purchasePrice','')",
		            "quantityAvailable" : "{{$godownProduct->quantityAvailable}}",
					"unitsAvailable" : "{{$godownProduct->unitsAvailable}}",
					"godownID" : "{{$godownProduct->godownID}}",
					"godownName" : "{{$godownProduct->godownName}}"
				},
				@if ($loop->last)
					],}
				@endif
			@else
				<?php
					$selectedProductID = $godownProduct->productID;
				?>
				@if (!$loop->first)
					],},
				@endif
				"{{$godownProduct->productID}}" : {
					"productName" : "{{$godownProduct->productName}}",
					"unit" : "{{$godownProduct->symbol}}",
					"unitsInProduct" : "{{$godownProduct->unitsInProduct}}",
					"godowns" : [{
						"purchasePrice" : "@money('$godownProduct->purchasePrice','')",
						"quantityAvailable" : "{{$godownProduct->quantityAvailable}}",
						"unitsAvailable" : "{{$godownProduct->unitsAvailable}}",
						"godownID" : "{{$godownProduct->godownID}}",
						"godownName" : "{{$godownProduct->godownName}}"
					},
				@if ($loop->last)
					],}
				@endif
			@endif
        @endforeach
	};

    function bindRemoveClick() {
        $('button.removeSORow').bind('click', function() {
            $(this).parent().parent().remove();
            calculateGrandTotal();
        });
    }

    function bindQuantityChanged() {
        $('input[name="quantity[]"]').bind('keydown mouseup keypress blur keyup change', function(e) {
            quantityChanged(e.target);
        });
    }

	@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
	function bindQuantityUnitChanged() {
        $('input[name="quantityUnits[]"]').bind('keydown mouseup keypress blur keyup change', function(e) {
			quantityUnitChanged(e.target);
        });
    }
	@endif

    function addSORow() {
        var strPORowHTML = $('#so-row').html();
        //strPORowHTML = strPORowHTML.replace(/_ctr/g,'_'+poDetailCounter);
        strPORowHTML = strPORowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
        $('table#myTable tbody').append('<tr><td>' + strPORowHTML + '</td></tr>');
        bindRemoveClick();
        bindQuantityChanged();
		@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
		bindQuantityUnitChanged();
		@endif

        // var isSelect2Implemented = false;
        // $('select[name="productID[]"]').map(function(){
        //     if(!$(this).hasClass('select2-hidden-accessible') && !isSelect2Implemented) {
        //         $(this).select2();
        //         isSelect2Implemented = true;
        //     }
        // });
    }

    function calculateProductRowTotal(elem) {
        var jQElem = $(elem);
        var trElem = jQElem.parent().parent().parent().parent();
        var areAllValuesFilled = true;
        var quantity,quantityUnits,purchasePrice,total,salePrice,unitsInProduct;

        if (isNaN(parseInt(trElem.find('input[name="unitsInProduct[]"]').val()))) {
            areAllValuesFilled = false;
        }
		if (isNaN(parseInt(trElem.find('input[name="quantity[]"]').val()))) {
            areAllValuesFilled = false;
        }
		if (isNaN(parseInt(trElem.find('input[name="quantityUnits[]"]').val()))) {
            areAllValuesFilled = false;
        }
        if (isNaN(parseFloat(trElem.find('input[name="salePrice[]"]').val()))) {
            areAllValuesFilled = false;
        }
        if (isNaN(parseFloat(trElem.find('input[name="purchasePrice[]"]').val()))) {
            areAllValuesFilled = false;
        }

        if (areAllValuesFilled === true) {
            unitsInProduct = parseInt(trElem.find('input[name="unitsInProduct[]"]').val());
			quantity = parseInt(trElem.find('input[name="quantity[]"]').val());
			quantityUnits = parseInt(trElem.find('input[name="quantityUnits[]"]').val());
            purchasePrice = parseFloat(trElem.find('input[name="purchasePrice[]"]').val().replace(',',''));
            salePrice = parseFloat(trElem.find('input[name="salePrice[]"]').val().replace(',',''));
            total = salePrice * quantityUnits;
        } else {
            total = 0;
        }

        trElem.find('input[name="total[]"]').val(total);

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var totalFields = $('input[name="total[]"]');
		var shippingCharges = $('input[name="shippingCharges"]').val();
        if (isNaN(parseInt(shippingCharges))) {
            shippingCharges = 0;
        }
		var discount = $('input[name="discount"]').val();
		$('#shippingChargesTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;' + shippingCharges);
		$('#discountTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;' + discount);
        var total = 0;
		var subTotal = 0;
        $(totalFields).each(function(x,y) {
            if (!isNaN(parseFloat($(y).val().replace(',','')))) {
                subTotal+=parseFloat($(y).val().replace(',',''));
            }
        });

		total = parseFloat(subTotal) + parseInt(shippingCharges) - parseInt(discount);
		$('#gSubTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;' + subTotal.toFixed(2));
		$('#gTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;' + total.toFixed(2));
		@if ($isNew == 1)
        	$('#amountPaid').val(0);
		@else
			total-=$('#amountPaid').val();
		@endif
        $('#balance').html('&nbsp;&nbsp;&nbsp;&nbsp;'+total.toFixed(2));
		$('#balanceInUrdu').html('&nbsp;&nbsp;&nbsp;&nbsp;' + translate(total));
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
		var trElem = jQElem.parent().parent().parent().parent();
		var unitsAvailable, quantityAvailable, purchasePrice,godownHTML,totalUnitsAvailableText,unitsInProduct;
		if (productID == '') {
			unitsAvailable = '';
			quantityAvailable = '';
			purchasePrice = '';
			godownHTML = '';
			totalUnitsAvailableText = '';
			unitsInProduct = 0;
		} else {
			purchasePrice = productsInfo[productID].purchasePrice;
			quantityAvailable = productsInfo[productID].quantityAvailable;
			unitsAvailable = productsInfo[productID].unitsAvailable;
			godownHTML = makeProductGodownsDD(productID);
			totalUnitsAvailableText = productUnitText(productID);
			unitsInProduct = productsInfo[productID].unitsInProduct;
		}
		trElem.find('.godown').html(godownHTML);
		trElem.find('input[name="unitsAvailable[]"]').val(unitsAvailable);
		trElem.find('input[name="unitsInProduct[]"]').val(unitsInProduct);
		trElem.find('.totalUnitsAvailable').text(totalUnitsAvailableText);
		trElem.find('input[name="quantity[]"]').attr('max',quantityAvailable);
		trElem.find('input[name="quantityUnits[]"]').attr('max',unitsAvailable);
		trElem.find('input[name="purchasePrice[]"]').val(purchasePrice);
		trElem.find('input[name="salePrice[]"]').val(purchasePrice);
		updateQtyUnits(selectProduct);
		// calculateProductRowTotal(selectProduct);

        // if (checkProductSelected(productID)) {
		//
        // }
    }

	function productUnitText(productID,unitsAvailable = 0,quantityAvailable = 0) {
		if (unitsAvailable == 0) {
			quantityAvailable = productsInfo[productID].quantityAvailable;
			unitsAvailable = productsInfo[productID].unitsAvailable;
		}
		var totalUnitsAvailableText = quantityAvailable;
		var totalUnitsAvailable = unitsAvailable;
		if (godownProductsInfo[productID].unitsInProduct > 1) {
			totalUnitsAvailableText += " Qty -- " + totalUnitsAvailable;
			if (godownProductsInfo[productID].unit == 'Qty') {
				totalUnitsAvailableText += " Items";
			} else {
				totalUnitsAvailableText += " " + godownProductsInfo[productID].unit;
			}
		}
		return totalUnitsAvailableText;
	}

	function formCheck() {
		// $('#frmSales');
		// alert('Still working on it... Please check back later.');
		// return false;
		// TODO: Check if the quantities of the products are correct.
		return true;
	}

	function makeProductGodownsDD(productID) {
		var optionsHTML = "";
		var godownsArray = godownProductsInfo[productID].godowns;
		godownsArray.forEach(function(godownObj) {
			optionsHTML += '<option value="'+godownObj.godownID+'">' + godownObj.godownName + ' (' + productUnitText(productID,godownObj.unitsAvailable,godownObj.quantityAvailable) + ')' + '</option>';
		});
		return '<select name="godownID[]" class="form-control"><option value=""></option>' + optionsHTML + '</select>';
	}

    function quantityChanged(quantityField) {
        var jQElem = $(quantityField);
		var trElem = jQElem.parent().parent().parent().parent();
        var qty = jQElem.val();
        var maxQty = jQElem.attr('max');
        if (parseInt(qty) > parseInt(maxQty)) {
            alert('You have ' + maxQty + ' available units.');
            jQElem.val(maxQty);
        }
		updateQtyUnits(quantityField);
    }

	function updateQtyUnits(field) {
		var jQElem = $(field);
		var trElem = jQElem.parent().parent().parent().parent();
		var productID = trElem.find('select[name="productID[]"]').val();
		var quantity = trElem.find('input[name="quantity[]"]').val();
		var prevQty = trElem.find('input[name="prevQty[]"]').val();
		if (quantity != prevQty) {
			trElem.find('input[name="prevQty[]"]').val(quantity);
			var quantityUnits = 0;
			if (productID != '') {
				quantityUnits = parseInt(productsInfo[productID].unitsInProduct) * parseInt(quantity);
			}
			trElem.find('input[name="quantityUnits[]"]').val(quantityUnits);
			calculateProductRowTotal(field);
		}
	}

	@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
	function quantityUnitChanged(quantityField) {
        calculateProductRowTotal(quantityField);
    }
	@endif

    function calculateBalance() {
        var amountPaid = $('#amountPaid').val();
        var gTotal = parseFloat($('#gTotal').text());
        var balance = 0;
        if (amountPaid != '' && isNaN(amountPaid)) {
            alert('Invalid value in paid amount');
            $('#amountPaid').val(gTotal);
        } else {
            amountPaid = parseFloat(amountPaid);
            if (isNaN(amountPaid)) {
                amountPaid = 0;
            }
            balance = gTotal - amountPaid;
        }
        $('#balance').html('&nbsp;&nbsp;&nbsp;&nbsp;' + balance.toFixed(2));
		$('#balanceInUrdu').html('&nbsp;&nbsp;&nbsp;&nbsp;' + translate(balance));
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
</script>
