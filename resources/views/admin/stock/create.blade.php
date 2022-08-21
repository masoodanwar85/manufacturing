@extends('adminlte::page')

@section('title', 'Opening Stock')

@section('content_header')
    <h1>Opening Stock</h1>
@stop

@section('content')
	<div class="card card-default color-palette-box">
		<div class="card-header">
			<h3 class="card-title">
				<i class="fas fa-cart-plus"></i> Opening Stock
			</h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" action="{{ route('stock.store') }}" method="POST">
				@csrf
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h3>Products</h3>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<table class="table" id="myTable">
									<thead>
										<tr class="text-center">
											<th style="text-align:center;width:55%;">Product Name</th>
											<th style="text-align:center;width:10%;">Godown</th>
											<th style="text-align:center;width:10%;">Quantity</th>
											<th style="text-align:center;width:10%;">Units</th>
											<th style="text-align:center;width:10%;">Per Unit Price</th>
											<th style="text-align:center;width:10%;">Update Price?</th>
											<th style="text-align:center;width:5%;">Action</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
									<tfoot>
										<tr id="actionRow">
											<td colspan="{{ $globalSettings['client_settings.is_units_in_product_fixed'] == 1 ? '5' : '6' }}"></td>
											<td>
												<button class="btn btn-primary btn-sm pull-right " onclick="addProductRow()" type="button" title="Add New Product Item">
													<i class="nav-icon fas fa-fw fa-plus"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<hr>
				</div>

				<div>
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </form>
        </div>
    </div>

	<div id="product-row" style="display:none;">
    	<div class="form-group">
  			<div class="col-sm-12">
  				<select name="productID[]" class="form-control" onChange="productChanged(this);" required>
                    <option value=""></option>
                    @foreach ($products as $product)
  					    <option value="{{$product->productID}}" unitPurchasePrice="{{$product->unitPurchasePrice}}" unitsInProduct="{{$product->unitsInProduct}}" isUnitsInProductFixed="{{$product->isUnitsInProductFixed}}">{{$product->productName}} ( {{$product->category->categoryName}} )</option>
				    @endforeach
  				</select>
  			</div>
    	</div>
		<span class="separator"></span>
		<div class="form-group">
  			<div class="col-sm-12">
  				<select name="godownID[]" class="form-control" required>
                    <option value=""></option>
                    @foreach ($godowns as $godown)
  					    <option value="{{$godown->godownID}}">{{$godown->name}}</option>
				    @endforeach
  				</select>
  			</div>
    	</div>
    	<span class="separator"></span>
    	<div class="form-group">
      		<div class="col-sm-12">
    		    <input type="number" name="quantity[]" value="1" class="form-control" min="1" placeholder="Quantity" required>
				<input type="hidden" name="prevQty[]" value="0" />
      		</div>
    	</div>
		<span class="separator"></span>
    	<div class="form-group">
      		<div class="col-sm-12">
				<input type="number" name="quantityUnits[]" value="1" class="form-control" min="1" readonly required>
			</div>
    	</div>
		<span class="separator"></span>
        <div class="form-group">
            <div class="col-sm-12">
        		<input type="text" name="salePrice[]" value="" class="form-control" min="1" placeholder="Unit Price" required>
        	</div>
        </div>
		<span class="separator"></span>
        <div class="form-group">
            <div class="col-sm-12">
				<input type="hidden" name="isUpdateProductPrice[]" value="0" disabled />
        		<input type="checkbox" onchange="inputChanged(this);" name="isUpdateProductPrice[]" checked value="1" class="form-control" />
        	</div>
        </div>
		<span class="separator"></span>
      	<button class="btn btn-danger btn-sm pull-right removeProductRow" type="button" title="Delete Product Item">
            <i class="nav-icon fas fa-fw fa-trash"></i>
        </button>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('plugins.Select2', true)

@section('js')
	<script type="text/javascript">
		$(function() {
			addProductRow();
			$('select.select2').select2();
		});

		function bindQuantityChanged() {
	        $('input[name="quantity[]"]').bind('keydown mouseup keypress blur keyup change', function(e) {
				calculateProductRowTotal(e.target);
	        });
	    }

		function productChanged(selectProduct) {
	        calculateProductRowTotal(selectProduct,true);
	    }

		function calculateProductRowTotal(elem, isProductSelect = false) {
			var jQElem = $(elem);
			var trElem = jQElem.parent().parent().parent().parent();
			var areAllValuesFilled = true;
			var productID = 0;
			var areUnitsFixed = 1;
			var quantity = 0;
			var unitsInProduct = 0;
			var perUnitPrice = 0;
			var totalUnits = 0;
			var prevQty = 0;

			if (!isNaN(parseInt(trElem.find('select[name="productID[]"]').val()))) {
				productID = trElem.find('select[name="productID[]"]').val();
				areUnitsFixed = parseInt(trElem.find('select[name="productID[]"]').find(':selected')[0].attributes.isUnitsInProductFixed.nodeValue);
				unitsInProduct = parseInt(trElem.find('select[name="productID[]"]').find(':selected')[0].attributes.unitsInProduct.nodeValue);
				perUnitPrice = trElem.find('select[name="productID[]"]').find(':selected')[0].attributes.unitPurchasePrice.nodeValue;
			}

			@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
				trElem.find('input[name="quantityUnits[]"]').attr('readonly',true);
				if (areUnitsFixed == 0) {
					trElem.find('input[name="quantityUnits[]"]').removeAttr('readonly');
				}
			@endif

			if (!isNaN(parseInt(trElem.find('input[name="quantity[]"]').val()))) {
				quantity = parseInt(trElem.find('input[name="quantity[]"]').val());
			}

			if (productID != 0 && quantity != 0) {
				@if ($globalSettings['client_settings.is_units_in_product_fixed'] == 0)
					prevQty = parseInt(trElem.find('input[name="prevQty[]"]').val());
					if (!isProductSelect && prevQty == quantity) {
						totalUnits = parseInt(trElem.find('input[name="quantityUnits[]"]').val());
					} else {
						totalUnits = quantity * unitsInProduct;
						prevQty = quantity;
					}
				@else
					totalUnits = quantity * unitsInProduct;
				@endif
			}

			trElem.find('input[name="quantityUnits[]"]').val(totalUnits);
			trElem.find('input[name="perUnitPrice[]"]').val(parseFloat(perUnitPrice).toFixed({{\Config::get('constants.client_settings.decimal_places')}}));
			trElem.find('input[name="prevQty[]"]').val(prevQty);
		}

		function bindCheckboxChanged() {
			$('input[name="isUpdateProductPrice[]"]').bind('change', function() {
				var isChecked = $(this).is(':checked');
				if (isChecked == false) {
					$(this).prev('input').removeAttr('disabled');
				} else {
					$(this).prev('input').attr('disabled',true);
				}
			});
		}

		function bindRemoveClick() {
			$('button.removeProductRow').bind('click', function() {
				$(this).parent().parent().remove();
			});
		}

		function addProductRow() {
			var strProductRowHTML = $('#product-row').html();
			//strPORowHTML = strPORowHTML.replace(/_ctr/g,'_'+poDetailCounter);
			strProductRowHTML = strProductRowHTML.replace(/<span class="separator"><\/span>/g,'</td><td>');
			$('table#myTable tbody').append('<tr><td>' + strProductRowHTML + '</td></tr>');
			bindRemoveClick();
			bindCheckboxChanged();
			bindQuantityChanged();
			var isSelect2Implemented = false;
			$('select[name="productID[]"]').map(function(){
				if(!$(this).hasClass('select2-hidden-accessible') && !isSelect2Implemented) {
					$(this).select2();
					isSelect2Implemented = true;
				}
			});
			$('select.select2').select2({ width: 'resolve' });
		}
	</script>
@stop
