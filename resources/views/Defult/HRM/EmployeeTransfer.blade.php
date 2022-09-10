<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="emptransfertabs">
 <div title="Employee Reference" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true" style="padding:2px">
    <table id="employeetransferTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'employee_h_r_id'" width="80">ERPID</th>
       <th data-options="field:'employee_name'" width="100">Employee</th>
       <th data-options="field:'designation_name'" width="100">Designation</th>
       <th data-options="field:'transfer_date'" width="100">Transfer Date</th>
       <th data-options="field:'code'" width="100">ID Card No</th>
       <th data-options="field:'company_name'" width="100">Company</th>
       <th data-options="field:'location_name'" width="100">Location</th>
       <th data-options="field:'division_name'" width="100">Division</th>
       <th data-options="field:'department_name'" width="100">Department</th>
       <th data-options="field:'section_name'" width="80">Section</th>
       <th data-options="field:'subsection_name'" width="80">Subsection</th>
       <th data-options="field:'report_to_name'" width="80">Report To</th>
       <th data-options="field:'remarks'" width="120">Remarks</th>
       <th data-options="field:'old_code'" width="100">Old ID Card No</th>
       <th data-options="field:'old_company_name'" width="100">Old Company</th>
       <th data-options="field:'old_location_name'" width="100">Old Location</th>
       <th data-options="field:'old_division_name'" width="100">Old Division</th>
       <th data-options="field:'old_department_name'" width="100">Old Department</th>
       <th data-options="field:'old_section_name'" width="80">Old Section</th>
       <th data-options="field:'old_subsection_name'" width="80">Old Subsection</th>
       <th data-options="field:'old_report_to_name'" width="80">Old Report To</th>
       <th data-options="field:'api_status'" width="80">API Status</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Employee Transfer Information',footer:'#ft2'"
    style="width: 450px;">
    <form id="employeetransferFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">ERP ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_h_r_id" id="employee_h_r_id" ondblclick="MsEmployeeTransfer.openEmpHrWindow()" placeholder=" Click Here" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Employee</div>
                                    <div class="col-sm-7"><input type="text" name="employee_name" id="employee_name" value="" disabled/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Designation</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="designation_name" id="designation_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">ID Card No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="code" id="code" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
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
                                    <div class="col-sm-5">Sub Section</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="subsection_name" id="subsection_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Report To</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="report_to_name" id="report_to_name"  disabled/>
                                    </div>
                                </div>
                            </code>
       <code>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="transfer_date" id="transfer_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">New Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">New ID Card</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="code" id="code" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">New Location</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">New Division</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('division_id', $division,'',array('id'=>'division_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">New Department</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">New Section</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('section_id', $section,'',array('id'=>'section_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">New Sub Section</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('subsection_id', $subsection,'',array('id'=>'subsection_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Report To</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="new_report_to_name" id="new_report_to_name"  placeholder=" Double Click To Select" ondblclick="MsEmployeeTransfer.openReportEmpHrWindow()" />
                                        <input type="hidden" name="report_to_id" id="report_to_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeTransfer.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeTransfer.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeTransfer.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Job Description" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
    <table id="employeetransferjobTbl" style="width:100%">
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
    <form id="employeetransferjobFrm">
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
                                        <textarea name="job_description" id="job_description" cols="30" rows="6"></textarea>
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
     <div id="HRMJDft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeTransferJob.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeTransferJob.resetForm()">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeTransferJob.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>

<!--------------------Employee Search-Window Start------------------>
<div id="openemphrwindow" class="easyui-window" title="Employee Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="employhrsearchFrm">
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
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsEmployeeTransfer.searchEmployeeHr()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="emphrsearchTbl" style="width:700px">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'employee_name'" width="100">Name</th>
      <th data-options="field:'code'" width="100">User Given Code</th>
      <th data-options="field:'designation_name'" width="100">Designation</th>
      <th data-options="field:'company_name'" width="100">Company</th>
      <th data-options="field:'location_name'" width="100">Location</th>
      <th data-options="field:'division_name'" width="100">Division</th>
      <th data-options="field:'department_name'" width="100">Department</th>
      <th data-options="field:'section_name'" width="100">Section</th>
      <th data-options="field:'subsection_name'" width="100">Sub-Section</th>
      <th data-options="field:'report_to_name'" width="100">Report To</th>

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
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openemphrwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
<!--------------------Report To Employee Search-Window Start------------------>
<div id="employeetoreportwindow" class="easyui-window" title="Report To Employee Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="employeetoreportFrm">
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
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsEmployeeTransfer.searchReportEmployee()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="employeetoreportTbl" style="width:700px">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'employee_name'" width="100">Name</th>
      <th data-options="field:'code'" width="100">User Given Code</th>
      <th data-options="field:'designation_id'" width="100">Designation</th>
      <th data-options="field:'department_id'" width="100">Department</th>
      <th data-options="field:'company_id'" width="100">Company</th>
      <th data-options="field:'section_id'" width="100">Section</th>
      <th data-options="field:'contact'" width="100">Phone No</th>
      <th data-options="field:'email'" width="120">Email Address</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#employeetoreportwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsAllEmployeeTransferController.js"></script>
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
    
})(jQuery);

</script>