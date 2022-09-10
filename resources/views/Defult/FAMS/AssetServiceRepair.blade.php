<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="assetservicerepairTabs" title="Asset services">
 <div title="Service Entry" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="assetservicerepairTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'custom_no'" width="100">Asset No</th>
       <th data-options="field:'out_date'" width="100">Out Date</th>
       <th data-options="field:'returnable_date'" width="100">Returnable Date</th>
       <th data-options="field:'supplier_name'" width="100">Supplier</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>

   </div>
   <div
    data-options="region:'west',border:true,title:'Add New Service Info',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
    style="width:400px; padding:2px">
    <div id="container">
        <div id="body">
            <code>
                <form id="assetservicerepairFrm">
                    <div class="row middle">
                        <div class="col-sm-5">Asset Breakdown Id</div>
                        <div class="col-sm-7">
                            <input type="hidden" name="custom_no" id="custom_no"placeholder="Browse" value="">
                            <input type="hidden" id="id" name="id">
                            <input type="text" name="asset_breakdown_id" id="asset_breakdown_id"  ondblclick="MsAssetServiceRepair.openAssetBreakDownWindow()" readonly />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Out Date</div>
                        <div class="col-sm-7">
                           <input type="text" name="out_date" id="out_date" class="datepicker">
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Returnable Date</div>
                        <div class="col-sm-7">
                           <input type="text" name="returnable_date" id="returnable_date" class="datepicker">
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text areaCon">Sent To</div>
                        <div class="col-sm-7">
                          {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Remark</div>
                        <div class="col-sm-7">
                            <textarea name="remarks" id="remarks"></textarea>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Reason</div>
                        <div class="col-sm-7">
                            <input type="text" name="reason_id" id="reason_id" placeholder="display" disabled>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Asset Name</div>
                        <div class="col-sm-7">
                            <input type="text" name="asset_name" id="asset_name" placeholder="display" disabled/>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Asset Type</div>
                        <div class="col-sm-7">
                           <input type="text" name="type_id" id="type_id" placeholder="display" disabled>
                        </div>
                    </div>
                    <div class="row middle">
                     <div class="col-sm-5">Category</div>
                     <div class="col-sm-7">
                      <input type="text" name="production_area_id" id="production_area_id" placeholder="display" disabled>
                     </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Group</div>
                        <div class="col-sm-7">
                            <input type="text" name="asset_group" id="asset_group" placeholder="display" disabled>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Brand</div>
                        <div class="col-sm-7">
                            <input type="text" name="brand" id="brand" placeholder="display" disabled>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Action</div>
                        <div class="col-sm-7">
                            <input type="text" name="action_taken" id="action_taken" placeholder="display" disabled>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Purchase Date</div>
                        <div class="col-sm-7">
                            <input type="text" name="purchase_date" id="purchase_date" placeholder="display" disabled>
                        </div>
                    </div>
                </form>
            </code>
        </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsAssetServiceRepair.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetservicerepairFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetServiceRepair.remove()">Delete</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetServiceRepair.getPdf()">PDF</a>
    </div>
   </div>
  </div>
 </div>

 <div title="Service Parts Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="assetservicerepairpartTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'itemcategory'" width="100">Item Category</th>
       <th data-options="field:'qty'" width="80">Quantity</th>
       <th data-options="field:'remarks'" width="80">remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Recovery',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'"
    style="width:400px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
             <form id="assetservicerepairpartFrm">
                 <div class="row" style="display: none">
                     <input type="hidden" name="id" id="id" value="" />
                     <input type="hidden" name="asset_service_repair_id" id="asset_service_repair_id" value="">
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4">Parts/Asset</div>
                     <div class="col-sm-8">
                        <input type="text" name="itemcategories_name" id="itemcategories_name" ondblclick="MsAssetServiceRepairPart.openAssetServicePart()" placeholder="Browser">
                         <input type="hidden" name="item_account_id" id="item_account_id" />
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4">Quantity</div>
                     <div class="col-sm-8">
                         <input type="text" name="qty" id="qty" class="integer number"/>
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4">Remark</div>
                     <div class="col-sm-8">
                        <textarea name="remarks" id="remarks"></textarea>
                     </div>
                 </div>
             </form>
         </code>
     </div>
    </div>
    <div id="ft4" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsAssetServiceRepairPart.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetservicerepairpartFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetServiceRepairPart.remove()">Delete</a>
    </div>
   </div>
  </div>
 </div>
</div>

{{-- Fixed Asset Search Window --}}
<div id="openassetbreakdownwindow" class="easyui-window" title="Asset Breakdown Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#assetdtlft'" style="padding:2px">
   <table id="assetbreakdownsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'custom_no'" width="80">Custom <br />Asset No</th>
      <th data-options="field:'asset_name'" width="250">Asset Name</th>
      <th data-options="field:'prod_capacity'" width="70">Capacity</th>
      <th data-options="field:'breakdown_date'" width="80">Breakdown<br /> Date</th>
      <th data-options="field:'breakdown_time'" width="80">Breakdown<br /> Time</th>
      <th data-options="field:'reason_id'" width="100">Reason</th>
      <th data-options="field:'decision_id'" width="130">Decision</th>
      <th data-options="field:'production_area_id'" width="150">Production Area</th>
      <th data-options="field:'brand'" width="150">Brand</th>
      <th data-options="field:'asset_group'" width="150">Group</th>
      <th data-options="field:'action_taken'" width="150">Action</th>
      <th data-options="field:'remarks'" width="150">Remarks</th>
     </tr>
    </thead>
   </table>
   <div id="assetdtlft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#openassetbreakdownwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#assetFrmft'" style="padding:2px; width:350px">
   <form id="assetbreakdownsearchFrm">
    <div id="container">
     <div id="body">
      <code>
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
                        </code>
     </div>
    </div>
    <div id="assetFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsAssetServiceRepair.searchAssetBreakdown()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>
{{-- General item Search window  --}}
<div id="openassetservicerepairpartwindow" class="easyui-window" title="Asset Breakdown Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#assetservicerepairpartft'" style="padding:2px">
   <table id="assetservicerepairpartsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'itemcategory'" width="100">Item Category</th>
      <th data-options="field:'itemclass'" width="100">Item Class</th>
      <th data-options="field:'sub_class_name'" width="100">Sub Class Name</th>
      <th data-options="field:'item_description'" width="120">Item Description</th>
      <th data-options="field:'specification'" width="100">Specification</th>
      <th data-options="field:'uom'" width="100">Uom code</th>
     </tr>
    </thead>
   </table>
   <div id="assetservicerepairpartft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#openassetservicerepairpartwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#assetservicerepairpartFrmft'" style="padding:2px; width:350px">
   <form id="assetservicerepairpartsearchFrm">
    <div id="container">
     <div id="body">
      <code>
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
                        </code>
     </div>
    </div>
    <div id="assetservicerepairpartFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsAssetServiceRepairPart.searchAssetServicePart()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/FAMS/MsAllAssetServiceRepairController.js"></script>
<script>
 (function(){
    $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
    });
    $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
    });
    $('#assetservicerepairFrm [id="supplier_id"]').combobox();

})(jQuery);
</script>