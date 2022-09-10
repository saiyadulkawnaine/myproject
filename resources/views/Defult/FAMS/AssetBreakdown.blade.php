<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="famsAssetBreakdowntabs" >
    <div title="Breakdown" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists', footer:'#assetbreakdownTblFt'" style="padding:2px">
                <table id="assetbreakdownTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'custom_no'" width="80">Custom <br/>Asset No</th>
                            <th data-options="field:'asset_name'" width="250">Asset Name</th>
                            <th data-options="field:'prod_capacity'" width="70">Capacity</th>
                            <th data-options="field:'breakdown_date'" width="80">Breakdown<br/> Date</th>
                            <th data-options="field:'breakdown_time'" width="80">Breakdown<br/> Time</th>
                            <th data-options="field:'reason_id'" width="100">Reason</th>
                            <th data-options="field:'decision_id'" width="130">Decision</th>
                            <th data-options="field:'employee_name'" width="100">Custody Of</th>
                            <th data-options="field:'remarks'" width="150">Remarks</th> 
                        </tr>
                    </thead>
                </table>
                <div id="assetbreakdownTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Breakdown Date: <input type="text" name="from_date" id="from_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsAssetBreakdown.searchBreakdown()">Show</a>
                </div>
            </div>

            <div data-options="region:'west',border:true,title:'Add New General Info',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="assetbreakdownFrm">
                                <div class="row" style="display: none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Asset No</div>
                                    <div class="col-sm-7">
                                        <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id" />
                                        <input type="text" name="custom_no" id="custom_no" ondblclick="MsAssetBreakdown.openAssetDtlWindow()" placeholder=" Double Click">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Breakdown Date</div>
                                    <div class="col-sm-3" style="padding-right:0px">
                                        <input type="text" name="breakdown_date" id="breakdown_date" value="" placeholder="date" class="datepicker"/>
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="breakdown_time" id="breakdown_time" value="" placeholder="hh:mm:ss am/pm"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Reason</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('reason_id',$reason,'',array('id'=>'reason_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Decision</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('decision_id',$decision,'',array('id'=>'decision_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Maintenance By</div>
                                    <div class="col-sm-7">
                                        <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" />
                                        <input type="text" name="name" id="name" ondblclick="MsAssetBreakdown.openBreakdownEmployee()" placeholder=" Double Click">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Estimated Recovery</div>
                                    <div class="col-sm-3" style="padding-right:0px">
                                        <input type="text" name="estimated_recovery_date" id="estimated_recovery_date" class="datepicker" placeholder="yyyy-mm-dd" value=""/>
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px;">
                                        <input type="text" name="estimated_recovery_time" id="estimated_recovery_time" placeholder="hh:mm:ss am/pm"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Custody Of</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_name" id="employee_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Asset Name</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="asset_name" id="asset_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Asset Type</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="type_id" id="type_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Category</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="production_area_id" id="production_area_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Group</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="asset_group" id="asset_group" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Brand</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="brand" id="brand" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Purchase Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="purchase_date" id="purchase_date" class="datepicker" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Serial No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="serial_no" id="serial_no" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Origin</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="origin" id="origin" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Capacity</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="prod_capacity" id="prod_capacity" disabled />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetBreakdown.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetbreakdownFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetBreakdown.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Recovery" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="assetrecoveryTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'custom_no'" width="100">Custom Asset No</th>
                            {{-- <th data-options="field:'breakdown_date'" width="80">Breakdown Date</th>
                            <th data-options="field:'breakdown_time'" width="80">Breakdown Time</th> --}}
                            <th data-options="field:'function_date'" width="80">Recovery Date</th>
                            <th data-options="field:'function_time'" width="80">Recovery Time</th>
                            <th data-options="field:'action_taken'" width="150">Action Taken</th> 
                            <th data-options="field:'reason_id'" width="100">Reason</th>
                            <th data-options="field:'decision_id'" width="100">Decision</th>
                            <th data-options="field:'employee_name'" width="100">Custody Of</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Recovery',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'" style="width:400px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="assetrecoveryFrm">
                                <div class="row" style="display: none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Recovery Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="function_date" id="function_date" class="datepicker" placeholder="yyyy-mm-dd" value=""/>
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px;">
                                        <input type="text" name="function_time" id="function_time" placeholder="hh:mm:ss am/pm"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Action Taken</div>
                                    <div class="col-sm-8">
                                        <textarea name="action_taken" id="action_taken"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Asset No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="custom_no" id="custom_no" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Custody Of</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="employee_name" id="employee_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Asset Name</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="asset_name" id="asset_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Asset Type</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="type_id" id="type_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Category</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="production_area_id" id="production_area_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Group</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="asset_group" id="asset_group" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Brand</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="brand" id="brand" disabled />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft4" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetRecovery.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('assetrecoveryFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetRecovery.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Fixed Asset Search Window --}}
<div id="openassetdtlwindow" class="easyui-window" title="Asset Details Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#assetdtlft'" style="padding:2px">
            <table id="assetdtlsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'custom_no'" width="100">Custom Asset No</th>
                        <th data-options="field:'asset_no'" width="100">Asset No</th>
                        <th data-options="field:'employee_name'" width="100">Custody of</th>
                        <th data-options="field:'asset_name'" width="100">Asset Name</th>
                        <th data-options="field:'origin'" width="100">Origin</th>
                        <th data-options="field:'brand'" width="100">Brand</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'location_id'" width="100">Location</th>
                        <th data-options="field:'type_id'" width="100">Asset Type</th>
                        <th data-options="field:'production_area_id'" width="100">Production Area</th>
                        <th data-options="field:'asset_group'" width="100">Group</th>
                        <th data-options="field:'supplier_id'" width="100">Supplier</th>
                        <th data-options="field:'iregular_supplier'" width="100">Irragular Supplier</th>
                        <th data-options="field:'prod_capacity'" width="100">Prod Capacity</th>
                        <th data-options="field:'purchase_date'" width="100">Purchase Date</th>
                    </tr>
                </thead>
            </table>
            <div id="assetdtlft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openassetdtlwindow').window('close')" style="width:80px">Close</a>
            </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#assetdtlFrmft'" style="padding:2px; width:350px">
            <form id="assetdtlsearchFrm">
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
                                    <input type="text" name="name" id="name" />
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="assetdtlFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" onClick="MsAssetBreakdown.searchAssetDtl()">Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--------------------Employee Search-Window Start------------------>
<div id="openbreakdownemployeewindow" class="easyui-window" title="Maintenance Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsAssetBreakdown.searchEmployeeGrid()">Search</a>
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
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'national_id'" width="100">National ID</th>
                        <th data-options="field:'address'" width="100">Address</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openbreakdownemployeewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/FAMS/MsAllAssetBreakdownController.js"></script>
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
    $('#assetbreakdownFrm [id="reason_id"]').combobox();

    $('#breakdown_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

    $('#function_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

    $('#estimated_recovery_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

})(jQuery);
</script>