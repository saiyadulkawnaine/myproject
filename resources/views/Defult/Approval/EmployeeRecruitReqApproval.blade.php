<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="employeerecruitreqapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="employeerecruitreqapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="70" formatter='MsEmployeeRecruitReqApproval.approveButton'></th>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'requisition_date'" width="80">Requisition<br> Date</th>
                            <th data-options="field:'employee_budget_position_id'" width="80">Cost <br>Center ID</th>
                            <th data-options="field:'no_of_required_position'" width="80">Required <br>Position</th>
                            <th data-options="field:'date_of_join'" width="80">Expected DOJ</th>
                            <th data-options="field:'age_limit'" width="80" align="right">Age Limit</th>
                            <th data-options="field:'employee_name'" width="100">Reporting Officer</th>
                            <th data-options="field:'justification'" width="120">Justification</th>
                            <th data-options="field:'person_specification'" width="100">Employee Spec</th>
                            <th data-options="field:'vacancy_available'" width="70" align="right">Vacancy <br>Available</th>
                            <th data-options="field:'budgeted_position'" width="70" align="right">Budgeted <br>Position</th>
                            <th data-options="field:'designation_level_id'" width="80">Level</th>
                            <th data-options="field:'grade'" width="100">Grade</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'designation_name'" width="100">Designation</th>
                            <th data-options="field:'location_name'" width="100">Location</th>
                            <th data-options="field:'division_name'" width="100">Division</th>
                            <th data-options="field:'department_name'" width="100">Department</th>
                            <th data-options="field:'section_name'" width="100">Section</th>
                            <th data-options="field:'subsection_name'" width="100">Sub Section</th>
                            <th data-options="field:'listreplace'" width="100" formatter="MsEmployeeRecruitReqApproval.formatempreplacement"></th>        
                            <th data-options="field:'listjobdesc'" width="100" formatter="MsEmployeeRecruitReqApproval.formatemployeerecruitjobdesc"></th>        
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#employeerecruitreqapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="employeerecruitreqapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4"> Requisition Date</div>
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
                <div id="employeerecruitreqapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeRecruitReqApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('employeerecruitreqapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="empreplacementWindow" class="easyui-window" title="Employee Recruitment Requisition Replacement" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:600px;height:600px;padding:2px;">
    <table id="empreplacementTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'employee_h_r_id'" width="80">ERP ID</th>
                <th data-options="field:'employee_name'" width="120">Name</th>
                <th data-options="field:'designation_name'" width="100">Designation</th>
            </tr>
        </thead>
    </table>
</div>
<div id="employeerecruitjobdescWindow" class="easyui-window" title="Recruitment Job Description" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:600px;height:600px;padding:2px;">
    <table id="employeerecruitjobdescTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'job_description'" width="250">New Job Description</th>
                <th data-options="field:'sort_id'" width="80">Sequence</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsEmployeeRecruitReqApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>
    