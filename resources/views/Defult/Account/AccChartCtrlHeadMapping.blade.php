<div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Chart Of Account Mapping" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Control Head'" style="padding:2px">
                <table id="accchartctrlheadmappingTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th width="75" data-options="field:'asset_head_code',halign:'center'">A/C Code</th>
                            <th data-options="field:'asset_head_name'" width="250">Asset Name</th>
                            <th width="75" data-options="field:'accumulate_head_code',halign:'center'">A/C Code</th>
                            <th data-options="field:'accumulate_head_name'" width="250">Accumulated Depreciation</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'New Control Head Mapping',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="accchartctrlheadmappingFrm">
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Asset Head</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="asset_head_name" id="asset_head_name" placeholder="Double Click"  ondblclick="MsAccChartCtrlHeadMapping.openAssetHeadWindow()"/>
                                        <input type="hidden" name="acc_chart_ctrl_head_id" id="acc_chart_ctrl_head_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Accumulated Depreciation</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="accumulate_head_name" id="accumulate_head_name" placeholder="Double Click"  ondblclick="MsAccChartCtrlHeadMapping.openAccumulatedHeadWindow()"/>
                                        <input type="hidden" name="acc_acumulate_ctrl_head_id" id="acc_acumulate_ctrl_head_id" value="" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccChartCtrlHeadMapping.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccChartCtrlHeadMapping.resetForm()">Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccChartCtrlHeadMapping.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="assetheadwindow" class="easyui-window" title="Asset Head Control Heads" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table  border="1" id="assetheadTbl">
        <thead>
            <tr>
                <th width="70" data-options="field:'code',halign:'center'">A/C Code</th>
                <th width="230" data-options="field:'asset_head_name',halign:'center'">Head Name</th>
                <th width="190" data-options="field:'root_id',halign:'center'">Report Head</th>
                <th width="190" data-options="field:'sub_group_name',halign:'center'">Sub Group</th>
                <th width="120" data-options="field:'accchartgroup',halign:'center'">Main Group</th>
                <th width="90" data-options="field:'statement_type_id',halign:'center'">Statement Type</th>
                <th width="100" data-options="field:'control_name_id',halign:'center'">Control Name</th>
                <th width="80" data-options="field:'currency_id',halign:'center'">Currency</th>
                <th width="80" data-options="field:'other_type_id',halign:'center'">Other Type</th>
                <th width="60" data-options="field:'normal_balance_id',halign:'center'">Normal Balance</th>
                <th width="60" data-options="field:'status',halign:'center'">Status</th>
            </tr>
        </thead>
    </table>
</div>

<div id="accumulatedheadwindow" class="easyui-window" title="Accumulated Depreciation Control Heads" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table  border="1" id="accumulatedheadTbl">
        <thead>
            <tr>
                <th width="70" data-options="field:'code',halign:'center'">A/C Code</th>
                <th width="230" data-options="field:'accumulate_head_name',halign:'center'">Head Name</th>
                <th width="190" data-options="field:'root_id',halign:'center'">Report Head</th>
                <th width="190" data-options="field:'sub_group_name',halign:'center'">Sub Group</th>
                <th width="120" data-options="field:'accchartgroup',halign:'center'">Main Group</th>
                <th width="90" data-options="field:'statement_type_id',halign:'center'">Statement Type</th>
                <th width="100" data-options="field:'control_name_id',halign:'center'">Control Name</th>
                <th width="80" data-options="field:'currency_id',halign:'center'">Currency</th>
                <th width="80" data-options="field:'other_type_id',halign:'center'">Other Type</th>
                <th width="60" data-options="field:'normal_balance_id',halign:'center'">Normal Balance</th>
                <th width="60" data-options="field:'status',halign:'center'">Status</th>
            </tr>
        </thead> 
    </table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAccChartCtrlHeadMappingController.js"></script>