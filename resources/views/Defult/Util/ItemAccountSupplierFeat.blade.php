<div class="easyui-layout "  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="itemaccountsupplierfeatTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'feature_point_id'" width="100">Feature Point</th>
                    <th data-options="field:'available_id'" width="100">Available</th>
                    <th data-options="field:'mandatory_id'" width="100">Mandatory</th>
                    <th data-options="field:'values'" width="100">Values</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Supplier Features',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ftfeat'" style="width:350px; padding:2px">
        <form id="itemaccountsupplierfeatFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row" style="display:none">
                            <input type="hidden" name="id" id="id" value=""/>
                            <input type="hidden" name="item_account_supplier_id" id="item_account_supplier_id" value=""/>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Item Description </div>
                            <div class="col-sm-8">
                                <input type="text" name="item_description" id="item_description" value="" disabled />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Specification</div>
                            <div class="col-sm-8">
                                <input type="text" name="specification" id="specification" value="" disabled/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Supplier</div>
                            <div class="col-sm-8">
                                {!! Form::select('supplier_name', $supplier,'',array('id'=>'supplier_name','disabled'=>'disabled')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Feature Point</div>
                            <div class="col-sm-8">
                                {!! Form::select('feature_point_id', $feature,'',array('id'=>'feature_point_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Available</div>
                            <div class="col-sm-8">
                                {!! Form::select('available_id', $yesno,'',array('id'=>'available_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Mandatory</div>
                            <div class="col-sm-8">
                                {!! Form::select('mandatory_id', $yesno,'',array('id'=>'mandatory_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Values</div>
                            <div class="col-sm-8">
                                <textarea name="values" id="values"></textarea>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Remarks </div>
                            <div class="col-sm-8">
                                <textarea name="remarks" id="remarks"></textarea>
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ftfeat" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemAccountSupplierFeat.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemAccountSupplierFeat.resetForm()" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemAccountSupplierFeat.remove()" >Delete</a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsItemAccountSupplierFeatController.js"></script>