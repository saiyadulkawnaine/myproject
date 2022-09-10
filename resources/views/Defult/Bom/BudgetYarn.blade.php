
<div class="row middle">
<div class="col-sm-4 req-text">Fabric Qty </div>
<div class="col-sm-8" align="right" style="font-weight:bold; font-size:18px">
{{ $fabricyarn->req_cons }}
</div>
</div>
<div class="row middle">
<div class="col-sm-4">Yarn: </div>
<div class="col-sm-8">
    <input type="text" name="item_description" id="item_description"  ondblclick="MsBudgetYarn.openbudgetyarnsearchWindow()" placeholder="Double Click To Search" readonly/>
    <input type="hidden" name="item_account_id" id="item_account_id" value="" readonly/>
<input type="hidden" name="id" id="id" value=""/>
<input type="hidden" name="budget_id" id="budget_id" value="{{ $fabricyarn->budget_id }}"/>
<input type="hidden" name="budget_fabric_id" id="budget_fabric_id" value="{{ $fabricyarn->id }}"/>
<input type="hidden" name="fab_cons" id="fab_cons" value="{{ $fabricyarn->req_cons }}"/>
</div>
</div>
<div class="row middle">
<div class="col-sm-4">Supplier </div>
<div class="col-sm-8">
{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
</div>
</div>
<div class="row middle">
<div class="col-sm-4 req-text">Ratio </div>
<div class="col-sm-8"><input type="text" name="ratio" id="ratio" onChange="MsBudgetYarn.claculateCons()" class="number integer"/></div>
</div>
<div class="row middle">
<div class="col-sm-4 req-text">BOM Qty </div>
<div class="col-sm-8"><input type="text" name="cons" id="cons" readonly  class="number integer"/></div>
</div>
<div class="row middle">
<div class="col-sm-4 req-text">Rate </div>
<div class="col-sm-8"><input type="text" name="rate" id="rate" onChange="MsBudgetYarn.claculateAmount()" class="number integer"/></div>
</div>
<div class="row middle">
<div class="col-sm-4">Amount </div>
<div class="col-sm-8"><input type="text" name="amount" id="amount" readonly class="number integer"/></div>
</div>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
