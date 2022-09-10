<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilitemclasstabs">
    <div title="Item Class" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="itemclassTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'itemcategory'" width="100">Category</th>
                            <th data-options="field:'itemnature'" width="100">Item Nature</th>
                            <th data-options="field:'uomclass'" width="100">Uom Class</th>
                            <th data-options="field:'uom'" width="100">Budget Uom</th>
                            <th data-options="field:'is_pre_account'" width="100">Pre Account Req</th>
                        </tr>
                    </thead>    
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New ItemClass',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:350px; padding:2px">
                <form id="itemclassFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value=""/>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Category</div>
                                    <div class="col-sm-8">{!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Name </div>
                                    <div class="col-sm-8"><input type="text" name="name" id="name" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Code</div>
                                    <div class="col-sm-8"><input type="text" name="code" id="code" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Type</div>
                                    <div class="col-sm-8">{!! Form::select('trims_type_id', $trimstype,'',array('id'=>'trims_type_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item Nature</div>
                                    <div class="col-sm-8">{!! Form::select('item_nature_id', $itemnature,'',array('id'=>'item_nature_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Uom Class</div>
                                    <div class="col-sm-8">{!! Form::select('uomclass_id', $uomclass,'',array('id'=>'uomclass_id','onchange'=>'MsItemclass.getUom(this.value)')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bom Uom</div>
                                    <div class="col-sm-8">{!! Form::select('costing_uom_id', $uom,'',array('id'=>'costing_uom_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Calculator</div>
                                    <div class="col-sm-8">{!! Form::select('calculator_type_id', $calculatorneed,'',array('id'=>'calculator_type_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sensitivity</div>
                                    <div class="col-sm-8">{!! Form::select('sensivity_id', $sensivity,'',array('id'=>'sensivity_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Is Pre Account Req</div>
                                    <div class="col-sm-8">{!! Form::select('pre_account_req_id', $is_pre_account,'',array('id'=>'pre_account_req_id')) !!}</div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemclass.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('itemclassFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemclass.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Profit Center" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New ProfitCenter',footer:'#utilitemclassprofitcenterft'" style="width:450px; padding:2px">
                <form id="itemclassprofitcenterFrm">
                    <input type="hidden" name="itemclass_id" id="itemclass_id" value=""/>
                </form>
                <table id="itemclassprofitcenterTbl">
                </table>
                <div id="utilitemclassprofitcenterft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemclassProfitcenter.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Company'" style="padding:2px">
                <table id="itemclassprofitcentersavedTbl">
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllItemclassController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
