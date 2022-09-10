<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="soembprintentrytabs">
 <div title="Reference Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Reference List'" style="padding:2px">
    <table id="soembprintentryTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'company_name'" width="80">Company Name</th>
       <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
       <th data-options="field:'prod_date'" width="100">Prod. Date</th>
       <th data-options="field:'buyer_name'" width="100">Costomer</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Scring Printing Reference',footer:'#ft2'"
    style="width: 350px; padding:2px">
    <form id="soembprintentryFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Shift Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Prod. Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="prod_date" id="prod_date" class="datepicker" placeholder="Date"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Costomer</div>
                                    <div class="col-sm-8">
                                       {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                      </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" ></textarea>
                                    </div>
                                </div>    
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintEntry.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembprintentryFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintEntry.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!-----===============  =============----------->
 <div title="Production Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Production List'" style="padding:2px">
    <table id="soembprintentorderTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'sales_order_no'" width="100">Order No</th>
       <th data-options="field:'gmtspart'" width="100">Body Part</th>
       <th data-options="field:'item_desc'" width="100">GMT Part</th>
       <th data-options="field:'gmt_color'" width="100">GMT Color</th>
       <th data-options="field:'design_no'" width="100">Design No</th>
       <th data-options="field:'prod_source_id'" width="100">Prod.Source</th>
       <th data-options="field:'prod_hour'" width="100">Prod.Hour</th>
       <th data-options="field:'qty'" width="90">Qty</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Production',footer:'#ft4'" style="width: 350px; padding:2px">
    <form id="soembprintentorderFrm">
     <div id="container">
      <div id="body">
       <code>
                                    <div class="row middle" style="display:none">
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="so_emb_print_entry_id" id="so_emb_print_entry_id" value="" />
                                    </div>
                                    <div class="row middle">
                                      <div class="col-sm-4">Prod Source</div>
                                      <div class="col-sm-8">
                                       {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                                      </div>
                                     </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Order No</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="sales_order_no" id="sales_order_no" value="" onclick="
                                            MsSoEmbPrintEntOrder.openCutPanelOrderWindow()" placeholder="Click" readonly />
                                            <input type="hidden" name="so_emb_cutpanel_rcv_qty_id" id="so_emb_cutpanel_rcv_qty_id" value="" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Body Part</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="gmtspart" id="gmtspart" placeholder="dispaly" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">GMT Item</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="item_desc" id="item_desc" placeholder="dispaly" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">GMT Color</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="gmt_color" id="gmt_color" placeholder="dispaly" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Design No</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="design_no" id="design_no" placeholder="dispaly" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                     <div class="col-sm-4">Machine No</div>
                                     <div class="col-sm-8">
                                      <input type="text" name="asset_no" id="asset_no" ondblclick="MsSoEmbPrintEntOrder.openmachinWindow()" placeholder="Browse"/>
                                      <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id">
                                     </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Prod Hour</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="prod_hour" id="prod_hour" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Qty</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="qty" id="qty" class="number integer"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Remarks</div>
                                        <div class="col-sm-8">
                                            <textarea name="remarks" id="remarks"></textarea>
                                        </div>
                                    </div>
                                </code>
      </div>
     </div>
     <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintEntOrder.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembprintentorderFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintEntOrder.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
{{-- Order window --}}
<div id="opensoembprintwindow" class="easyui-window" title="Sales Order No Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="opencutpanelordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsSoEmbPrintEntOrder.searchCutpanelOrderGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="opencutpanelordersearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'sales_order_no'" width="100">Sale Order No</th>
      <th data-options="field:'gmtspart'" width="100">Body Part</th>
      <th data-options="field:'item_desc'" width="100">GMT Item</th>
      <th data-options="field:'gmt_color'" width="100">GMT Color</th>
      <th data-options="field:'design_no'" width="100">Design No</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#opensoembprintwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>


{{-- Machian Window --}}
<div id="openmachinWindow" class="easyui-window" title="Machine Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
        <form id="machinnosearchFrm">
            <div class="row middle">
             <div class="col-sm-4">Asset No</div>
             <div class="col-sm-8">
              <input type="text" name="asset_no" id="asset_no" />
             </div>
            </div>
            <div class="row middle">
             <div class="col-sm-4">Custom No</div>
             <div class="col-sm-8">
              <input type="text" name="custom_no" id="custom_no" />
             </div>
            </div>
            <div class="row middle">
             <div class="col-sm-4">Asset Name</div>
             <div class="col-sm-8">
              <input type="text" name="asset_name" id="asset_name" />
             </div>
            </div>
        </form>
      </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsSoEmbPrintEntOrder.searchMachineGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="machinnosearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'custom_no'" width="100">Custom Asset No</th>
      <th data-options="field:'asset_no'" width="100">Asset No</th>
      <th data-options="field:'asset_name'" width="100">Asset Name</th>
      <th data-options="field:'origin'" width="100">Origin</th>
      <th data-options="field:'origin_cost'" width="100">Origin Cost</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'company_id'" width="100">Company</th>
      <th data-options="field:'location_id'" width="100">Location</th>
      <th data-options="field:'type_id'" width="100">Asset Type</th>
      <th data-options="field:'production_area_id'" width="100">Production Area</th>
      <th data-options="field:'asset_group'" width="100">Group</th>
      <th data-options="field:'supplier_id'" width="100">Supplier</th>
      <th data-options="field:'salvage_value'" width="100">Salvage Value</th>
      <th data-options="field:'iregular_supplier'" width="100">Irragular Supplier</th>
      <th data-options="field:'prod_capacity'" width="100">Prod Capacity</th>
      <th data-options="field:'purchase_date'" width="100">Purchase Date</th>
      <th data-options="field:'depreciation_method_id'" width="100">Dep. Method</th>
      <th data-options="field:'depreciation_rate'" width="100">Dep. Rate</th>
      <th data-options="field:'accumulated_dep'" width="100">Acummulated Dep</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openmachinWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

<script type="text/javascript"
 src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsAllSoEmbPrintEntryController.js"></script>

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
         changeYear: true
      });

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });

      // $('#soembprintentryFrm [id="buyer_id"]').combobox();

   })(jQuery);

$(function() {
$('#prod_hour').timepicker(
{
'minTime': '12:00pm',
'maxTime': '11:00am',
'showDuration': false,
'step':60,
'scrollDefault': 'now',
'change': function(){
    alert('m')
}
}
);
});

</script>