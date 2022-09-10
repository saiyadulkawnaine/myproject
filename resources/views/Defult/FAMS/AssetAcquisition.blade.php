<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="famsAssettabs" title="Asset Acquisitions">
    <div title="General Information" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="assetacquisitionTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'type_id'" width="100">Asset Type</th>
                            <th data-options="field:'production_area_id'" width="100">Production Area</th>
                            <th data-options="field:'asset_group'" width="100">Group</th>
                            <th data-options="field:'store_id'" width="100">Store</th>
                            <th data-options="field:'supplier_id'" width="100">Supplier</th>
                            <th data-options="field:'iregular_supplier'" width="100">Irragular Supplier</th>
                            <th data-options="field:'brand'" width="100">Brand</th>
                            <th data-options="field:'origin'" width="100">Origin</th>
                            <th data-options="field:'purchase_date'" width="100">Purchase Date</th>
                            <th data-options="field:'qty'" width="100">Quantity</th>
                            <th data-options="field:'prod_capacity'" width="100">Prod Capacity</th>
                            <th data-options="field:'uom_id'" width="100">UOM</th>
                            <th data-options="field:'sort_id'" width="100">Sequence</th>
                        </tr>
                    </thead>
                </table>

            </div>
            <div data-options="region:'west',border:true,title:'Add New General Info',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="assetacquisitionFrm">
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Location</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Asset Name</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="name" id="name" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Asset Type</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('type_id',$assetType,'',array('id'=>'type_id','style'=>'width: 100%; border-radius:2px','onchange'=>'MsAssetAcquisition.assetTypechange(this.value)')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text areaCon">Production Area</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Group</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="asset_group" id="asset_group" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Store</div>
                                    <div class="col-sm-7">
                                    	{!! Form::select('store_id',$store,'',array('id'=>'store_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Listed Supplier</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('supplier_id',$supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Irragular Supplier</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="iregular_supplier" id="iregular_supplier" placeholder=" If not exist in the supplier list"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Brand</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="brand" id="brand" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Origin</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="origin" id="origin" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Prod. Capacity</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="prod_capacity" id="prod_capacity" class="integer number" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Purchase Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="purchase_date" id="purchase_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Qty.</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="qty" id="qty" class="integer number" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-5">UOM</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('uom_id',$uom,'',array('id'=>'uom_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Sequence No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sort_id" id="sort_id"/>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetAcquisition.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetAcquisition.openqtywindow()">Add Details</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetacquisitionFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetAcquisition.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Asset Acquisition Depreciation------------>
    <div title="Depreciation" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Asset Depreciation',iconCls:'icon-more',footer:'#depft'" style="width:350px; padding:2px">
                <form id="assetdepreciationFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5">Depreciation Method</div>
                                    <div class="col-sm-7">
                                        <input type="hidden" name="id" id="id">
                                        {!! Form::select('depreciation_method_id',$depMethod,'',array('id'=>'depreciation_method_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Depreciation Rate</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="depreciation_rate" id="depreciation_rate" class="number integer" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="depft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetDepreciation.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetdepreciationFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetDepreciation.remove()">Delete</a>
                    </div>
    
            </form>
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="assetdepreciationTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'depreciation_method_id'" width="100">Depreciation Method</th>
                            <th data-options="field:'depreciation_rate'" width="100">Depreciation Rate</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--------Technical Features---------------->
    <div title="Technical Feature" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Technical Feature',iconCls:'icon-more',footer:'#utiltechfeatureft'" style="width:450px; padding:2px">
                <form id="assettechfeatureFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                <div class="row">
                                    <div class="col-sm-4">Dia/Width </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="dia_width" id="dia_width" class="number integer" >
                                        <input type="hidden" name="asset_acquisition_id" id="asset_acquisition_id" value=""/>
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Gauge </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gauge" id="gauge" class="number integer" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Extra Cylinder</div>
                                    <div class="col-sm-8">
                                       <input type="text" name="extra_cylinder" class="number integer" id="extra_cylinder" /> 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">No of Feeder </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_feeder" id="no_of_feeder" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Attachment</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="attachment" id="attachment" value="" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="utiltechfeatureft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetTechnicalFeature.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assettechfeatureFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetTechnicalFeature.remove()">Delete</a>
                    </div>

                </form>


            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="assettechfeatureTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'dia_width'" width="80">Dia/Width</th>
                            <th data-options="field:'gauge'" width="100">Gauge</th>
                            <th data-options="field:'extra_cylinder'" width="100">Extra Cylinder</th>
                            <th data-options="field:'no_of_feeder'" width="100">No of Feeder</th>
                            <th data-options="field:'attachment'" width="100">Attachment</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--------Technical Features Image Upload---------------->
    <div title="Image" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Image',iconCls:'icon-more',footer:'#assetimageft'" style="width:450px; padding:2px">
                <form id="assettechimageFrm" enctype="multipart/form-data">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="asset_acquisition_id" id="asset_acquisition_id" value=""/>
		                            <!--input type="hidden" name="asset_technical_feature_id" id="asset_technical_feature_id" value=""/-->
                                </div>
                                <div class="row middle">
                                   <div class="col-sm-4 req-text">Image</div>
                                   <div class="col-sm-8">
                                    	<input type="file" id="file_src" name="file_src" />
                                    	<!-- onchange="MsAssetTechImage.loadImage(event)"-->
                                   </div>
                            	  </div>                               
                            </code>
                        </div>
                    </div>
                    <div id="assetimageft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetTechImage.submit()">Upload</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assettechimageFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetTechImage.remove()">Delete</a>
                    </div>

                </form>


            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="assettechimageTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'file_src',halign:'center',align:'center'" formatter="MsAssetTechImage.formatimage" width="100">Image</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--------Technical Features  File Upload---------------->
    <div title="Files" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Upload File',iconCls:'icon-more',footer:'#assetfileft'" style="width:450px; padding:2px">
                <form id="assettechfileuploadFrm" enctype="multipart/form-data">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="asset_acquisition_id" id="asset_acquisition_id" value=""/>
                                </div>
                                <div class="row middle">
                                   <div class="col-sm-4 req-text">File Upload</div>
                                   <div class="col-sm-8">
                                    	<input type="file" id="file_upload" name="file_upload" value="" />
                                    	<!-- onchange="MsAssetTechImage.loadImage(event)"-->
                                   </div>
                            	  </div>
                                
                            </code>
                        </div>
                    </div>
                    <div id="assetfileft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetTechFileUpload.submit()">Upload</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assettechfileuploadFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetTechFileUpload.remove()">Delete</a>
                    </div>

                </form>


            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="assettechfileuploadTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'file_src'" width="80">Upload Files</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--------------------Utilities------------------------------->
    <div title="Utility Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Utility Details',iconCls:'icon-more',footer:'#assetutilft'" style="width:450px; padding:2px">
                <form id="assetutilitydetailFrm" >
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                <div class="row">
                                    <div class="col-sm-4">Power Consumption/Hr</div>
                                    <div class="col-sm-4">
                                        <input type="text" name="power_consumption" id="power_consumption">
                                        <input type="hidden" name="asset_acquisition_id" id="asset_acquisition_id" value="" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="power_rate" id="power_rate" value="" class="number integer" placeholder="  RATE / UNIT"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Water Consumption </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="water_consumption" id="water_consumption" value="" />
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="water_rate" id="water_rate" value="" class="number integer" placeholder="  RATE / UNIT"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Air Consumption </div>
                                    <div class="col-sm-4">
                                       <input type="text" name="air_consumption" id="air_consumption"> 
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="air_rate" id="air_rate" value="" class="number integer" placeholder="  RATE / UNIT"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Steam Consumption </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="steam_consumption" id="steam_comsumption">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="steam_rate" id="steam_rate" value="" class="number integer" placeholder="  RATE / UNIT"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">GAS Consumption</div>
                                    <div class="col-sm-4">
                                        <input type="text" name="gas_consumption" id="gas_consumption" value="" />
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="gas_rate" id="gas_rate" value="" class="number integer" placeholder="  RATE / UNIT"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Power Stating Load</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="power_stating_load" id="power_stating_load" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Power Running Load</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="power_running_load" id="power_running_load" value="" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="assetutilft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetUtilityDetail.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetutilitydetailFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetUtilityDetail.remove()">Delete</a>
                    </div>

                </form>


            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="assetutilitydetailTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'power_consumption'" width="80">Power Consumption/Hr</th>
                            <th data-options="field:'water_consumption'" width="100">Water Consumption</th>
                            <th data-options="field:'air_consumption'" width="100">Air Consumption</th>
                            <th data-options="field:'steam_consumption'" width="100">Steam Consumption</th>
                            <th data-options="field:'gas_consumption'" width="100">GAS Consumption</th>
                            <th data-options="field:'power_stating_load'" width="100">Power Stating Load</th>
                            <th data-options="field:'power_running_load'" width="100">Power Running Load</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!----------............Maintenance.....................------------>
    <div title="Maintenance" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Maintenance Information',iconCls:'icon-more',footer:'#assetmaintft'" style="width:450px; padding:2px">
                <form id="assetmaintenanceFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_description" id="item_description" placeholder="  Double Click" onDblClick="MsAssetMaintenance.openItemDescWindow()" readonly />
                                        <input type="hidden" name="item_account_id" id="item_account_id" value="" />
                                        <input type="hidden" name="asset_acquisition_id" id="asset_acquisition_id" value="" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rate/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" />
                                    </div>
                                </div>
                                
                            </code>
                        </div>
                    </div>
                    <div id="assetmaintft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetMaintenance.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetmaintenanceFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetMaintenance.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="assetmaintenanceTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'item_description'" width="80">Item</th>
                            <th data-options="field:'rate'" width="100">Rate/Unit</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

 <!----------............Man Power.....................------------>
    <div title="Man Power" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add New Manpower',iconCls:'icon-more',footer:'#utilmanpowerft'" style="width:450px; padding:2px">
                <form id="assetmanpowerFrm">
                    <div id="container">
                        <div id="body">
                            <code>                          
                                <div class="row">  
                                    <input type="hidden" name="asset_acquisition_id" id="asset_acquisition_id" value=""/>
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Machine No</div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id" />
                                        <input type="text" name="custom_no" id="custom_no" ondblclick="MsAssetManpower.openManpowerMachineWindow()" placeholder=" Double Click">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Employee </div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" />
                                        <input type="text" name="name" id="name" ondblclick="MsAssetManpower.openManpowerEmployee()" placeholder=" Double Click">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Tenure Start</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="tenure_start" id="tenure_start" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Tenure End</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="tenure_end" id="tenure_end" class="datepicker" />
                                    </div>
                                </div>  
                            </code>
                        </div>
                    </div>
                    <div id="utilmanpowerft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetManpower.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetmanpowerFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetManpower.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Manpower Lists'" style="padding:2px">
                <table id="assetmanpowerTbl" style="width:100%"> 
                    <thead>
                        <tr>
                            <th data-options="field:'custom_no'" width="100">Machine No</th> 
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'employee_h_r_id'" width="80">Employee</th>
                            <th data-options="field:'tenure_start'" width="100">Tenure Start</th>
                            <th data-options="field:'tenure_end'" width="100">Tenure End</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!--------------------Item Search-Window Start------------------>
<div id="OpenItemWindow" class="easyui-window" title="Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:450px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="itemDescSearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Item Category</div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id')) !!}
                                    <input type="hidden" name="item_account_id" id="item_account_id" value="" />
                                </div>
                            </div>

                            <div class="row middle">
                                <div class="col-sm-4">Item Class </div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsAssetMaintenance.searchItemDesGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="itemDescSearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'name'" width="100">Category</th>
                        <th data-options="field:'class_name'" width="100">Class</th>
                        <th data-options="field:'item_description'" width="100">Item Description</th>
                        <th data-options="field:'item_nature_id'" width="100">Nature</th>    
                        <th data-options="field:'reorder_level '" width="100">Reorder Level  </th>        
                        
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#OpenItemWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<!--------------------Item Search-Window Ends----------------->

<!-------==============Quantity Pop Up START===================-------------->
<div id="OpenQuantityWindow" class="easyui-window" title="Quantity Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Add Quantity Cost',footer:'#ftqtycost'" style="padding:2px">
            <form id="assetquantitycostFrm">
              <input type="hidden" name="id" id="id" />
                <code id="assetqty">
                    
                </code>
                <div id="ftqtycost" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetQuantityCost.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetquantitycostFrm')">Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetQuantityCost.remove()">Delete</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-------==============Asset Image Pop Up===================-------------->
<div id="assetImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="assetImageWindowoutput" src=""/>
</div>
<!--------------------Employee Search-Window Start------------------>
<div id="openmanpoweremployeewindow" class="easyui-window" title="Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="assetempsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Designation</div>
                                <div class="col-sm-8">
                                    {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Department </div>
                                <div class="col-sm-8">
                                    {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsAssetManpower.searchEmployeeGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="assetempsearchTbl" style="width:700px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'name'" width="100">Name</th>
                        <th data-options="field:'code'" width="100">User Given Code</th>
                        <th data-options="field:'designation_id'" width="100">Designation</th>
                        <th data-options="field:'department_id'" width="100">Department</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'grade'" width="100">Grade</th>
                        <th data-options="field:'date_of_join'" width="120">Date of Join</th>
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'national_id'" width="100">National ID</th>
                        <th data-options="field:'address'" width="100">Address</th>
                        <th data-options="field:'is_advanced_applicable'" width="100">Advance Applicable</th>
                        <th data-options="field:'last_education'" width="100">Last Education</th>
                        <th data-options="field:'experience'" width="100">Experience</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openmanpoweremployeewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Machine No --}}
<div id="assetmachineWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#plknitmachinesearchTblft'" style="padding:2px">
            <table id="assetmachinesearchTbl" style="width:100%">
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
            <div id="plknitmachinesearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                 <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#assetmachineWindow').window('close')" style="width:80px">Close</a>
            </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#plknitmachinesearchFrmft'" style="padding:2px; width:350px">
            <form id="assetmachinesearchFrm">
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
                <div id="plknitmachinesearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" onClick="MsAssetManpower.searchAssetMachine()">Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/FAMS/MsAllAssetAcquisitionController.js"></script>
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
    $('#assetacquisitionFrm [id="location_id"]').combobox();
    $('#assetacquisitionFrm [id="type_id"]').combobox();
    $('#assetacquisitionFrm [id="supplier_id"]').combobox();
    $('#assetacquisitionFrm [id="uom_id"]').combobox();
    $('#assetempsearchFrm [id="department_id"]').combobox();
    $('#assetempsearchFrm [id="designation_id"]').combobox();

})(jQuery);
</script>