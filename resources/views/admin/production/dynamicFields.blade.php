<div id="product-row" style="display:none;">
    <div class="form-group">
        <div class="col-sm-12">
            <select name="productIDs[]" class="form-control" onchange="BOMProductChanged(this);" required>
                <option value=""></option>
                @foreach($BOMProducts as $idx => $product)
                    <option value="{{ $product->productID }}_product_unique_id">{{ $product->productName }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-12">
            <br />
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th width="50%">Item</th>
                        <th width="15%">Quantity Per Unit</th>
                        <th width="15%">Unit Price</th>
                        <th width="15%">Total</th>
                    </tr>
                </thead>
                <tbody class="product-bom-items">

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right font-weight-bold">Total:</td>
                        <td class="font-weight-bold product-bom-item-total"></td>
                    </tr>
                </tfoot>
            </table>
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th width="50%">Expense</th>
                        <th width="15%">Quantity Per Unit</th>
                        <th width="15%">Amount</th>
                        <th width="15%">Total</th>
                    </tr>
                </thead>
                <tbody class="product-bom-expenses">

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right font-weight-bold">Total:</td>
                        <td class="font-weight-bold product-bom-expense-total"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right font-weight-bold">Grand Total:</td>
                        <td class="font-weight-bold product-bom-item-expense-grand-total"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <span class="separator"></span>
    <div class="form-group">
        <div class="col-sm-12">
            <input type="number" name="quantity[]" value="1" onchange="calculateProductRowTotal();" class="form-control" min="1" placeholder="Quantity" required>
        </div>
    </div>
    <span class="separator"></span>
    <div class="form-group">
        <div class="col-sm-12">
            <input type="number" name="total[]" value="0" class="form-control" disabled>
        </div>
    </div>
    <span class="separator"></span>
    <button class="btn btn-danger btn-sm float-right removeProductRow" type="button" title="Delete Product">
        <i class="nav-icon fas fa-fw fa-trash"></i>
    </button>
</div>
