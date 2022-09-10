<div class="easyui-tabs" style="width:100%;height:100%; border:none">
  <div title="Basic" style="padding:1px" data-options="selected:true">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'west',split:true, title:'Knit Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px;padding:3px">

      <div id="container">
      <div id="body">
      <code>
<form id="yarndyingchargeFrm">
  <div class="row">
          <div class="col-sm-4 req-text">Company</div>
          <div class="col-sm-8">
          {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
          <input type="hidden" name="id" id="id" value=""/>
          </div>
      </div>
       <div class="row middle" style="display:none">
                <div class="col-sm-4 req-text">Fabrication </div>
                <div class="col-sm-8">{!! Form::select('autoyarn_id', $autoyarn,'',array('id'=>'autoyarn_id')) !!}</div>
            </div>
      <div class="row middle" style="display:none">
          <div class="col-sm-4 req-text">Composition </div>
          <div class="col-sm-8">{!! Form::select('composition_id', $composition,'',array('id'=>'composition_id')) !!}</div>
          
      </div>
      <div class="row middle">
      <div class="col-sm-4 req-text">Color Range </div>
          <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}</div>
      </div>
      <div class="row middle">
          <div class="col-sm-4 req-text">Yarn Count </div>
          <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id')) !!}</div>
      </div>
      <div class="row middle">
      <div class="col-sm-4">Yarn Type </div>
          <div class="col-sm-8">{!! Form::select('yarntype_id', $yarntype,'',array('id'=>'yarntype_id')) !!}</div>
      </div>
     
      <div class="row middle" style="display:none">
          <div class="col-sm-4 req-text" >Prod. Process </div>
          <div class="col-sm-8">{!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id')) !!}</div>
          
      </div>
      <div class="row middle">
      <div class="col-sm-4 req-text">Rate </div>
          <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
      </div>
      <div class="row middle">
          <div class="col-sm-4 req-text">Uom </div>
          <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
          
      </div>
       <div class="row middle">
          
          <div class="col-sm-4">Sequence  </div>
          <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" value=""/></div>
      </div>
      <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnDyingCharge.submit()">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('yarndyingchargeFrm')" >Reset</a>
          <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnDyingCharge.remove()" >Delete</a>
      </div>
      </form>
      </code>
      </div>
      </div>
     </div>
     <div data-options="region:'center',split:true, title:'List'">
       <table id="yarndyingchargeTbl" style="width:100%">
        <thead>
        <tr>
          <th data-options="field:'id'" width="80">ID</th>
          <th data-options="field:'company'" width="100">Company</th>
          <th data-options="field:'yarncount'" width="100">Yarn Count</th>
          <th data-options="field:'yarntype'" width="100">Yarn Type</th>
          
          <th data-options="field:'colorrange'" width="100">Color Range</th>
          <th data-options="field:'rate'" width="100">Rate</th>
          <th data-options="field:'uom'" width="100">Uom</th>
        </tr>
        </thead>
        </table>
     </div>
   </div>
  </div>
  <div title="Buyer Rate" style="padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'west',split:true, title:'Knit Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft3'" style="width:350px;padding:3px">

      <div id="container">
      <div id="body">
      <code>
<form id="buyeryarndyingchargeFrm">
  <div class="row">

                     <div class="col-sm-4 req-text">Buyer: </div>
                     <div class="col-sm-8">
                       {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                       <input type="hidden" name="id" id="id" value=""/>
                      <input type="hidden" name="yarn_dying_charge_id" id="yarn_dying_charge_id" value=""/>
                     </div>
                     </div>
                     <div class="row middle">
                     <div class="col-sm-4 req-text">Rate: </div>
                     <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
                 </div>

     <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerYarnDyingCharge.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('buyeryarndyingchargeFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerYarnDyingCharge.remove()" >Delete</a>
    </div>
      </form>
      </code>
      </div>
      </div>
     </div>
     <div data-options="region:'center',split:true, title:'List'">
       <table id="buyeryarndyingchargeTbl" style="width:100%">
          <thead>
              <tr>
                  <th data-options="field:'id'" width="80">ID</th>
                  <th data-options="field:'buyer'" width="100">Buyer</th>
                  <th data-options="field:'rate'" width="100">Rate</th>
             </tr>
          </thead>
          </table>
     </div>
   </div>
  </div>
  <div title="Supplier Rate" style="padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'west',split:true, title:'Knit Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'" style="width:350px;padding:3px">

      <div id="container">
      <div id="body">
      <code>
    <form id="supplieryarndyingchargeFrm">
      <div class="row">

                      <div class="col-sm-4 req-text">Supplier: </div>
                      <div class="col-sm-8">
                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                        <input type="hidden" name="yarn_dying_charge_id" id="yarn_dying_charge_id" value=""/>
                        <input type="hidden" name="id" id="id" value=""/>
                      </div>
                      </div>
                      <div class="row middle">
                      <div class="col-sm-4 req-text">Rate: </div>
                      <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
                  </div>

             <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSupplierYarnDyingCharge.submit()">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('supplieryarndyingchargeFrm')" >Reset</a>
          <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSupplierYarnDyingCharge.remove()" >Delete</a>
      </div>
      </form>
      </code>
      </div>
      </div>
     </div>
     <div data-options="region:'center',split:true, title:'List'">
       <table id="supplieryarndyingchargeTbl" style="width:100%">
          <thead>
              <tr>
                  <th data-options="field:'id'" width="80">ID</th>
                  <th data-options="field:'supplier'" width="100">Supplier</th>
                  <th data-options="field:'rate'" width="100">Rate</th>
             </tr>
          </thead>
          </table>
     </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsYarnDyingChargeController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsBuyerYarnDyingChargeController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsSupplierYarnDyingChargeController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>
