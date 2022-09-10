<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="employeeRecruittabs">
    <div title="Cost Center Hirer Key" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center',border:true" style="padding:2px">
                        <table id="employeerecruitreqTbl" style="width:100%" >
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="40">ID</th>
                                    <th data-options="field:'company_name'" width="100">Company</th>
                                    <th data-options="field:'location_name'" width="140">Location</th>
                                    <th data-options="field:'division_name'" width="120">Division</th>
                                    <th data-options="field:'department_name'" width="130">Department</th>
                                    <th data-options="field:'section_name'" width="100">Section</th>
                                    <th data-options="field:'subsection_name'" width="100">Sub Section</th>    
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft2'" style="width: 400px;">
                <form id="employeerecruitreqFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Requisition Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="requisition_date" id="requisition_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Cost Center ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_budget_position_id" id="employee_budget_position_id" ondblclick="MsEmployeeRecruitReq.openEmployeeBudgetWindow()" placeholder="Double Click Here" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Designation</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="designation_name" id="designation_name"  disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Required Position</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_required_position" id="no_of_required_position"  class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Expected DOJ</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="date_of_join" id="date_of_join"  class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Age Limit</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="age_limit" id="age_limit"  class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Reporting Officer</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_name" id="employee_name"  placeholder=" Double Click To Select" ondblclick="MsEmployeeRecruitReq.openEmpHrWindow()" />
                                        <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Justification</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="justification" id="justification"  placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Employee Spec</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="person_specification" id="person_specification"  placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Vacancy Available</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="vacancy_available" id="vacancy_available" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Budgeted Position</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="budgeted_position" id="budgeted_position" value="" disabled class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Level</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="designation_level_id" id="designation_level_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Grade</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="grade" id="grade" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Company</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="company_name" id="company_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Location</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="location_name" id="location_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Division</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="division_name" id="division_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Department</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="department_name" id="department_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Section</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="section_name" id="section_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Sub-Section</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="subsection_name" id="subsection_name" value="" disabled/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeRecruitReq.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeRecruitReq.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeRecruitReq.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsEmployeeRecruitReq.pdf()">FORM</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Replacement" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center',border:true" style="padding:2px">
                        <table id="employeerecruitreqreplaceTbl" style="width:100%" >
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
                </div>
            </div>        
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft3'" style="width: 450px;">
                <form id="employeerecruitreqreplaceFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="employee_recruit_req_id" id="employee_recruit_req_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Replacement Of</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_name" id="employee_name" ondblclick="MsEmployeeRecruitReqReplace.openRepEmployeeWindow()" placeholder="Double Click Here" readonly />
                                        <input type="hidden" name="employee_h_r_id" id="employee_h_r_id">
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeRecruitReqReplace.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeRecruitReqReplace.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeRecruitReqReplace.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Job Description" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center',border:true" style="padding:2px">
                        <table id="employeerecruitreqjobTbl" style="width:100%" >
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="80">ID</th>
                                    <th data-options="field:'job_description'" width="250">New Job Description</th>
                                    <th data-options="field:'sort_id'" width="80">Sequence</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>        
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft4'" style="width: 450px;">
                <form id="employeerecruitreqjobFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="employee_recruit_req_id" id="employee_recruit_req_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">New Job Description</div>
                                    <div class="col-sm-7">
                                        <textarea name="job_description" id="job_description"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Sequence</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sort_id" id="sort_id" value="" class="number integer"/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeRecruitReqJob.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeRecruitReqJob.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeRecruitReqJob.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--------------------Employee Budget Search-Window Start------------------>
<div id="employeebudgetsearchwindow" class="easyui-window" title="Employee Budget Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="employeebudgetsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeRecruitReq.searchEmployeeBudget()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="employeebudgetsearchTbl" style="width:700px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'designation_name'" width="100">Designation</th>
                        <th data-options="field:'no_of_post'" width="80">No of<br> Post</th>
                        <th data-options="field:'min_salary'" width="100">Minimum<br> Salary</th>
                        <th data-options="field:'max_salary'" width="100">Maximum<br> Salary</th>
                        <th data-options="field:'designation_level_id'" width="100">Designation<br> Level</th>
                        <th data-options="field:'last_education'" width="120">Last Education</th>
                        <th data-options="field:'professional_education'" width="120">Prof. <br>Education</th>
                        <th data-options="field:'special_qualificaiton'" width="120">Special <br>Qualification</th>
                        <th data-options="field:'room_required_id'" width="80">Room <br>Required</th>
                        <th data-options="field:'desk_required_id'" width="80">Desk <br>Required</th>
                        <th data-options="field:'intercom_required_id'" width="80">Intercom <br>Required</th>
                        <th data-options="field:'computer_required_id'" width="80">Computer <br>Required</th>
                        <th data-options="field:'ups_required_id'" width="80">UPS <br>Required</th>
                        <th data-options="field:'printer_required_id'" width="80">Printer <br>Required</th>
                        <th data-options="field:'cell_phone_required_id'" width="80">Cellphone <br>Required</th>
                        <th data-options="field:'sim_required_id'" width="80">SIM <br>Required</th>
                        <th data-options="field:'network_required_id'" width="80">Network <br>Required</th>
                        <th data-options="field:'transport_required_id'" width="80">Transport <br>Required</th>
                        <th data-options="field:'other_item_required'" width="100">Other Items <br>Required</th>  
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#employeebudgetsearchwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<!--------------------Reporting Supervisor Search-Window Start------------------>
<div id="employeehrsearchwindow" class="easyui-window" title="Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="employeehrsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeRecruitReq.searchEmployeeHr()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="employeehrsearchTbl" style="width:700px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'employee_name'" width="100">Name</th>
                        <th data-options="field:'code'" width="100">User Given Code</th>
                        <th data-options="field:'designation_id'" width="100">Designation</th>
                        <th data-options="field:'department_id'" width="100">Department</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'last_education'" width="100">Last Education</th>
                        <th data-options="field:'experience'" width="100">Experience</th>
                        <th data-options="field:'address'" width="100">Address</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#employeehrsearchwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{--Replace Employee Search --}}
<div id="replaceemployeesearchwindow" class="easyui-window" title="Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="replaceemployeesearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeRecruitReqReplace.searchReplaceEmployee()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="replaceemployeesearchTbl" style="width:700px">
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
                        <th data-options="field:'last_education'" width="100">Last Education</th>
                        <th data-options="field:'experience'" width="100">Experience</th>
                        <th data-options="field:'address'" width="100">Address</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#replaceemployeesearchwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

{{-- //////////////////////// --}}
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsAllEmployeeRecruitReqController.js"></script>
<script>
(function(){
    $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
     });

    $('#replaceemployeesearchFrm [id="department_id"]').combobox();
	$('#replaceemployeesearchFrm [id="designation_id"]').combobox();
	$('#employeebudgetsearchFrm [id="department_id"]').combobox();
	$('#employeebudgetsearchFrm [id="designation_id"]').combobox();
	$('#employeehrsearchFrm [id="department_id"]').combobox();
	$('#employeehrsearchFrm [id="designation_id"]').combobox();
    
})(jQuery);

</script>