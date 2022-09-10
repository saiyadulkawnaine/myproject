<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="prodknittabs">
 <div title="Production ID" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List',footer:'#prodknitTblFt'" style="padding:2px">
    <table id="prodknitTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'prod_no'" width="100">GFP No</th>
       <th data-options="field:'prod_date'" width="80">Production Date</th>
       <th data-options="field:'supplier_name'" width="80">Knit Company</th>
       <th data-options="field:'challan_no'" width="80">Challan No</th>
       <th data-options="field:'shift_name'" width="80">Shift Name</th>
       <th data-options="field:'location_name'" width="80">Location</th>
      </tr>
     </thead>
    </table>
    <div id="prodknitTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     Prod Date: <input type="text" name="from_prod_date" id="from_prod_date" class="datepicker"
      style="width: 100px ;height: 23px" />
     <input type="text" name="to_prod_date" id="to_prod_date" class="datepicker" style="width: 100px;height: 23px" />
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" zdy90 onClick="MsProdKnit.search()">Show</a>
    </div>
   </div>
   <div data-options="region:'west',border:true,title:'Production Reference',footer:'#prodknitFrmft'"
    style="width: 350px; padding:2px">
    <form id="prodknitFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                  <div class="col-sm-4">GFP No</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="prod_no" id="prod_no" readonly />
                                  <input type="hidden" name="id" id="id" readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Prod Date</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="prod_date" id="prod_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Prod. Basis</div>
                                  <div class="col-sm-8">
                                  {!! Form::select('basis_id',$productionsource,'',array('id'=>'basis_id')) !!}
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Challan No</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="challan_no" id="challan_no"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Knit Company</div>
                                  <div class="col-sm-8">
                                    {!! Form::select('supplier_id',$supplier,'',array('id'=>'supplier_id')) !!}
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Location</div>
                                  <div class="col-sm-8">
                                    {!! Form::select('location_id',$location,'',array('id'=>'location_id')) !!}
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Floor</div>
                                  <div class="col-sm-8">
                                  {!! Form::select('floor_id',$floor,'',array('id'=>'floor_id')) !!}
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Shift Name</div>
                                  <div class="col-sm-8">
                                  {!! Form::select('shift_id',$shiftname,'',array('id'=>'shift_id')) !!}
                                  </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="prodknitFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdKnit.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodknitFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnit.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Fabrication" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodknititemTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'gmtspart'" width="100">Body Part</th>
       <th data-options="field:'fabrication'" width="100">Fabrication</th>
       <th data-options="field:'gsm_weight'" width="80">GSM</th>

       <th data-options="field:'dia'" width="80">Dia</th>
       <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
       <th data-options="field:'gauge'" width="100">Machine Gauge</th>
       <th data-options="field:'colorrange_name'" width="100">Color Range</th>
       <th data-options="field:'fabriccolor'" width="100">Fabric Color</th>
       <th data-options="field:'stitch_length'" width="80">Stitch Length</th>
       <th data-options="field:'pl_no'" width="80">WO/PL No</th>
       <th data-options="field:'style_ref'" width="80">Style</th>
       <th data-options="field:'order_no'" width="80">Sales Order</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Fabrication Reference',footer:'#prodknititemFrmft'"
    style="width: 350px; padding:2px">
    <form id="prodknititemFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                  <div class="col-sm-4">Fabrication</div>
                                  <div class="col-sm-8">
                                    <input type="text" name="fabrication" id="fabrication" ondblclick="MsProdKnitItem.prodknititemWindowOpen()" readonly placeholder="Dobule Click" />
                                    <input type="hidden" name="id" id="id" readonly />
                                    <input type="hidden" name="prod_knit_id" id="prod_knit_id" readonly />
                                    <input type="hidden" name="po_knit_service_item_qty_id" id="po_knit_service_item_qty_id" readonly />
                                    <input type="hidden" name="pl_knit_item_id" id="pl_knit_item_id" readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">GSM</div>
                                  <div class="col-sm-8">
                                    <input type="text" name="gsm_weight" id="gsm_weight" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Dia</div>
                                  <div class="col-sm-8">
                                    <input type="text" name="dia" id="dia" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Stitch Length</div>
                                  <div class="col-sm-8">
                                    <input type="text" name="stitch_length"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Looks </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id','style'=>'width: 100%; border-radius:2px','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Shape </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('fabric_shape_id', $fabricshape,'',array('id'=>'fabric_shape_id','style'=>'width: 100%; border-radius:2px','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Style Ref</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="style_ref" id="style_ref" disabled />
                                  </div>
                                </div>
                                 <div class="row middle">
                                  <div class="col-sm-4 req-text">Order No</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="order_no" id="order_no" disabled/>
                                  </div>
                                </div>

                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Machine No</div>
                                  <div class="col-sm-8">
                                    <input type="text" name="custom_no" id="custom_no" ondblclick="MsProdKnitItem.prodknitmachineWindowOpen()" readonly placeholder="Dobule Click"/>
                                    <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id"  readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">M/C Gauge</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="machine_gg" id="machine_gg" disabled/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">M/C Dia</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="machine_dia" id="machine_dia" disabled/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Operator</div>
                                  <div class="col-sm-8">
                                    <input type="text" name="operator_name" id="operator_name" ondblclick="MsProdKnitItem.prodknitmachineoperatorWindowOpen()" />
                                  <input type="hidden" name="operator_id" id="operator_id"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">M/C info Outside</div>
                                  <div class="col-sm-8">
                                    <textarea name="machine_info_outside" id="machine_info_outside"></textarea>
                                  </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="prodknititemFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodknititemFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitItem.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Roll" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodknititemrollTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'roll_no'" width="100">Roll No</th>
       <th data-options="field:'custom_no'" width="80">Custom No</th>
       <th data-options="field:'roll_length'" width="80">Length</th>
       <th data-options="field:'width'" width="80">Width</th>
       <th data-options="field:'measurment'" width="80">Measurment</th>
       <th data-options="field:'roll_weight'" width="80" align="right">Roll Wgt</th>
       <th data-options="field:'qty_pcs'" width="80" align="right">PCS</th>
       <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
       <th data-options="field:'gmt_sample'" width="80">Gmt Sample</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Roll Reference',footer:'#prodknititemrollFrmft'"
    style="width: 350px; padding:2px">
    <form id="prodknititemrollFrm">
     <div id="container">
      <div id="body">
       <code>
                              <div class="row">
                                <div class="col-sm-4">Fabrication</div>
                                  <div class="col-sm-8" id="fabrication_td" style="font-weight: bold;"></div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Roll Wgt</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="roll_weight" id="roll_weight" class="integer number" onclick="MsProdKnitRollItem.getWgt()"/>
                                  <input type="hidden" name="id" id="id" readonly />
                                  <input type="hidden" name="prod_knit_item_id" id="prod_knit_item_id" readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Length</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="roll_length" id="roll_length" class="integer number" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Width</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="width" id="width" class="integer number" />
                                  </div>
                                </div>
                                
                                
                                <div class="row middle">
                                  <div class="col-sm-4">Pcs</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="qty_pcs" id="qty_pcs" class="integer number"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Measurment</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="measurment" id="measurment" />
                                  </div>
                                </div>

                                <div class="row middle">
                                  <div class="col-sm-4">Roll No</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="roll_no" id="roll_no"  disabled />
                                  
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Custom No</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="custom_no" id="custom_no" disabled />
                                  </div>
                                </div>
                                
                                <div class="row middle" style="display: none">
                                  <div class="col-sm-4">Fabric Color</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="fabric_color" id="fabric_color" ondblclick="MsProdKnitRollItem.prodknitcolorWindowOpen()" placeholder="Dobule Click" disabled />
                                  <input type="hidden" name="fabric_color_id" id="fabric_color_id" disabled />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Gmt Sample</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="gmt_sample_name" id="gmt_sample_name" ondblclick="MsProdKnitRollItem.prodknitsampleWindowOpen()" placeholder="Dobule Click" readonly />
                                  <input type="hidden" name="gmt_sample" id="gmt_sample" readonly />
                                  </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="prodknititemrollFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitRollItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodknititemrollFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitRollItem.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Yarn" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodknititemyarnTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'lot'" width="100">Lot No</th>
       <th data-options="field:'yarn_count'" width="80">Yarn Count</th>
       <th data-options="field:'composition'" width="80">Composition</th>
       <th data-options="field:'yarn_type'" width="80">Type</th>
       <th data-options="field:'brand'" width="80">Brand</th>
       <th data-options="field:'supplier_name'" width="80">Supplier</th>
       <th data-options="field:'qty'" width="80" align="right">Used Qty</th>
       <th data-options="field:'rate'" width="80" align="right">Rate/KG</th>
       <th data-options="field:'amount'" width="80">Cost</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Roll Reference',footer:'#prodknititemyarnFrmft'"
    style="width: 350px; padding:2px">
    <form id="prodknititemyarnFrm">
     <div id="container">
      <div id="body">
       <code>
                              <div class="row">
                                <div class="col-sm-4">Fabrication</div>
                                  <div class="col-sm-8" id="fabrication_yarn_td" style="font-weight: bold;"></div>
                                  
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Yarn ID</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="inv_yarn_item_id" id="inv_yarn_item_id" readonly ondblclick="MsProdKnitItemYarn.prodknititemyarnWindowOpen()" />
                                  <input type="hidden" name="id" id="id" readonly />
                                  <input type="hidden" name="inv_yarn_isu_item_id" id="inv_yarn_isu_item_id" readonly />
                                  <input type="hidden" name="prod_knit_item_id" id="prod_knit_item_id" readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Lot No</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="lot" id="lot" disabled/>
                                  
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Count</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="yarn_count" id="yarn_count" disabled/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Composition</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="composition" id="composition"  disabled/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Type</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="yarn_type" id="yarn_type"  disabled/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Brand</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="brand" id="brand" disabled />
                                  </div>
                                </div>
                                
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Supplier</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="supplier_name" id="supplier_name" disabled />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Used Qty</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="qty" id="qty" class="integer number" onchange="MsProdKnitItemYarn.calculate()" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Rate/KG</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="rate" id="rate" class="integer number" readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Cost</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="amount" id="amount" class="integer number" readonly />
                                  </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="prodknititemyarnFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitItemYarn.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodknititemyarnFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitItemYarn.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>

<div id="prodknititemWindow" class="easyui-window" title="Item Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodknititemsearchTblft'" style="padding:2px">
   <table id="prodknititemsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'pl_no'" width="100">Plan No/WO No</th>
      <th data-options="field:'gmtspart'" width="100">Body Part</th>
      <th data-options="field:'fabrication'" width="300">Fabrication</th>
      <th data-options="field:'fabricshape'" width="80">Fabric Shape</th>
      <th data-options="field:'fabriclooks'" width="80">Fabric Looks</th>
      <th data-options="field:'gsm_weight'" width="80">GSM</th>
      <th data-options="field:'dia'" width="80">Dia</th>
      <th data-options="field:'measurment'" width="80">Measurement</th>
      <th data-options="field:'stitch_length'" width="80">Stitch Length</th>
      <th data-options="field:'colorrange_name'" width="100">Color Range</th>
      <th data-options="field:'fabriccolor'" width="100">Fabric Color</th>
      <th data-options="field:'style_ref'" width="80">Style</th>
      <th data-options="field:'order_no'" width="80">Sales Order</th>
     </tr>
    </thead>
   </table>
   <div id="prodknititemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodknititemWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#prodknititemsearchFrmft'" style="padding:2px; width:350px">
   <form id="prodknititemsearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row ">
                              <div class="col-sm-4 req-text">Customer</div>
                              <div class="col-sm-8"> {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                              </div>
                            </div>

                            <div class="row middle">
                              <div class="col-sm-4 req-text">Plan No</div>
                              <div class="col-sm-8">
                              <input type="text" name="pl_no" id="pl_no" />
                              </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">WO No</div>
                            <div class="col-sm-8">
                            <input type="text" name="po_no" id="po_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Dia</div>
                            <div class="col-sm-8">
                            <input type="text" name="dia" id="dia" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">GSM</div>
                            <div class="col-sm-8">
                            <input type="text" name="gsm" id="gsm" />
                            </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="prodknititemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" \
      onClick="MsProdKnitItem.searchItem()">Search</a>
    </div>
   </form>
  </div>

 </div>
</div>


<div id="prodknitmachineWindow" class="easyui-window" title="Machine Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodknitmachinesearchTblft'" style="padding:2px">
   <table id="prodknitmachinesearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'custom_no'" width="100">Machine No</th>
      <th data-options="field:'asset_name'" width="100">Asset Name</th>
      <th data-options="field:'origin'" width="100">Origin</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'prod_capacity'" width="100">Prod. Capacity</th>
      <th data-options="field:'dia_width'" width="60">Dia/Width</th>
      <th data-options="field:'gauge'" width="60">Gauge</th>
      <th data-options="field:'extra_cylinder'" width="60">Extra Cylinder</th>
      <th data-options="field:'no_of_feeder'" width="60">Feeder</th>
     </tr>
    </thead>
   </table>
   <div id="prodknitmachinesearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodknitmachineWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#prodknitmachinesearchFrmft'" style="padding:2px; width:350px">
   <form id="prodknitmachinesearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Dia/Width</div>
                            <div class="col-sm-8"> <input type="text" name="dia_width" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Feeder</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="no_of_feeder" />
                            </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="prodknitmachinesearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" \
      onClick="MsProdKnitItem.searchMachine()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<div id="prodknitmachineoperatorWindow" class="easyui-window" title="Machine Operator Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodknitmachineoperatorsearchTblft'" style="padding:2px">
   <table id="prodknitmachineoperatorsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'name'" width="100">Name</th>
     </tr>
    </thead>
   </table>
   <div id="prodknitmachineoperatorsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodknitmachineoperatorWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#prodknitmachineoperatorsearchFrmft'"
   style="padding:2px; width:350px">
   <form id="prodknitmachineoperatorsearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Name</div>
                            <div class="col-sm-8"> <input type="text" name="Name" id="Name" /> </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="prodknitmachineoperatorsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" \
      onClick="MsProdKnitItem.searchMachineOperator()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<div id="prodknitcolorWindow" class="easyui-window" title="Color Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodknitcolorsearchTblft'" style="padding:2px">
   <table id="prodknitcolorsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'name'" width="100">Name</th>
     </tr>
    </thead>
   </table>
   <div id="prodknitcolorsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodknitcolorWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>

 </div>
</div>

<div id="prodknitsampleWindow" class="easyui-window" title="Sample Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodknitsamplesearchTblft'" style="padding:2px">
   <table id="prodknitsamplesearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'name'" width="100">Name</th>
     </tr>
    </thead>
   </table>
   <div id="prodknitsamplesearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodknitsampleWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>

 </div>
</div>

<div id="prodknititemyarnWindow" class="easyui-window" title="Machine Operator Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodknititemyarnsearchTblFT'" style="padding:2px">
   <table id="prodknititemyarnsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'lot'" width="100">Lot</th>
      <th data-options="field:'yarn_count'" width="100">Count</th>
      <th data-options="field:'composition'" width="100">Composition</th>
      <th data-options="field:'yarn_type'" width="100">Type</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'supplier_name'" width="100">Supplier</th>
      <th data-options="field:'qty'" width="100" align="right">Qty</th>
      <th data-options="field:'rate'" width="100" align="right">Rate</th>
      <th data-options="field:'amount'" width="100" align="right">Amount</th>
     </tr>
    </thead>
   </table>
   <div id="prodknititemyarnsearchTblFT" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodknititemyarnWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#prodknititemyarnsearchFrmFt'" style="padding:2px; width:350px">
   <form id="prodknititemyarnsearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Lot</div>
                            <div class="col-sm-8"> <input type="text" name="lot" id="lot" /> 
                            </div>
                            </div>
                            <div class="row middle ">
                            <div class="col-sm-4 req-text">Brand</div>
                            <div class="col-sm-8"> <input type="text" name="brand" id="brand" /> 
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Supplier</div>
                            <div class="col-sm-8">
                            {!! Form::select('supplier_id',$yarnsupplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="prodknititemyarnsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsProdKnitItemYarn.getyarn()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Kniting/MsProdKnitController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Kniting/MsProdKnitItemController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Kniting/MsProdKnitItemRollController.js">
</script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Kniting/MsProdKnitItemYarnController.js">
</script>


<script>
 (function(){
      $(".datepicker").datepicker({
         beforeShow:function(input) {
               $(input).css({
                  "position": "relative",
                  "z-index": 999999
               });
         },
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true,
      });
      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) 
         {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });
      $('#prodknitFrm [id="buyer_id"]').combobox();
      $('#prodknititemyarnsearchFrm [id="supplier_id"]').combobox();
   })(jQuery);
</script>