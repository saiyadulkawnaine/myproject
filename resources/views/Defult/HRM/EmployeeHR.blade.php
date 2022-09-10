<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="hrEmployeetabs">
    <div title="Employee Information" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center',border:true" style="padding:2px">
                        <table id="employeehrTbl" style="width:100%" >
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="40">ID</th>
                                    <th data-options="field:'name'" width="100">Name</th>
                                    <th data-options="field:'user'" width="100">User</th>
                                    <th data-options="field:'code'" width="100">User Given<br/> Code</th>
                                    <th data-options="field:'designation_name'" width="100">Designation</th>
                                    <th data-options="field:'company_name'" width="100">Company</th>
                                    <th data-options="field:'location_name'" width="100">Location</th>
                                    <th data-options="field:'division_name'" width="100">Division</th>
                                    <th data-options="field:'department_name'" width="100">Department</th>
                                    <th data-options="field:'section_name'" width="100">Section</th>
                                    <th data-options="field:'subsection_name'" width="100">SUb Section</th>

                                    <th data-options="field:'grade'" width="50">Grade</th>
                                    <th data-options="field:'date_of_join'" width="80">Date of Join</th>
                                    <th data-options="field:'date_of_birth'" width="80">Date of Birth</th>
                                    <th data-options="field:'contact'" width="100">Phone No</th>
                                    <th data-options="field:'email'" width="120">Email Address</th>
                                    <th data-options="field:'national_id'" width="100">National ID</th>
                                    <th data-options="field:'address'" width="120">Address</th>
                                    <th data-options="field:'salary'" width="80" align="right">Salary</th>
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
                    <div data-options="region:'north',split:true" style="height:85px">
                        <div class="easyui-layout" data-options="fit:true">
                            <div id="body">
                                <code>
                                    <form id="employhrsearchFrm">
                                        <div class="row">
                                            <div class="col-sm-1">Company</div>
                                            <div class="col-sm-2">
                                                {!! Form::select('company_id', $company,'',array('id'=>'company_id','style'=>'width: 100%; border-radius:2px')) !!}
                                            </div>
                                            <div class="col-sm-1">Department</div>
                                            <div class="col-sm-2">
                                                {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                                            </div>
                                            <div class="col-sm-1">Designation </div>
                                            <div class="col-sm-2">
                                                {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                                            </div>
                                            <div class="col-sm-1">Location </div>
                                            <div class="col-sm-2">
                                                {!! Form::select('location_id', $location,'',array('id'=>'location_id','style'=>'width: 100%; border-radius:2px')) !!}
                                            </div>
                                        </div>
                                        
                                    </form>
                                </code>
                            </div>
                            <p class="footer">
                                <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeHR.searchshowGrid()">Search</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft2'" style="width: 450px;">
                <form id="employeehrFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Name </div>
                                    <div class="col-sm-7"><input type="text" name="name" id="name" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">User ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="user_name" id="user_name" onclick="MsEmployeeHR.openUserWindow()" placeholder=" Click Here" />
                                        <input type="hidden" name="user_id" id="user_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Code </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="code" id="code" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Designation</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Location</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Division</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('division_id', $division,'',array('id'=>'division_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Department</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Section</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('section_id', $section,'',array('id'=>'section_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Sub Section</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('subsection_id', $subsection,'',array('id'=>'subsection_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Grade </div>
                                    <div class="col-sm-7"><input type="text" name="grade" id="grade" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Date of Birth</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="date_of_birth" id="date_of_birth" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Gender</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('gender_id', $gender,'',array('id'=>'gender_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Date of Join</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="date_of_join" id="date_of_join" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Probation Period(Days)</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="probation_days" id="probation_days" class="number integer" placeholder="No of days" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Report To</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="report_to_name" id="report_to_name"  placeholder=" Double Click To Select" ondblclick="MsEmployeeHR.openEmpHrWindow()" />
                                        <input type="hidden" name="report_to_id" id="report_to_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">National ID</div>
                                    <div class="col-sm-7"><input type="text" name="national_id" id="national_id" /></div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Salary</div>
                                    <div class="col-sm-7"><input type="text" name="salary" id="salary" class="number integer" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Compliance Salary</div>
                                    <div class="col-sm-7"><input type="text" name="compliance_salary" id="compliance_salary"  class="number integer" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Advance Applicable</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('is_advanced_applicable', $yesno,'',array('id'=>'is_advanced_applicable')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Last Education </div>
                                    <div class="col-sm-7"><input type="text" name="last_education" id="last_education" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Employee Type</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('employee_type_id', $employeetype,'1',array('id'=>'employee_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Experience </div>
                                    <div class="col-sm-7"><input type="text" name="experience" id="experience" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Tin </div>
                                    <div class="col-sm-7"><input type="text" name="tin" id="tin" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Phone</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="contact" id="contact" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Email</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="email" id="email" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Address</div>
                                    <div class="col-sm-7">
                                        <textarea name="address" id="address"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Religion</div>
                                    <div class="col-sm-7">
                                    <input type="text" name="religion" id="religion" />
                                    </div>
                                </div>
                                <div class="row middle" style="display: none">
                                    <div class="col-sm-5 req-text">Status</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('status_id', $status,'1',array('id'=>'status_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle" style="display: none">
                                    <div class="col-sm-5 req-text">In-Active Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="inactive_date" id="inactive_date" class="datepicker" placeholder="yyyy-mm-dd" disabled="disabled" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Separation Clause</div>
                                    <div class="col-sm-7">
                                    <textarea name="seperation_clause" id="seperation_clause" cols="30" rows="10">a)  Resignation: In case of your resignation from the service of the company, you will have to give one-month notice or surrender full salary in lieu thereof.
                                    b) Termination: During probation period, either party can terminate employment at any time without giving any notice. After completion of probation period the company may terminate the employment by giving one month’s notice or one month’s payment in lieu thereof. Upon termination of your employment, you will return to the company all papers, documents, Computers, and other property which may at that time be in your possession, relating to the Business or affairs of the company or any of the associates or branches and you will not retain any copy of extracts thereof, in any form.
                                    </textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Transport </div>
                                    <div class="col-sm-7">
                                        <textarea name="transport" id="transport"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Group Insurance </div>
                                    <div class="col-sm-7">
                                        <textarea name="group_insurance" id="group_insurance"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Utility Bill </div>
                                    <div class="col-sm-7">
                                        <textarea name="utility_bill" id="utility_bill"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Allowance </div>
                                    <div class="col-sm-7">
                                        <textarea name="allowance" id="allowance"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Signatory</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="signatory_name" id="signatory_name"  placeholder=" Double Click To Select" ondblclick="MsEmployeeHR.openSignatoryWindow()" />
                                        <input type="hidden" name="signatory_id" id="signatory_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Appointment Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="appointment_date" id="appointment_date" class="datepicker" placeholder="yyyy-mm-dd"/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsEmployeeHR.ndapdf()">NDA</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsEmployeeHR.pdf()">APP.Letter</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeHR.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHR.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHR.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Job Description" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
                <table id="employeehrjobTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'job_description'" width="200">Job</th>
                            <th data-options="field:'sort_id'" width="70">Sequence</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'List',footer:'#HRMJDft'" style="width: 400px; padding:2px">
                <form id="employeehrjobFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row" style="display:none;">
                                    <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" />
                                    <input type="hidden" name="id" id="id" value=""/>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Job Description </div>
                                    <div class="col-sm-8">
                                        <textarea name="job_description" id="job_description" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="row middle"> 
                                    <div class="col-sm-4 req-text">Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" value=""/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="HRMJDft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeHRJob.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHRJob.resetForm()" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHRJob.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Leave" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
                <table id="employeehrleaveTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'leave_description'" width="200">Job</th>
                            <th data-options="field:'sort_id'" width="60">Sequence</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'List',footer:'#HRMLVft'" style="width: 400px; padding:2px">
                <form id="employeehrleaveFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row" style="display:none;">
                                    <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" />
                                    <input type="hidden" name="id" id="id" value=""/>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Leave Description </div>
                                    <div class="col-sm-8">
                                        <textarea name="leave_description" id="leave_description" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="row middle"> 
                                    <div class="col-sm-4 req-text">Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" value=""/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="HRMLVft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeHRLeave.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHRLeave.resetForm()" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHRLeave.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- USER Search --}}
<div id="openuserwindow" class="easyui-window" title="User Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:450px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="usersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">User Name</div>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="name">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Email </div>
                                <div class="col-sm-8">
                                    <input type="text" name="email" id="email">
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeHR.searchUser()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="usersearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'user_name'" width="100">User Name</th>
                        <th data-options="field:'email'" width="100">Email</th>
                        <th data-options="field:'role_id'" width="100">Role</th> 
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openuserwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<!--------------------Employee Search-Window Start------------------>
<div id="openemployhrwindow" class="easyui-window" title="Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="employtoreportFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeHR.searchEmployeeHr()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="employhrsearchTbl" style="width:700px">
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openemployhrwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<!--------------------Signatory Search-Window Start------------------>
<div id="opensignatorywindow" class="easyui-window" title="Signatory Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="signatorysearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeHR.searchSignatory()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="signatorysearchTbl" style="width:700px">
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opensignatorywindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsAllEmployeeHRController.js"></script>
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

	$('#employeehrFrm [id="department_id"]').combobox();
    $('#employeehrFrm [id="designation_id"]').combobox();
    $('#employeehrFrm [id="subsection_id"]').combobox();
    $('#employeehrFrm [id="section_id"]').combobox();
    $('#employtoreportFrm [id="department_id"]').combobox();
    $('#employtoreportFrm [id="designation_id"]').combobox();
    $('#signatorysearchFrm [id="department_id"]').combobox();
    $('#signatorysearchFrm [id="designation_id"]').combobox();
    $('#employhrsearchFrm [id="department_id"]').combobox();
    $('#employhrsearchFrm [id="designation_id"]').combobox();
    
})(jQuery);

</script>
         