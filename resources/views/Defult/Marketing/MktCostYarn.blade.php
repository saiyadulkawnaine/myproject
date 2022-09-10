
<div class="row middle">
<div class="col-sm-4 req-text">Fabric Cons  </div>
<div class="col-sm-8" align="right" style="font-weight:bold; font-size:18px">
{{ $fabricyarn->req_cons }}
</div>
</div>
<div class="row middle">
<div class="col-sm-4">Yarn: </div>
<div class="col-sm-8">
<input type="text" name="item_description" id="item_description"  ondblclick="MsMktCostYarn.openyarnsearchWindow()" placeholder="Double Click To Search" readonly/>
<input type="hidden" name="item_account_id" id="item_account_id" value="" readonly/>
<input type="hidden" name="id" id="id" value=""readonly/>
<input type="hidden" name="mkt_cost_id" id="mkt_cost_id" value="{{ $fabricyarn->mkt_cost_id }}"readonly/>
<input type="hidden" name="mkt_cost_fabric_id" id="mkt_cost_fabric_id" value="{{ $fabricyarn->id }}"readonly/>
<input type="hidden" name="fab_cons" id="fab_cons" value="{{ $fabricyarn->req_cons }}"readonly/>
</div>
</div>
<div class="row middle">
<div class="col-sm-4 req-text">Ratio  </div>
<div class="col-sm-8"><input type="text" name="ratio" id="ratio" onChange="MsMktCostYarn.claculateCons()" class="number integer"/></div>
</div>
<div class="row middle">
<div class="col-sm-4 req-text">Cons  </div>
<div class="col-sm-8"><input type="text" name="cons" id="cons" readonly  class="number integer"/></div>
</div>
<div class="row middle">
<div class="col-sm-4 req-text">Rate  </div>
<div class="col-sm-8"><input type="text" name="rate" id="rate" onChange="MsMktCostYarn.claculateAmount()" class="number integer"/></div>
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
