<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true" style="padding:2px">
        {{-- <h4 align="center">Out of Office Status(Date:{{$from}} to {{$to}})</h4> --}}
        <table id="employeemovementreportTbl" title="Out of Office Status" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'company_name',halign:'center'" width="100">Company</th>
                    <th data-options="field:'name',halign:'center'" width="100">Employee Name</th>
                    <th data-options="field:'designation_name',halign:'center'" width="100">Designation</th>
                    <th data-options="field:'department_name',halign:'center'" width="80">Department</th>
                    <th data-options="field:'out_date',halign:'center'" width="80">Out Date</th>
                    <th data-options="field:'out_time',halign:'center'" width="80">Out Time</th>
                    <th data-options="field:'return_date',halign:'center'" width="80">Return Date</th>
                    <th data-options="field:'return_time',halign:'center'" width="80">Return Time</th>
                    <th data-options="field:'purpose',halign:'center'" width="120">Purpose</th>
                    <th data-options="field:'work_detail',halign:'center'" width="200">Work Details</th>
                    <th data-options="field:'destination',halign:'center'" width="200">Destination</th>
                    <th data-options="field:'total_out_this_month',halign:'center'" width="70">Total Out<br/> This Month</th>
                    <th data-options="field:'total_out_last_month',halign:'center'" width="70">Total Out<br/> Last Month</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="employeemovementreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Out Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" value="{{date('Y-m-d')}}"/>
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="{{date('Y-m-d')}}"/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Department</div>
                            <div class="col-sm-8">
                                {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Designation</div>
                            <div class="col-sm-8">
                                {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeMovementReport.getDepartment()">DepartmentWise</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeMovementReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeMovementReport.resetForm('employeemovementreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>
<div id="departmentWiseWindow" class="easyui-window" title="Department Wise Employee Movement Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:900px;height:500px;padding:2px;margin:10px;"> 
    <table id="departmentwiseReportTbl">
        <thead>
            <tr>
                <th data-options="field:'department_name'" width="140px">Department</th>
                <th data-options="field:'no_of_employee'" width="100px" formatter="MsEmployeeMovementReport.formatDepEmp">No Of Employee</th>
            </tr>
        </thead>
    </table>  
</div>
<div id="movementdtailWindow" class="easyui-window" title="Department Wise Employee Movement Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;"> 
    <table id="movementdtailTbl">
        <thead>
            <tr>
                <th data-options="field:'employee_name'" width="140px">Department</th>
                <th data-options="field:'out_time'" width="100px">Out Time</th>
            </tr>
        </thead>
    </table>  
</div>

<div id="depemployeeWindow" class="easyui-window" title="Department Wise Employee Details Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:600px;height:500px;padding:2px;"> 
    <table id="depempTbl">
        <thead>
            <tr>
                <th data-options="field:'employee_name',halign:'center'" align="left" width="120">Employee Name</th>
                <th data-options="field:'contact',halign:'center'" align="left" width="120">Contact</th>
                <th data-options="field:'out_date',halign:'center'" align="center" width="100">Out Date</th>
            </tr>
        </thead>
    </table>  
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/HRM/MsEmployeeMovementReportController.js"></script>
<script>
(function(){   
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
    $('#employeemovementreportFrm [id="department_id"]').combobox();
    $('#employeemovementreportFrm [id="designation_id"]').combobox();
    // $(".date_from").datepicker({
    //     dateFormat: 'yy-mm-dd',
    //     changeMonth: true,
    //     changeYear: true,
    //     beforeShowDay: function(date) {

    //         if (date.getDate() == 1) {
    //             return [true, ''];
    //         }
    //         return [false, ''];
    //     }
    // });

    // function LastDayOfMonth(Year, Month) {
    //     return (new Date((new Date(Year, Month + 1, 1)) - 1)).getDate();
    // }

    // $('.date_to').datepicker({
    //     dateFormat: 'yy-mm-dd',
    //     changeMonth: true,
    //     changeYear: true,
    //     beforeShowDay: function(date) {
    //         //getDate() returns the day (0-31)
    //         if (date.getDate() == LastDayOfMonth(date.getFullYear(), date.getMonth())) {
    //             return [true, ''];
    //         }
    //         return [false, ''];
    //     }
    // });
})(jQuery);
</script>