        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#statusTblFt'" style="padding:2px;width:600px">
                <table id="employeehrstatusTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'employee_h_r_id'" width="80">ERP ID</th>
                            <th data-options="field:'employee_name'" width="100">Employee</th>
                            <th data-options="field:'designation_name'" width="100">Designation</th>
                            <th data-options="field:'grade'" width="100">Grade</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'location_name'" width="100">Location</th>
                            <th data-options="field:'division_name'" width="100">Division</th>
                            <th data-options="field:'department_name'" width="100">Department</th>
                            <th data-options="field:'section_name'" width="80">Section</th>
                            <th data-options="field:'subsection_name'" width="80">Subsection</th>
                            <th data-options="field:'status'" width="60">Status</th>
                            <th data-options="field:'status_date'" width="80">Status Date</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>
                            <th data-options="field:'approved_by'" width="120">Approved By</th>
                            <th data-options="field:'approved_at'" width="120">Approved At</th>
                            <th data-options="field:'api_status'" width="60">API Status</th>
                        </tr>
                    </thead>
                </table>
                <div id="statusTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Status Date: <input type="text" name="date_from" id="date_from" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="date_to" id="date_to" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsEmployeeHRStatus.searchEmployeeStatus()">Show</a>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Employee Status',footer:'#employeehrstatusFrmFT'" style="width: 400px; padding:2px">
                <form id="employeehrstatusFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row" style="display:none;">
                                    <input type="hidden" name="id" id="id" value=""/>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">ERP ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_h_r_id" id="employee_h_r_id" ondblclick="MsEmployeeHRStatus.openEmpHrWindow()" placeholder=" Click Here" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">ID Card No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="code" id="code" value="" disabled/>
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
                                    <div class="col-sm-5 req-text">Grade</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="grade" id="grade" value="" disabled/>
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
                                    <div class="col-sm-5 req-text">Report To</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="report_to_name" id="report_to_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Status</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="status_name" id="status_name" value="" disabled/>
                                    </div>
                                </div>
                            </code>
                                <code>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">Status</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('status_id', $status,'',array('id'=>'status_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Status Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="status_date" id="status_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">In-Active For</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('logistics_status_id', $hrinactivefor,'',array('id'=>'logistics_status_id')) !!}
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
                    <div id="employeehrstatusFrmFT" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeHRStatus.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHRStatus.resetForm()" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHRStatus.remove()" >Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeHRStatus.sendtoapi()" >Sent To API</a>
                    </div>
                </form>
            </div>
        </div>
    

<div id="openemphrstatuswindow" class="easyui-window" title="Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="employhrsearchstatusFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeHRStatus.searchEmployeeHr()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="emphrsearchstatusTbl" style="width:700px">
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
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email</th>
                        <th data-options="field:'status_name'" width="120">Status</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openemphrstatuswindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsEmployeeHRStatusController.js"></script>
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
         