<div id="so-row" style="display:none;">
    <div class="form-group">
        <div class="col-sm-12">
            <select name="productID[]" class="form-control" onChange="productChanged(this);" required>
                <option value=""></option>
                @foreach ($products as $product)
                    <option value="{{$product->productID}}">{{$product->productName}} ( {{$product->categoryName}} )</option>
                @endforeach
            </select>
        </div>
    </div>
    <span class="separator"></span>
    <div class="form-group">
        <div class="col-sm-12 text-center">
			<input type="hidden" name="unitsAvailable[]" value="" min="1" />
			<input type="hidden" name="unitsInProduct[]" value="0" min="1" />
            <span class="totalUnitsAvailable"></span>
        </div>
    </div>
	<span class="separator"></span>
    <div class="form-group">
        <div class="col-sm-12">
            <input type="number" name="quantity[]" value="1" class="form-control" min="1" placeholder="Quantity" required>
        </div>
    </div>
    <span class="separator"></span>
    <button class="btn btn-danger btn-sm float-right removeSORow" type="button" title="Delete Sales Item">
        <i class="nav-icon fas fa-fw fa-trash"></i>
    </button>
</div>
