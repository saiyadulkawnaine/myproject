<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'General Ledger',footer:'#ft3'" style="padding:2px" id="glcontainer">
    <!-- <table id="glTbl">
        <thead>
        <tr>
        <th data-options="field:'trans_type',halign:'center'" width="70" halign:'center'>Type</th>
        <th data-options="field:'trans_no',halign:'center'" width="70">#</th>
        <th data-options="field:'trans_date',halign:'center'" width="80">Date</th>
        <th data-options="field:'account',halign:'center'" width="100">Account</th>
        <th data-options="field:'cost_center',halign:'center'" width="80">Cost Center</th>
        <th data-options="field:'party_name',halign:'center'" width="80">Person/Item</th>

        <th data-options="field:'amount_debit',halign:'center'" width="100">Debit</th>
        <th data-options="field:'amount_credit',halign:'center'" width="80">Credit</th>
        <th data-options="field:'chld_narration',halign:'center'" width="80">Narration</th>
        </tr>
        </thead>
    </table> -->
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
<form id="glempFrm">
    <div id="container">
         <div id="body">
           <code>

                
                <div class="row">
                    <div class="col-sm-4 req-text">Company </div>
                    <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','onchange'=>'MsGlEmp.getYear(this.value)')) !!}</div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4 req-text">Year </div>
                    <div class="col-sm-8">
                        <select id="acc_year_id" name="acc_year_id" onchange="MsGlEmp.getDateRange(this.value)">
                        </select>
                    </div>
                </div>
                
                
                <div class="row middle">
                    <div class="col-sm-4 req-text">Trans Date </div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">Employee </div>
                    <div class="col-sm-8">
                    	<input type="text" name="employee_name" id="employee_name"  ondblclick="MsGlEmp.openemployeeWindow()" />
                    	<input type="hidden" name="employee_id" id="employee_id"/>
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">Code </div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <input type="text" name="code_from" id="code_from" ondblclick="MsGlEmp.codefromWindow()" placeholder="From:: Double Click" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="code_to" id="code_to"   ondblclick="MsGlEmp.codetoWindow()" placeholder="To:: Double Click" />
                    </div>
                </div>
                
               

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlEmp.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlEmp.pdf()">Pdf</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGlEmp.resetForm()" >Reset</a>
    </div>

  </form>
</div>
</div>

<div id="glemployeesearchWindow" class="easyui-window" title="Search Employee" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
     <table id="glemployeesearchTbl" style="width:100%">
        <thead>
                <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'name'" width="100">Name</th>
                    <th data-options="field:'code'" width="100">User Given Code</th>
                    <th data-options="field:'designation_id'" width="100">Designation</th>
                    <th data-options="field:'department_id'" width="100">Department</th>
                    <th data-options="field:'grade'" width="100">Grade</th>
                    <th data-options="field:'date_of_join'" width="120">Date of Join</th>
                    <th data-options="field:'date_of_birth'" width="120">Date of Birth</th>
                    <th data-options="field:'contact'" width="100">Phone No</th>
                    <th data-options="field:'email'" width="120">Email Address</th>
                    <th data-options="field:'national_id'" width="100">National ID</th>
                    <th data-options="field:'address'" width="100">Address</th>
                    <th data-options="field:'salary'" width="100">Salary</th>
                    <th data-options="field:'last_education'" width="100">Last Education</th>
                    <th data-options="field:'experience'" width="100">Experience</th>
                    <th data-options="field:'tin'" width="100">Tin</th>
                    
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#glemployeesearchft'" style="width:350px; padding:2px">
    <form id="glemployeesearchFrm">
        <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle" style="display:none">
                            <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Name </div>
                            <div class="col-sm-8"><input type="text" name="name" id="name" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Code </div>
                            <div class="col-sm-8">
                                <input type="text" name="code" id="code" value="" />
                            </div>
                        </div>
                      
                         <div class="row middle">
                            <div class="col-sm-4">Phone </div>
                            <div class="col-sm-8">
                            <input type="text" name="contact" id="contact" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Email </div>
                            <div class="col-sm-8">
                            <input type="text" name="email" id="email" />
                            </div>
                        </div>
                        
                       
                    </code>
                </div>
            </div>
            
        <div id="glemployeesearchft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlEmp.employeesearch()">Search</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('glemployeesearchFrm')" >Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlEmp.closeEmployeeWindow('html')">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlEmp.closeEmployeeWindow('pdf')">Pdf</a>


        </div>

        </form>
    </div>
    </div>
</div>

<div id="glempcodefromwindow" class="easyui-window" title="Accounts Code" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table  border="1" id="glempcodefromTbl">
    <thead>
    <tr>
    <th width="70" data-options="field:'code',halign:'center'">A/C Code</th>
    <th width="200" data-options="field:'name',halign:'center'">Head Name</th>
    <th width="100" data-options="field:'root_id',halign:'center'">Report Head</th>
    <th width="100" data-options="field:'sub_group_name',halign:'center'">Sub Group</th>
    <th width="100" data-options="field:'accchartgroup',halign:'center'">Main Group</th>
    <th width="80" data-options="field:'statement_type_id',halign:'center'">Statement Type</th>
    <th width="100" data-options="field:'control_name_id',halign:'center'">Control Name</th>
    <th width="80" data-options="field:'currency_id',halign:'center'">Currency</th>
    <th width="80" data-options="field:'other_type_id',halign:'center'">Other Type</th>
    <th width="60" data-options="field:'normal_balance_id',halign:'center'">Normal Balance</th>
    <th width="60" data-options="field:'status',halign:'center'">Status</th>
    </tr>
    </thead>
    </table>
</div>

<div id="glempcodetowindow" class="easyui-window" title="Accounts Code" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table  border="1" id="glempcodetoTbl">
    <thead>
    <tr>
    <th width="70" data-options="field:'code',halign:'center'">A/C Code</th>
    <th width="200" data-options="field:'name',halign:'center'">Head Name</th>
    <th width="100" data-options="field:'root_id',halign:'center'">Report Head</th>
    <th width="100" data-options="field:'sub_group_name',halign:'center'">Sub Group</th>
    <th width="100" data-options="field:'accchartgroup',halign:'center'">Main Group</th>
    <th width="80" data-options="field:'statement_type_id',halign:'center'">Statement Type</th>
    <th width="100" data-options="field:'control_name_id',halign:'center'">Control Name</th>
    <th width="80" data-options="field:'currency_id',halign:'center'">Currency</th>
    <th width="80" data-options="field:'other_type_id',halign:'center'">Other Type</th>
    <th width="60" data-options="field:'normal_balance_id',halign:'center'">Normal Balance</th>
    <th width="60" data-options="field:'status',halign:'center'">Status</th>
    </tr>
    </thead>
    </table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsGlEmpController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

