<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="assetserviceTabs" title="Asset services">
 <div title="Asset services" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="assetserviceTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'out_date'" width="100">Out Date</th>
       <th data-options="field:'returnable_date'" width="100">Returnable Date</th>
       <th data-options="field:'supplier_name'" width="100">Supplier</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>

   </div>
   <div
    data-options="region:'west',border:true,title:'Add New Service Info',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
    style="width:400px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                <form id="assetserviceFrm">
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Out Date</div>
                        <div class="col-sm-7">
                           <input type="text" name="out_date" id="out_date" class="datepicker">
																											<input type="hidden" name="id" id="id">
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
                </form>
            </code>
     </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsAssetService.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetserviceFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetService.remove()">Delete</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetService.getPdf()">PDF</a>
    </div>
   </div>
  </div>
 </div>

 <div title="Asset Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="assetservicedetailTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="20">ID</th>
       <th data-options="field:'asset_name'" width="80">Asset Name</th>
       <th data-options="field:'type_id'" width="100">Asset Type</th>
       <th data-options="field:'asset_group'" width="100">Group</th>
       <th data-options="field:'brand'" width="80">Brand</th>
       <th data-options="field:'purchase_date'" width="100">Purchase Date</th>
       <th data-options="field:'remarks'" width="80">remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Asset',hideCollapsedContent:false,collapsed:false,footer:'#ft4'"
    style="width:400px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
             <form id="assetservicedetailFrm">
                 <div class="row" style="display: none">
                     <input type="hidden" name="id" id="id" value="" />
                     <input type="hidden" name="asset_service_id" id="asset_service_id" value="">
                 </div>
                 <div class="row middle">
																			<div class="col-sm-4">Asset Name</div>
																			<div class="col-sm-8">
																						<input type="text" name="asset_name" id="asset_name" placeholder="Browser" ondblclick="MsAssetServiceDetail.openAssetWindow()"/>
																						<input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id">
																			</div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4">Remark</div>
                     <div class="col-sm-8">
                        <textarea name="remarks" id="remarks"></textarea>
                     </div>
                 </div>
																	<div class="row middle">
																		<div class="col-sm-4">Asset Type</div>
																		<div class="col-sm-8">
																			<input type="text" name="type_id" id="type_id" placeholder="display" disabled>
																		</div>
																	</div>
																	<div class="row middle">
																		<div class="col-sm-4">Category</div>
																		<div class="col-sm-8">
																			<input type="text" name="production_area_id" id="production_area_id" placeholder="display" disabled>
																		</div>
																	</div>
																	<div class="row middle">
																		<div class="col-sm-4">Group</div>
																		<div class="col-sm-8">
																			<input type="text" name="asset_group" id="asset_group" placeholder="display" disabled>
																		</div>
																	</div>
																	<div class="row middle">
																		<div class="col-sm-4">Brand</div>
																		<div class="col-sm-8">
																			<input type="text" name="brand" id="brand" placeholder="display" disabled>
																		</div>
																	</div>
																	<div class="row middle">
																		<div class="col-sm-4">Action</div>
																		<div class="col-sm-8">
																			<input type="text" name="action_taken" id="action_taken" placeholder="display" disabled>
																		</div>
																	</div>
																	<div class="row middle">
																		<div class="col-sm-4">Purchase Date</div>
																		<div class="col-sm-8">
																			<input type="text" name="purchase_date" id="purchase_date" placeholder="display" disabled>
																		</div>
																	</div>
             </form>
         </code>
     </div>
    </div>
    <div id="ft4" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsAssetServiceDetail.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetservicedetailFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetServiceDetail.remove()">Delete</a>
    </div>
   </div>
  </div>
 </div>
</div>

{{-- Fixed Asset Search Window --}}
<div id="openassetwindow" class="easyui-window" title="Asset Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#assetdtlft'" style="padding:2px">
   <table id="assetsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'asset_no'" width="80">Asset No</th>
      <th data-options="field:'asset_name'" width="100">Asset Name</th>
      <th data-options="field:'company_name'" width="100">Company</th>
      <th data-options="field:'location_name'" width="100">Location</th>
      <th data-options="field:'type_id'" width="100">Asset Type</th>
      <th data-options="field:'production_area_id'" width="100">Production Area</th>
      <th data-options="field:'asset_group'" width="100">Group</th>
      <th data-options="field:'supplier_name'" width="100">Supplier</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'origin'" width="100">Origin</th>
      <th data-options="field:'purchase_date'" width="100">Purchase Date</th>
      <th data-options="field:'qty'" width="100">Quantity</th>
      <th data-options="field:'prod_capacity'" width="100">Prod Capacity</th>
      <th data-options="field:'uom_id'" width="100">UOM</th>
     </tr>
    </thead>
   </table>
   <div id="assetdtlft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#openassetwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#assetFrmft'" style="padding:2px; width:350px">
   <form id="assetsearchFrm">
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
      onClick="MsAssetServiceDetail.searchAsset()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/FAMS/MsAllAssetServiceController.js"></script>
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
    $('#assetserviceFrm [id="supplier_id"]').combobox();

})(jQuery);
</script>