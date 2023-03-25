@section('plugins.Select2', true)

<script type="text/javascript">
    var finalProducts = {};
    $(function() {
        addSORow();
        bindRemoveClick();
    });
    

    var productsInfo = {
        @foreach ($products as $product)
        "{{$product->productID}}" : {
            "productName" : "{{$product->productName}}",
            "quantityAvailable" : "{{$product->quantityAvailable}}"
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

    function filterProducts() {
        finalProducts = {};
        var selectedGodownID = $('select[name="transferFrom"]').val();
        var productElem = $('select[name="productID[]"] option');
        productElem.removeAttr('disabled');
        Object.keys(godownProductsInfo).forEach((prodID,idx) => {
            var productInfo = godownProductsInfo[prodID];
            var godownInfo = productInfo.godowns;
            var res = godownInfo.filter((godown, gdIdx) => parseInt(godown.godownID) === parseInt(selectedGodownID));
            if (res.length) {
                finalProducts[prodID] = {productName: productInfo.productName, unit: productInfo.unit, unitsInProduct: productInfo.unitsInProduct, godowns: res};
            }
        });

        productElem.each(function() {
            if (Object.keys(finalProducts).indexOf($(this).val()) == -1) $(this).attr('disabled','disabled');
        });
    }

    function bindRemoveClick() {
        $('button.removeSORow').bind('click', function() {
            $(this).closest('tr').remove();
        });
    }

    function addSORow() {
        var strPORowHTML = $('#so-row').html();
        //strPORowHTML = strPORowHTML.replace(/_ctr/g,'_'+poDetailCounter);
        strPORowHTML = strPORowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
        $('table#myTable tbody').append('<tr><td>' + strPORowHTML + '</td></tr>');
        bindRemoveClick();
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
		var quantityAvailable,totalUnitsAvailableText,unitsInProduct;
		if (productID == '') {
			quantityAvailable = '';
			totalUnitsAvailableText = '';
			unitsInProduct = 0;
		} else {
			quantityAvailable = finalProducts[productID].godowns[0].quantityAvailable;
			totalUnitsAvailableText = productUnitText(productID);
			unitsInProduct = productsInfo[productID].unitsInProduct;
		}
		trElem.find('input[name="unitsInProduct[]"]').val(unitsInProduct);
		trElem.find('.totalUnitsAvailable').text(totalUnitsAvailableText);
		trElem.find('input[name="quantity[]"]').attr('max',quantityAvailable);
    }

	function productUnitText(productID,quantityAvailable = 0) {
		if (quantityAvailable == 0) {
			quantityAvailable = finalProducts[productID].godowns[0].quantityAvailable;
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
			optionsHTML += '<option value="'+godownObj.godownID+'">' + godownObj.godownName + ' (' + productUnitText(productID,godownObj.quantityAvailable) + ')' + '</option>';
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
</script>
