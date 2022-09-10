<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="employeehrstatusapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="employeehrstatusapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsEmployeeHRStatusApproval.approveButton'></th>
                            <th data-options="field:'apl'" width="60" formatter='MsEmployeeHRStatusApproval.aplButton'></th>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'employee_h_r_id'" width="80">ERP ID</th>
                            <th data-options="field:'employee_name'" width="100">Employee</th>
                            <th data-options="field:'status'" width="60">Status</th>
                            <th data-options="field:'created_date'" width="80">Entry Date</th>
                            <th data-options="field:'status_date'" width="80">Status Date</th>
                            <th data-options="field:'designation_name'" width="100">Designation</th>
                            <th data-options="field:'grade'" width="100">Grade</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'location_name'" width="100">Location</th>
                            <th data-options="field:'division_name'" width="100">Division</th>
                            <th data-options="field:'department_name'" width="100">Department</th>
                            <th data-options="field:'section_name'" width="80">Section</th>
                            <th data-options="field:'subsection_name'" width="80">Subsection</th>
                            <th data-options="field:'logistics_status'" width="100">Reason/<br/>Inactive for</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th> 
                            <th data-options="field:'api_status'" width="60">API Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#employeehrstatusapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="employeehrstatusapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4"> Status Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="employeehrstatusapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeHRStatusApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('employeehrstatusapprovalFrm')">Reset</a>
                    
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsEmployeeHRStatusApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>
    