<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Employee Basic Information'" style="padding:2px">
     <table id="employeeinformationTbl" style="width:100%">
         <thead>
             <tr>
                 <th data-options="field:'id',halign:'center'" width="100" halign:'center'  align="center">ERP ID</th>
                 <th data-options="field:'company_id',halign:'center'" width="100">Company</th>
                 <th data-options="field:'name',halign:'center'" width="100">Name</th>
                 <th data-options="field:'code',halign:'center'" width="100">User Given Code</th>
                 <th data-options="field:'designation_id',halign:'center'" width="100">Designation</th>
                 <th data-options="field:'location_name',halign:'center'" width="100">Location</th>
                 <th data-options="field:'division_name',halign:'center'" width="100">Division</th>
                 <th data-options="field:'department_id',halign:'center'" width="80">Department</th>
                 <th data-options="field:'section_name',halign:'center'" width="80">Section</th>
                 <th data-options="field:'subsection_name',halign:'center'" width="80">Sub-Section</th>
                 <th data-options="field:'grade',halign:'center'" width="60">Grade</th>
                 <th data-options="field:'date_of_join',halign:'center'" width="80">Date of Join</th>
         
                 <th data-options="field:'date_of_birth',halign:'center'" width="80">Date of Birth</th>
                 <th data-options="field:'contact',halign:'center'" width="80">Phone No</th>
                 <th data-options="field:'email',halign:'center'" width="100">Email Address</th>
                 <th data-options="field:'national_id',halign:'center'" width="100">National ID</th>
                 <th data-options="field:'address',halign:'center'" width="120">Address</th>
                 <th data-options="field:'tin',halign:'center'" width="80">Tin</th>
                 <th data-options="field:'last_education',halign:'center'" width="100">Last Education</th>
                 <th data-options="field:'experience',halign:'center'" width="100" align="right">Experience</th>
         
             </tr>
         </thead>
     </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
    <form id="employeeinformationFrm">
        <div id="container">
             <div id="body">
               <code>
                 <div class="row">
                     <div class="col-sm-4 req-text">Company</div>
                     <div class="col-sm-8">
                       {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}  
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4">Department</div>
                     <div class="col-sm-8">
                         {!! Form::select('department_id', $department,'',array('id'=>'department_id')) !!}
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4 req-text">Name </div>
                     <div class="col-sm-8">
                         <input type="text" name="name" id="name" />
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4 req-text">Code </div>
                     <div class="col-sm-8">
                         <input type="text" name="code" id="code" value="" />
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4 req-text">Date of Join </div>
                     <div class="col-sm-4" style="padding-right:0px">
                     <input type="text" name="date_from" id="date_from" class="datepicker" placeholder=" From" />
                     </div>
                     <div class="col-sm-4" style="padding-left:0px">
                     <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder=" To" />
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4">Employee Type</div>
                     <div class="col-sm-8">
                         {!! Form::select('employee_type_id', $employeetype,'1',array('id'=>'employee_type_id')) !!}
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4">Status  </div>
                     <div class="col-sm-8">
                         {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                     </div>
                 </div>
              </code>
           </div>
        </div>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeInformation.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeInformation.resetForm('employeeinformationFrm')" >Reset</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="save" onClick="MsEmployeeInformation.showExcel()" >XL</a>
        </div>
    
      </form>
    </div>
 </div>
 
 <script type="text/javascript" src="<?php echo url('/');?>/js/report/HRM/MsEmployeeInformationController.js"></script>
 <script>
     $(".datepicker" ).datepicker({
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
     });
 </script>