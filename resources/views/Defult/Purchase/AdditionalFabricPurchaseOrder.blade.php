<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="bulkfabricpurchaseTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
         <th data-options="field:'pur_order_no'" width="100">Order No</th>
        <th data-options="field:'company'" width="100">Company</th>
        <th data-options="field:'source'" width="100">Source</th>
        <th data-options="field:'delv_start_date'" width="100">Delivery Start</th>
        <th data-options="field:'delv_end_date'" width="100">Delivery End</th>
        <th data-options="field:'paymode'" width="100">Pay Mode</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Purchase Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'" style="width:350px; padding:2px">
<form id="bulkfabricpurchaseFrm">
    <div id="container">
         <div id="body">
           <code>
             <div class="row">
                <div class="col-sm-4">Order No</div>
                <div class="col-sm-8">
                <input type="text" name="pur_order_no" id="pur_order_no" class="integer number" readonly placeholder="display"/>
                </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Order Date</div>
                  <div class="col-sm-8"><input type="text" name="pur_order_date" id="pur_order_date" class="datepicker"/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Company</div>
                  <div class="col-sm-8">
                  {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                  <input type="hidden" name="id" id="id" value=""/>
                  <input type="hidden" name="order_type_id" id="order_type_id" value="{{ $order_type_id }}"/>
                  </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Source</div>
                  <div class="col-sm-8">{!! Form::select('source_id', $source,'',array('id'=>'source_id')) !!}</div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Basis</div>
                  <div class="col-sm-8">{!! Form::select('basis_id', $source,'',array('id'=>'basis_id')) !!}</div>
                </div>
                 <div class="row middle">
                 
                  <div class="col-sm-4 req-text">Supplier</div>
                  <div class="col-sm-8">{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}</div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Pay Mode</div>
                  <div class="col-sm-8">{!! Form::select('pay_mode', $paymode,'',array('id'=>'pay_mode')) !!}</div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Currency</div>
                  <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Exchange Rate  </div>
                  <div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="integer number"/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Delivery Start  </div>
                  <div class="col-sm-8"><input type="text" name="delv_start_date" id="delv_start_date" class="datepicker"/></div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Delivery End  </div>
                  <div class="col-sm-8"><input type="text" name="delv_end_date" id="delv_end_date" class="datepicker"/></div>
                </div>
                <div class="row middle">
                  
                  <div class="col-sm-4">PI No.  </div>
                  <div class="col-sm-8"><input type="text" name="pi_no" id="pi_no" value=""/></div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4">PI Date  </div>
                  <div class="col-sm-8"><input type="text" name="pi_date" id="pi_date" class="datepicker"/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Remarks  </div>
                  <div class="col-sm-8"><input type="text" name="remarks" id="remarks" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBulkFabricPurchase.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('bulkfabricpurchaseFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBulkFabricPurchase.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAdditionalFabricPurchaseController.js"></script>
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
