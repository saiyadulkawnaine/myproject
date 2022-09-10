
  <div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Basic" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
			 <div data-options="region:'west',split:true, title:'Dying Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
<form id="dyingchargeFrm">
  <div class="row">
                <div class="col-sm-4 req-text">Company</div>
                <div class="col-sm-8">
                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                <input type="hidden" name="id" id="id" value=""/>
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Fabrication </div>
                <div class="col-sm-8">
                <input type="hidden" name="autoyarn_id" id="autoyarn_id"  readonly="readonly"/>
                <input type="text" name="fabrication" id="fabrication" ondblclick="MsDyingCharge.openFabricationWindow()" placeholder="Double Click For Search"/>

                </div>
            </div>
            
            <div class="row middle">
                <div class="col-sm-4 req-text">Color Range </div>
                <div class="col-sm-8">{!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id')) !!}</div>
                
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Dyeing Type </div>
                <div class="col-sm-8">{!! Form::select('dyeing_type_id', $dyetype,'',array('id'=>'dyeing_type_id')) !!}</div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Fabric Shape  </div>
                <div class="col-sm-8">{!! Form::select('fabric_shape_id', $fabricshape,'',array('id'=>'fabric_shape_id')) !!}</div>
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
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDyingCharge.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('dyingchargeFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDyingCharge.remove()" >Delete</a>
            </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="dyingchargeTbl" style="width:100%">
        <thead>
        <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'company'" width="100">Company</th>
            <th data-options="field:'fabrication'" width="500">Fabrication</th>
            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
            <th data-options="field:'colorrange'" width="100">Color Range</th>
            <th data-options="field:'dyeing_type'" width="100">Dyeing type</th>
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
			 <div data-options="region:'west',split:true, title:'Dying Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft3'" style="width:350px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
<form id="buyerdyingchargeFrm">
  <div class="row">
                <div class="col-sm-4 req-text">Buyer: </div>
                 <div class="col-sm-8">
                   {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                   <input type="hidden" name="dying_charge_id" id="dying_charge_id" value=""/>
                 <input type="hidden" name="id" id="id" value=""/>
                 </div>
                 </div>
                 <div class="row middle">
                 <div class="col-sm-4 req-text">Rate: </div>
                 <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>


             </div>


             <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerDyingCharge.submit()">Save</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('buyerdyingchargeFrm')" >Reset</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerDyingCharge.remove()" >Delete</a>
          </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="buyerdyingchargeTbl" style="width:100%">
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
       <div data-options="region:'west',split:true, title:'Dying Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'" style="width:350px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
      <form id="dyingchargesupplierFrm">
        <div class="row">

                    <div class="col-sm-4 req-text">Supplier: </div>
                    <div class="col-sm-8">
                      {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                      <input type="hidden" name="dying_charge_id" id="dying_charge_id" value=""/>
                      <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    </div>
                    <div class="row middle">
                    <div class="col-sm-4 req-text">Rate: </div>
                    <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
                </div>


                <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDyingChargeSupplier.submit()">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('dyingchargesupplierFrm')" >Reset</a>
          <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDyingChargeSupplier.remove()" >Delete</a>
      </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="dyingchargesupplierTbl" style="width:100%">
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
  
  <div id="dyingChargeFabricationWindow" class="easyui-window" title="Fabrications" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#stylefabricsearchTblft'" style="padding:2px">
            <table id="dyingvhargefabricsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'name'" width="100">Construction</th>
                    <th data-options="field:'composition_name'" width="300">Composition</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true,footer:'#dyingchargefabricsearchft'" style="padding:2px; width:350px">
            <form id="dyingchargefabricsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Construction</div>
                            <div class="col-sm-8"> <input type="text" name="construction_name" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Composition</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="composition_name" />
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="dyingchargefabricsearchft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsDyingCharge.searchFabric()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsDyingChargeController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsBuyerDyingChargeController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsDyingChargeSupplierController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>
