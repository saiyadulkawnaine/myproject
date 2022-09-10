<div class="easyui-layout animated rollIn"  data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="dailyattencereportTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'name',halign:'center'" width="100">Name</th>
                <th data-options="field:'enlisted',halign:'center'" width="100" align="right">Enlisted</th>
                <th data-options="field:'present',halign:'center'" width="80" align="right">Present</th>
                <th data-options="field:'leave',halign:'center'" width="80" align="right">Leave</th>
                <th data-options="field:'absent',halign:'center'" width="80" align="right">Absent</th>
                <th data-options="field:'dept',halign:'center'" align="left" width="80" formatter="MsDailyAttenceReport.formatdept">Department</th>
                <th data-options="field:'sect',halign:'center'" align="left" width="60" formatter="MsDailyAttenceReport.formatsect">Section</th>
                <th data-options="field:'subsect',halign:'center'" align="left" width="80" formatter="MsDailyAttenceReport.formatsubsect">Sub-Section</th>
                <th data-options="field:'degn',halign:'center'" align="left" width="80" formatter="MsDailyAttenceReport.formatdegn">Designation</th>
                <th data-options="field:'emp',halign:'center'" align="left" width="80" formatter="MsDailyAttenceReport.formatempl">Employee</th>
            </tr>
        </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
   <form id="dailyattencereportFrm">
       <div id="container">
            <div id="body">
              <code>
                <div class="row">
                    <div class="col-sm-4 req-text">Date</div>
                    <div class="col-sm-8">
                    <input type="text" name="work_date" id="work_date" class="datepicker"  placeholder="To" value="<?php echo $work_date; ?>" />
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Company</div>
                    <div class="col-sm-8">
                      {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}  
                    </div>
                </div>
                
                
             </code>
          </div>
       </div>
       <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
           <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDailyAttenceReport.get()">Show</a>
           <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDailyAttenceReport.resetForm('dailyattencereportFrm')" >Reset</a>
       </div>
     </form>
   </div>
</div>

<div id="dailyattencereportdeptwindow" class="easyui-window" title="Department" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dailyattencereportdeptTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'name',halign:'center'" width="100">Department</th>
                <th data-options="field:'budgeted',halign:'center'" width="100" align="right">Budgeted</th>
                <th data-options="field:'enlisted',halign:'center'" width="100" align="right">Enlisted</th>
                <th data-options="field:'present',halign:'center'" width="80" align="right">Present</th>
                <th data-options="field:'leave',halign:'center'" width="80" align="right">Leave</th>
                <th data-options="field:'absent',halign:'center'" width="80" align="right">Absent</th>
            </tr>
        </thead>
    </table>
</div>

<div id="dailyattencereportsectwindow" class="easyui-window" title="Section" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dailyattencereportsectTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'name',halign:'center'" width="100">Section</th>
                <th data-options="field:'budgeted',halign:'center'" width="100" align="right">Budgeted</th>
                <th data-options="field:'enlisted',halign:'center'" width="100" align="right">Enlisted</th>
                <th data-options="field:'present',halign:'center'" width="80" align="right">Present</th>
                <th data-options="field:'leave',halign:'center'" width="80" align="right">Leave</th>
                <th data-options="field:'absent',halign:'center'" width="80" align="right">Absent</th>
            </tr>
        </thead>
    </table>
</div>

<div id="dailyattencereportsubsectwindow" class="easyui-window" title="Sub-Section" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dailyattencereportsubsectTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'name',halign:'center'" width="100">Sub-Section</th>
                <th data-options="field:'budgeted',halign:'center'" width="100" align="right">Budgeted</th>
                <th data-options="field:'enlisted',halign:'center'" width="100" align="right">Enlisted</th>
                <th data-options="field:'present',halign:'center'" width="80" align="right">Present</th>
                <th data-options="field:'leave',halign:'center'" width="80" align="right">Leave</th>
                <th data-options="field:'absent',halign:'center'" width="80" align="right">Absent</th>
            </tr>
        </thead>
    </table>
</div>

<div id="dailyattencereportdegnwindow" class="easyui-window" title="Designation" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dailyattencereportdegnTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'name',halign:'center'" width="100">Designation</th>
                <th data-options="field:'budgeted',halign:'center'" width="100" align="right">Budgeted</th>
                <th data-options="field:'enlisted',halign:'center'" width="100" align="right">Enlisted</th>
                <th data-options="field:'present',halign:'center'" width="80" align="right">Present</th>
                <th data-options="field:'leave',halign:'center'" width="80" align="right">Leave</th>
                <th data-options="field:'absent',halign:'center'" width="80" align="right">Absent</th>
            </tr>
        </thead>
    </table>
</div>

<div id="dailyattencereportemplwindow" class="easyui-window" title="Employee" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dailyattencereportemplTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id',halign:'center'" width="100" align="center">ID</th>
                <th data-options="field:'company_name',halign:'center'" width="100">Company</th>
                <th data-options="field:'name',halign:'center'" width="100">Name</th>
                <th data-options="field:'designation_name',halign:'center'" width="100">Designation</th>
                <th data-options="field:'location_name',halign:'center'" width="100">Location</th>
                <th data-options="field:'division_name',halign:'center'" width="100">Division</th>
                <th data-options="field:'department_name',halign:'center'" width="80">Department</th>
                <th data-options="field:'section_name',halign:'center'" width="80">Section</th>
                <th data-options="field:'subsection_name',halign:'center'" width="80">Sub-Section</th>
                <th data-options="field:'date_of_join',halign:'center'" width="80" align="center">Date of Join</th>
                <th data-options="field:'in_time',halign:'center'" width="80" align="center">In Time</th>
                <th data-options="field:'present',halign:'center'" width="80" align="center">Present</th>
                <th data-options="field:'leave',halign:'center'" width="80" align="center">Leave</th>
                <th data-options="field:'absent',halign:'center'" width="80" align="center">Absent</th>
                <th data-options="field:'leave_counter',halign:'center'" width="80" align="right">Leave No</th>
                <th data-options="field:'absent_counter',halign:'center'" width="80" align="right">Absent No</th>
            </tr>
        </thead>
    </table>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/report/HRM/MsDailyAttendenceReportController.js"></script>
<script>

    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>