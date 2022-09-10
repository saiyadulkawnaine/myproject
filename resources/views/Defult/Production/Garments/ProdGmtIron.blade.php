<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodgmtirontabs">
 <div title="Reference Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodgmtironTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
       <th data-options="field:'iron_qc_date'" width="100">Iron QC Date</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Iron QC Entry',footer:'#ft2'"
    style="width: 350px; padding:2px">
    <form id="prodgmtironFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Shift Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Iron QC Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="iron_qc_date" id="iron_qc_date" class="datepicker" />
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
       iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtIron.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtironFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtIron.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!-----===============  =============----------->
 <div title="Order Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true" style="width:400px; padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'"
      style="height:300px; padding:2px">
      <form id="prodgmtironorderFrm">
       <div id="container">
        <div id="body">
         <code>
                                        <div class="row middle" style="display:none">
                                            <input type="hidden" name="id" id="id" value="" />
                                            <input type="hidden" name="prod_gmt_iron_id" id="prod_gmt_iron_id" value="" />
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Order No</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="sale_order_no" id="sale_order_no" value="" onclick="MsProdGmtIronOrder.openOrderIronWindow()" placeholder="  Click" readonly />
                                                <input type="hidden" name="sales_order_country_id" id="sales_order_country_id" value="" />
                                            </div>
                                        </div>
                                    
                                        {{-- <div class="row middle">
                                            <div class="col-sm-4">Line No</div>
                                            <div class="col-sm-8">  
                                                <input type="text" name="line_name" id="line_name" value="" onclick="MsProdGmtIronOrder.openLineNoWindow()" placeholder=" Click Once" />
                                                <input type="hidden" name="wstudy_line_setup_id" id="wstudy_line_setup_id" value="" />
                                            </div>
                                        </div> --}}
                                        <div class="row middle">
                                            <div class="col-sm-4">Table No</div>
                                            <div class="col-sm-8">  
                                                <input type="text" name="table_no" id="table_no" value="" onclick="MsProdGmtIronOrder.openTableNoWindow()" placeholder=" Click Once" />
                                                <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id" value="" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Prod Hour</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="prod_hour" id="prod_hour" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Prod Source</div>
                                            <div class="col-sm-8">
                                                {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                                                {{-- <input type="text" name="prod_source_id" id="prod_source_id" value="" /> --}}
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Supplier</div>
                                            <div class="col-sm-8">
                                                {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Company</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="company_id" id="company_id" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Country</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="country_id" id="country_id" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Location</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="location_name" id="location_name" value="" disabled />
                                                <input type="hidden" name="location_id" id="location_id" value="" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Prod.Company</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="produced_company_name" id="produced_company_name" value="" disabled />
                                                <input type="hidden" name="produced_company_id" id="produced_company_id" value="" disabled />                                        
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Buyer</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="buyer_name" id="buyer_name" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Job No</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="job_no" id="job_no" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Ship Date</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="ship_date" id="ship_date" value="" disabled />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Order Source</div>
                                            <div class="col-sm-8">
                                                <input type="text" name="order_source_id" id="order_source_id" value="" disabled />
                                            </div>
                                        </div>
                                    </code>
        </div>
       </div>
       <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtIronOrder.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtironorderFrm')">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtIronOrder.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="prodgmtironorderTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="40">Id</th>
         <th data-options="field:'sale_order_no'" width="100">Order No</th>
         <th data-options="field:'prod_source_id'" width="100">Prod.Source</th>
         <th data-options="field:'supplier_id'" width="100">Service Provider</th>
         <th data-options="field:'prod_hour'" width="100">Prod.Hour</th>
         <th data-options="field:'location_id'" width="90">Location</th>
         <th data-options="field:'table_no'" width="100">Table No</th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
   <div data-options="region:'center',border:true,title:'Gmt Qty',footer:'#ftironqty'" style="padding:2px">
    <form id="prodgmtironqtyFrm">
     <input type="hidden" name="id" id="id" value="" />
     <code id="irongmtcosi">
                        </code>
     <div id="ftironqty" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtIronQty.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtironqtyFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtIronQty.remove()">Delete</a>
     </div>
    </form>

   </div>
  </div>
 </div>
</div>
{{-- Order window --}}
<div id="openorderironwindow" class="easyui-window" title="Sales Order No Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="orderironsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsProdGmtIronOrder.searchIronOrderGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="orderironsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'style_ref'" width="70">Style Ref</th>
      <th data-options="field:'job_no'" width="90">Job No</th>
      <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
      <th data-options="field:'country_id'" width="100">Country</th>
      <th data-options="field:'ship_date'" width="100">Ship Date</th>
      <th data-options="field:'buyer_name'" width="100">Buyer</th>
      <th data-options="field:'company_id'" width="80">Company</th>
      <th data-options="field:'produced_company_name'" width="80">Produced Company</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openorderironwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
{{-- Line Window --}}
{{-- <div id="openlinenowindow" class="easyui-window" title="Line No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="linenosearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Location</div>
                                <div class="col-sm-8">
                                    {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtIronOrder.searchLineNoGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="linenosearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'company_name'" align="center" width="170">Company</th>
                        <th data-options="field:'line_name'" width="100">Line No</th>
                        <th data-options="field:'line_code'" width="100">Line Name</th>
                        <th data-options="field:'line_floor'" width="200">Line Floor</th>
                        <th data-options="field:'location_name'" align="center" width="100">Location</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openlinenowindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div> --}}
{{-- Asset Acquisiton / Table No --}}
<div id="opentablenowindow" class="easyui-window" title="Asset Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#tablesearchTblFt'" style="padding:2px">
   <table id="tablenosearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'custom_no'" width="100">Table No</th>
      <th data-options="field:'asset_name'" width="100">Asset Name</th>
      <th data-options="field:'company_name'" width="100">Company</th>
      <th data-options="field:'location_name'" width="100">Location</th>
      <th data-options="field:'origin'" width="100">Origin</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'asset_group'" width="100">Group</th>
      <th data-options="field:'prod_capacity'" width="100">Prod. Capacity</th>
     </tr>
    </thead>
   </table>
   <div id="tablesearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#opentablenowindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#tablesearchFrmFt'" style="padding:2px; width:350px">
   <form id="tablenosearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row ">
                                <div class="col-sm-4">Table No</div>
                                <div class="col-sm-8"> <input type="text" name="custom_no" id="custom_no" /> </div>
                            </div>
                            <div class="row middle ">
                                <div class="col-sm-4">Brand</div>
                                <div class="col-sm-8"> <input type="text" name="brand" id="brand" /> </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="tablesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsProdGmtIronOrder.searchTableNo()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Garments/MsAllIronController.js"></script>

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
      $('#prodgmtironorderFrm [id="supplier_id"]').combobox();
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