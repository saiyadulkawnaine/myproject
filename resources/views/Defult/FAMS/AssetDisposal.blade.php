<div class="easyui-layout" data-options="fit:true">
 <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
  <table id="assetdisposalTbl" style="width:100%">
   <thead>
    <tr>
     <th data-options="field:'id'" width="40">ID</th>
     <th data-options="field:'custom_no'" width="100">Asset No</th>
     <th data-options="field:'disposal_date'" width="100">Disposal Date</th>
     <th data-options="field:'buyer_name'" width="100">Sold/Donated To</th>
     <th data-options="field:'sold_amount'" width="100">Sold Amount</th>
     <th data-options="field:'production_area_id'" width="100">Production Area</th>
     {{-- <th data-options="field:'asset_group'" width="100">Group</th>
                    <th data-options="field:'store_id'" width="100">Store</th>
                    <th data-options="field:'supplier_id'" width="100">Supplier</th>
                    <th data-options="field:'iregular_supplier'" width="100">Irragular Supplier</th> --}}
     {{-- <th data-options="field:'brand'" width="100">Brand</th>
                    <th data-options="field:'origin'" width="100">Origin</th>
                    <th data-options="field:'purchase_date'" width="100">Purchase Date</th>
                    <th data-options="field:'qty'" width="100">Quantity</th>
                    <th data-options="field:'prod_capacity'" width="100">Prod Capacity</th>
                    <th data-options="field:'uom_id'" width="100">UOM</th>
                    <th data-options="field:'sort_id'" width="100">Sequence</th> --}}
    </tr>
   </thead>
  </table>
 </div>
 <div
  data-options="region:'west',border:true,title:'Add Asset Disposal Info',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
  style="width:400px; padding:2px">
  <div id="container">
   <div id="body">
    <code>
                    <form id="assetdisposalFrm">
                        <div class="row middle">
                            <div class="col-sm-5 req-text">Disposal Id</div>
                            <div class="col-sm-7">
                              <input type="text" name="id" id="id" readonly/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Asset No</div>
                            <div class="col-sm-7">
                                <input type="text" name="custom_no" id="custom_no" ondblclick="MsAssetDisposal.openAssetWindow()" placeholder="Double Click" value="">
                                <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id">
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">Disposal Date</div>
                            <div class="col-sm-7">
                               <input type="text" name="disposal_date" id="disposal_date" class="datepicker">
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">Disposal Type</div>
                            <div class="col-sm-7">
                               {!! Form::select('disposal_type_id', $disposal_type,'',array('id'=>'disposal_type_id')) !!} 
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5 req-text areaCon">Sold/Donated To</div>
                            <div class="col-sm-7">
                              {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5 req-text areaCon">Sold Amount</div>
                            <div class="col-sm-7">
                               <input type="text" name="sold_amount" id="sold_amount" class="number integer" onchange="MsAssetDisposal.calculateGainLoss()" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">Original Cost</div>
                            <div class="col-sm-7">
                                <input type="text" name="origin_cost" id="origin_cost" placeholder="dispaly" readonly" class="number integer"/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Acummulated Dep.</div>
                            <div class="col-sm-7">
                            <input type="text" name="accumulated_dep" id="accumulated_dep" placeholder="display" readonly class="number integer"/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Written Down Value</div>
                            <div class="col-sm-7">
                                <input type="text" name="written_down_value" id="written_down_value" onchange="MsAssetDisposal.calculateGainLoss()" class="number integer" readonly/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Gain/Loss</div>
                            <div class="col-sm-7">
                              <input type="text" name="gain_loss" id="gain_loss" readonly class="number integer" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Company</div>
                            <div class="col-sm-7">
                                <input type="text" name="company_id" id="company_id" placeholder="display" disabled/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Asset Name</div>
                            <div class="col-sm-7">
                                <input type="text" name="asset_name" id="asset_name" placeholder="display" disabled/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Location</div>
                            <div class="col-sm-7">
                               <input type="text" name="location_id" id="location_id" placeholder="display" disabled>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Category</div>
                            <div class="col-sm-7">
                                <input type="text" name="production_area_id" id="production_area_id" placeholder="display" disabled>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Asset Type</div>
                            <div class="col-sm-7">
                               <input type="text" name="type_id" id="type_id" placeholder="display" disabled>
                            </div>
                        </div>
                        
                        <div class="row middle">
                            <div class="col-sm-5">Group</div>
                            <div class="col-sm-7">
                                <input type="text" name="asset_group" id="asset_group" placeholder="display" disabled>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Origin</div>
                            <div class="col-sm-7">
                                <input type="text" name="origin" id="origin" placeholder="display" disabled>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Brand</div>
                            <div class="col-sm-7">
                                <input type="text" name="brand" id="brand" placeholder="display" disabled>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Serial No</div>
                            <div class="col-sm-7">
                                <input type="text" name="serial_no" id="serial_no" placeholder="display" disabled>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Purchase Date</div>
                            <div class="col-sm-7">
                                <input type="text" name="purchase_date" id="purchase_date" placeholder="display" disabled>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Salvage Value</div>
                            <div class="col-sm-7">
                                <input type="text" name="salvage_value" id="salvage_value" placeholder="display" disabled>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Dep. Method</div>
                            <div class="col-sm-7">
                                <input type="text" name="depreciation_method_id" id="depreciation_method_id" disabled placeholder="display">
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Dep. Rate</div>
                            <div class="col-sm-7">
                                <input type="text" name="depreciation_rate" id="depreciation_rate" disabled placeholder="display">
                            </div>
                        </div>
                    </form>
                </code>
   </div>
  </div>
  <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
   <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save"
    plain="true" id="save" onClick="MsAssetDisposal.submit()">Save</a>
   <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove"
    plain="true" id="delete" onClick="msApp.resetForm('assetdisposalFrm')">Reset</a>
   <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove"
    plain="true" id="delete" onClick="MsAssetDisposal.remove()">Delete</a>
  </div>
 </div>
</div>

{{-- Fixed Asset Search Window --}}
<div id="openassetwindow" class="easyui-window" title="Asset Details Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#assetdtlft'" style="padding:2px">
   <table id="assetsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'custom_no'" width="100">Custom Asset No</th>
      <th data-options="field:'asset_no'" width="100">Asset No</th>
      <th data-options="field:'employee_name'" width="100">Custody of</th>
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
      onClick="MsAssetDisposal.searchAsset()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/FAMS/MsAssetDisposalController.js"></script>
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
    $('#assetdisposalFrm [id="buyer_id"]').combobox();

})(jQuery);
</script>