<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="employeehrapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="employeehrapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsEmployeeHRApproval.approveButton'></th>
                            <th data-options="field:'apl'" width="80" formatter='MsEmployeeHRApproval.aplButton'></th>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'user'" width="100">User</th>
                            <th data-options="field:'code'" width="100">User Given<br/> Code</th>
                            <th data-options="field:'employee_category'" width="100">Category</th>
                            <th data-options="field:'designation_level'" width="100">Structural <br>Designation</th>
                            <th data-options="field:'designation_name'" width="140">Functional<br>Designation</th>
                            <th data-options="field:'salary'" width="80" align="right">Salary</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'location_name'" width="80">Location</th>
                            <th data-options="field:'division_name'" width="100">Division</th>
                            <th data-options="field:'department_name'" width="100">Department</th>
                            <th data-options="field:'section_name'" width="100">Section</th>
                            <th data-options="field:'subsection_name'" width="100">Sub Section</th>

                            <th data-options="field:'grade'" width="50">Grade</th>
                            <th data-options="field:'created_date'" width="80">Entry Date</th>
                            <th data-options="field:'date_of_join'" width="80">Date of Join</th>
                            <th data-options="field:'date_of_birth'" width="80">Date of Birth</th>
                            <th data-options="field:'contact'" width="100">Phone No</th>
                            <th data-options="field:'email'" width="120">Email Address</th>
                            <th data-options="field:'national_id'" width="100">National ID</th>
                            <th data-options="field:'address'" width="120">Address</th>
                            <th data-options="field:'compliance_salary'" width="80" align="right">Compliance<br/>Salary</th>
                            <th data-options="field:'is_advanced_applicable'" width="100">Advance Applicable</th>
                            <th data-options="field:'last_education'" width="100">Last Education</th>
                            <th data-options="field:'experience'" width="100">Experience</th>
                            <th data-options="field:'tin'" width="100">Tin</th>       
                            <th data-options="field:'status'" width="100">Status</th>       
                            <th data-options="field:'api_status'" width="100">API Status</th> 
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#employeehrapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="employeehrapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4"> Joining Date</div>
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
                <div id="employeehrapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeHRApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('employeehrapprovalFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeHRApproval.showExcel()">Excel</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsEmployeeHRApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>
    