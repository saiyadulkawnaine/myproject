<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="assetreturnTabs" title="Asset return">
 <div title="Asset Repair/Service Back" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="assetreturnTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'supplier_name'" width="100">Vendor</th>
       <th data-options="field:'return_date'" width="100">Return Date</th>
       <th data-options="field:'vendor_challan'" width="100">Vendor Challan</th>
       <th data-options="field:'vendor_bill'" width="100">Vendor Bill</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>

   </div>
   <div
    data-options="region:'west',border:true,title:'Vendor Ref',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
    style="width:400px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                <form id="assetreturnFrm">
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Return Id</div>
                        <div class="col-sm-7">
                           <input type="text" name="" id="">
																											<input type="hidden" name="id" id="id">
                        </div>
                    </div>
                    <div class="row middle">
                     <div class="col-sm-5 req-text">Main Menu</div>
                     <div class="col-sm-7">
                      {!! Form::select('menu_id',$menu,'',array('id'=>'menu_id')) !!}
                     </div>
                    </div>
                    <div class="row middle">
                     <div class="col-sm-5 req-text">Vendor</div>
                     <div class="col-sm-7">
                      <input type="hidden" name="supplier_id" id="supplier_id">
                      <input type="text" name="supplier_name" id="supplier_name" ondblclick="MsAssetReturn.openVendor()" placeholder="Browse">
                     </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Return Date</div>
                        <div class="col-sm-7">
                           <input type="text" name="return_date" id="return_date" class="datepicker">
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text areaCon">Vendor Challan</div>
                        <div class="col-sm-7">
                        <input type="text" name="vendor_challan" id="vendor_challan">
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text areaCon">Vendor Bill</div>
                        <div class="col-sm-7">
                        <input type="text" name="vendor_bill" id="vendor_bill">
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Remark</div>
                        <div class="col-sm-7">
                            <textarea name="remarks" id="remarks"></textarea>
                        </div>
                    </div>
                </form>
            </code>
     </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsAssetReturn.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetreturnFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetReturn.remove()">Delete</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetReturn.getPdf()">PDF</a>
    </div>
   </div>
  </div>
 </div>
 <div title="Part/Asset Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true" style="width:400px; padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'"
      style="height:180px; padding:2px">
      <form id="assetreturndetailFrm">
       <div id="container">
        <div id="body">
         <code>
               <div class="row middle" style="display:none">
                   <input type="hidden" name="id" id="id" value="" />
                   <input type="hidden" name="asset_return_id" id="asset_return_id" value="" />
               </div>
               <div class="row middle">
                   <div class="col-sm-5 req-text">Asset Part</div>
                   <div class="col-sm-7">
                      <input type="text" name="asset_part" id="asset_part" onDblClick="MsAssetReturnDetail.openAssetPart()"
                        placeholder="Double Click" readonly />
                       <input type="hidden" name="asset_part_id" id="asset_part_id" value="" />
                   </div>
               </div>
               <div class="row middle">
                   <div class="col-sm-5 req-text">Out Date</div>
                   <div class="col-sm-7">
                       <input type="text" name="out_date" id="out_date" value="" placeholder="Display" disabled/>
                   </div>
               </div>
               <div class="row middle">
                   <div class="col-sm-5 req-text">Returnable Date</div>
                   <div class="col-sm-7">
                       <input type="text" name="returnable_date" id="returnable_date" value="" placeholder="Display" disabled/>
                   </div>
               </div>
               <div class="row middle">
                   <div class="col-sm-5 req-text">Asset Name</div>
                   <div class="col-sm-7">
                       <input type="text" name="asset_name" id="asset_name" value="" placeholder="Display" disabled/>
                   </div>
               </div>
           </code>
        </div>
       </div>
       <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsAssetReturnDetail.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetreturndetailFrm')">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetReturnDetail.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="assetreturndetailTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="30">ID</th>
         <th data-options="field:'asset_name'" width="100">Asset Name</th>
         <th data-options="field:'out_date'" width="100">Out Date</th>
         <th data-options="field:'returnable_date',align:'right'" width="100">Returnable Date
         </th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
   <div data-options="region:'center',border:true,title:'Cost Incurred'" style="padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft3'"
      style="height:150px; padding:2px">
      <form id="assetreturndetailcostFrm">
       <div id="container">
        <div id="body">
         <code>
           <table style="width:100%" border="1">
               <thead>
               <tr>
               <th width="200" class="text-center">Cost Components</th>
               <th width="80" class="text-center">Qty</th>
               <th width="80" class="text-center">Rate</th>
               <th width="80" class="text-center">Ammount</th>
               <th width="80" class="text-center">Discount %</th>
               <th width="80" class="text-center">Net Cost</th>
               </tr>
               </thead>
               <tbody>
                   <tr>
                       <td width="200">
                        <input type="hidden" name="id" id="id" value="" />
                        <input type="hidden" name="asset_return_detail_id" id="asset_return_detail_id" value="" />
                        <input type="text" name="cost_component" id="cost_component"/>
                       </td>
                       <td width="80"><input type="text" name="qty" id="qty" onchange="MsAssetReturnDetailCost.calculateAmount()" class="number integer" /></td>
                       <td width="80">
                        <input type="text" name="rate" id="rate" onchange="MsAssetReturnDetailCost.calculateAmount()"  class="number integer"/></td>
                       <td width="80">
                        <input type="text" name="amount" id="amount"  class="number integer" readonly/></td>
                       <td width="80"><input type="text" name="discount" id="discount" onmouseout="MsAssetReturnDetailCost.calculateNetCost()" class="number integer" /></td>
                       <td width="80"><input type="text" name="net_cost" id="net_cost"  class="number integer" readonly/></td>
                   </tr>
               </tbody>
           </table>
   		     </code>
        </div>
       </div>
       <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsAssetReturnDetailCost.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetreturndetailcostFrm')">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetReturnDetailCost.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="assetreturndetailcostTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="20">ID</th>
         <th data-options="field:'cost_component'" width="200">Cost Component</th>
         <th data-options="field:'qty'" width="65">Qty</th>
         <th data-options="field:'rate'" width="80">Rate</th>
         <th data-options="field:'amount'" width="100">Amount</th>
         <th data-options="field:'discount'" width="80">Discount</th>
         <th data-options="field:'net_cost'" width="120">Net Cost</th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
  </div>
 </div>

</div>

{{-- Fixed Asset Search Window --}}
<div id="openvendorwindow" class="easyui-window" title="Vindor Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#assetdtlft'" style="padding:2px">
   <table id="assetvendorsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">Id</th>
      <th data-options="field:'supplier_name'" width="200">Supplier Name</th>
      <th data-options="field:'supplier_code'" width="100">Code</th>
      <th data-options="field:'supplier_address'" width="400">Address</th>
     </tr>
    </thead>
   </table>
   <div id="assetdtlft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#openvendorwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#assetFrmft'" style="padding:2px; width:350px">
   <form id="assetvendorsearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row middle">
                                <div class="col-sm-4">Main Menu</div>
                                <div class="col-sm-8">
                                    {!! Form::select('menu_id',$menu,'',array('id'=>'menu_id')) !!}
                                </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="assetFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsAssetReturn.searchVendor()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

{{-- Asset Search Window --}}
<div id="openservicewindow" class="easyui-window" title="Asset Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#assetservicedtlft'" style="padding:2px">
   <table id="assetservicesearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'out_date'" width="100">Out Date</th>
      <th data-options="field:'returnable_date'" width="100">Returnable date</th>
      <th data-options="field:'asset_name'" width="200">Asset Name</th>
      <th data-options="field:'item_description'" width="200">Item Description</th>
      <th data-options="field:'qty'" width="100">Qty</th>
     </tr>
    </thead>
   </table>
   <div id="assetservicedtlft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#openservicewindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#assetpartFrmft'" style="padding:2px; width:350px">
   <form id="assetservicesearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row middle">
                                <div class="col-sm-4">Asset Name</div>
                                <div class="col-sm-8">
                                    <input type="text" name="asset_name" id="asset_name" />
                                </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="assetpartFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsAssetReturnDetail.searchAsset()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/FAMS/MsAllAssetReturnController.js"></script>
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
    // $('#assetreturnFrm [id="supplier_id"]').combobox();

})(jQuery);
</script>