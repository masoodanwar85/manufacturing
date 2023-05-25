@section('plugins.Select2', true)

<script type="text/javascript">
    $(function() {
        $('#bookType').change(function() {
            var bookType = $(this).val();
			getNextSerial(bookType);
        });
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
        $('select.select2').select2();
        bindRemoveClick();
        bindQuantityChanged();
        bindGodownChanged();
    });

	function getNextSerial(bookType) {
		$.ajax({
			url: `/admin/invoiceBooks/${bookType}/nextSerial`,
			success: function(returned) {
				$('#reason').text('');
				if (returned == '') {
					$('#bookSerial').removeAttr('readonly');
					$('#invoiceBookNum').val();
					$('#invoiceBookNumber').val();
				} else {
					$('#bookSerial').attr('readonly',true);
					$('#bookSerial').val(returned);
					$('#invoiceBookNum').val(returned);
					$('#invoiceBookNumber').val(returned);
				}
			}
		});
	}

    function voidThisSerial() {
        var bookSerialNumber = $('#invoiceBookNumber').val();
		var reason = $('#reason').val();
		if (bookSerialNumber.length && reason.length) {
			var bookType = bookSerialNumber.substring(0,2);
			$.ajax({
				url: '{{ route('invoiceBooks.voidSerial') }}',
				type: 'POST',
				data: $('#frmVoidSerial').serialize(),
				success: function(returned) {
					console.log(returned);
					getNextSerial(bookType);
				}
			});
		} else {
			alert('Invalid Book Serial to Void or Reason required');
		}
    }

    var productsInfo = {
        @foreach ($products as $product)
        "{{$product->productID}}" : {
            "productName" : "{{$product->productName}}",
            "purchasePrice" : "@money('$product->purchasePrice','')",
            "quantityAvailable" : "{{$product->quantityAvailable}}",
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

    function bindGodownChanged() {
        $('select[name="godownID[]"]').bind('change', function(e) {
            godownChanged(e.target);
        });
    }

	function addSORow() {
        var strPORowHTML = $('#so-row').html();
        //strPORowHTML = strPORowHTML.replace(/_ctr/g,'_'+poDetailCounter);
        strPORowHTML = strPORowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
        $('table#myTable tbody').append('<tr><td>' + strPORowHTML + '</td></tr>');
        bindRemoveClick();
        bindQuantityChanged();

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
        var trElem = jQElem.closest('tr');
        var areAllValuesFilled = true;
        var quantity,purchasePrice,total,salePrice,unitsInProduct;
        var discount = 0;

        if (isNaN(parseInt(trElem.find('input[name="unitsInProduct[]"]').val()))) {
            areAllValuesFilled = false;
        }
		if (isNaN(parseInt(trElem.find('input[name="quantity[]"]').val()))) {
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
			purchasePrice = parseFloat(trElem.find('input[name="purchasePrice[]"]').val().replaceAll(',',''));
            salePrice = parseFloat(trElem.find('input[name="salePrice[]"]').val().replaceAll(',',''));
            @if ($globalSettings['client_settings.is_show_discount_per_product'] == 1)
                discount = parseFloat(trElem.find('input[name="product_discount[]"]').val().replaceAll(',',''));
            @endif
            total = (salePrice * quantity) - (discount * quantity);
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
            if (!isNaN(parseFloat($(y).val().replaceAll(',','')))) {
                subTotal+=parseFloat($(y).val().replaceAll(',',''));
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
		var trElem = jQElem.closest('tr');
		var quantityAvailable, purchasePrice,godownHTML,totalUnitsAvailableText,unitsInProduct;
		if (productID == '') {
			quantityAvailable = '';
			purchasePrice = '';
			godownHTML = '';
			totalUnitsAvailableText = '';
			unitsInProduct = 0;
		} else {
			purchasePrice = productsInfo[productID].purchasePrice;
			quantityAvailable = productsInfo[productID].quantityAvailable;
			godownHTML = makeProductGodownsDD(productID);
			totalUnitsAvailableText = productUnitText(productID);
			unitsInProduct = productsInfo[productID].unitsInProduct;
		}
		trElem.find('.godown').html(godownHTML);
        bindGodownChanged();
        trElem.find('select[name="godownID[]"]').prop('selectedIndex',1);
        quantityAvailable = trElem.find('select[name="godownID[]"] :selected').attr('qty');
		trElem.find('input[name="unitsInProduct[]"]').val(unitsInProduct);
		trElem.find('.totalUnitsAvailable').text(totalUnitsAvailableText);
		trElem.find('input[name="quantity[]"]').attr('max',quantityAvailable);
		trElem.find('input[name="purchasePrice[]"]').val(purchasePrice);
		trElem.find('input[name="salePrice[]"]').val(purchasePrice);
		// updateQtyUnits(selectProduct);
		calculateProductRowTotal(selectProduct);

        // if (checkProductSelected(productID)) {
		//
        // }
    }

	function productUnitText(productID,quantityAvailable = 0) {
		if (quantityAvailable == 0) {
			quantityAvailable = productsInfo[productID].quantityAvailable;
		}
		var totalUnitsAvailableText = quantityAvailable;
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
			optionsHTML += '<option value="'+godownObj.godownID+'" qty="'+godownObj.quantityAvailable+'">' + godownObj.godownName + ' (' + productUnitText(productID,godownObj.quantityAvailable) + ')' + '</option>';
		});
		return '<select name="godownID[]" class="form-control"><option value=""></option>' + optionsHTML + '</select>';
	}

    function quantityChanged(quantityField) {
        var jQElem = $(quantityField);
		var trElem = jQElem.closest('tr');
        var qty = jQElem.val();
        var maxQty = jQElem.attr('max');
        if (parseInt(qty) > parseInt(maxQty)) {
            alert('You have ' + maxQty + ' available units.');
            jQElem.val(maxQty);
        }
        calculateProductRowTotal(quantityField);
    }

    function godownChanged(godownField) {
        var jQElem = $(godownField);
		var trElem = jQElem.closest('tr');
        var quantityAvailable = jQElem.find(':selected').attr('qty');
        if (quantityAvailable === undefined) {
            quantityAvailable = trElem.find('.totalUnitsAvailable').text();
        }
        trElem.find('input[name="quantity[]"]').attr('max',quantityAvailable);
    }

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

    function getCustomerData(customerID) {
        if ($('select[name="salesAgentID"]').length) {
            var salesAgentID = $('select[name="customerID"] option:selected').attr('salesagentid');
            $('select[name="salesAgentID"]').val(salesAgentID);
            $('select[name="salesAgentID"]').select2().select2('val',salesAgentID);
        }

        getCustomerBalance(customerID);
	}

	function getCustomerBalance(customerID) {
		$('#customerBalance').text('');
		if (!isNaN(parseInt(customerID))) {
			$.ajax({
				url: `/admin/customer/${customerID}/balance`,
				success: function(returnedBalance) {
					$('#customerBalance').text('Balance: ' + parseFloat(returnedBalance[0].totalPayable).toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
				}
			});
		}
	}
</script>
