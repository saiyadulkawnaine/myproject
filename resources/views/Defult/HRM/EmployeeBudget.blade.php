<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="employeeBudgettabs">
    <div title="Cost Center Hirer Key" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center',border:true" style="padding:2px">
                        <table id="employeebudgetTbl" style="width:100%" >
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
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft2'" style="width: 350px;">
                <form id="employeebudgetFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Location</div>
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
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeBudget.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeBudget.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeBudget.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Position Budget" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center',border:true" style="padding:2px">
                        <table id="employeebudgetpositionTbl" style="width:100%" >
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
                </div>
            </div>        
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft3'" style="width: 450px;">
                <form id="employeebudgetpositionFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="employee_budget_id" id="employee_budget_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Designation</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="designation_name" id="designation_name" ondblclick="MsEmployeeBudgetPosition.searchDesignation()" placeholder="Double Click Here" readonly />
                                        <input type="hidden" name="designation_id" id="designation_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Grade </div>
                                    <div class="col-sm-7"><input type="text" name="grade" id="grade" disabled/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">No of Post</div>
                                    <div class="col-sm-7"><input type="text" name="no_of_post" id="no_of_post" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Minimum Salary</div>
                                    <div class="col-sm-7"><input type="text" name="min_salary" id="min_salary" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Maximum Salary</div>
                                    <div class="col-sm-7"><input type="text" name="max_salary" id="max_salary" class="number integer"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Designation Level</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('designation_level_id', $designationlevel,'',array('id'=>'designation_level_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Last Education</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="last_education" id="last_education" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Professional Education</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="professional_education" id="professional_education" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Special Qualification</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="special_qualificaiton" id="special_qualificaiton" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Experience </div>
                                    <div class="col-sm-7"><input type="text" name="experience" id="experience" /></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Room Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('room_required_id', $roomreq,'',array('id'=>'room_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">Desk Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('desk_required_id', $yesno,'',array('id'=>'desk_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">Intercom Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('intercom_required_id', $yesno,'',array('id'=>'intercom_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">Computer Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('computer_required_id', $computer,'',array('id'=>'computer_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">UPS Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('ups_required_id', $yesno,'',array('id'=>'ups_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">Printer Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('printer_required_id', $yesno,'',array('id'=>'printer_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">Cellphone Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('cell_phone_required_id', $yesno,'',array('id'=>'cell_phone_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">SIM Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('sim_required_id', $yesno,'',array('id'=>'sim_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">Network Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('network_required_id', $yesno,'',array('id'=>'network_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">Transport Required</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('transport_required_id', $transportreq,'',array('id'=>'transport_required_id')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-5">Other Items Required</div>
                                    <div class="col-sm-7">
                                        <textarea name="other_item_required" id="other_item_required"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeBudgetPosition.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeBudgetPosition.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeBudgetPosition.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Designation Search --}}
<div id="opendesignationwindow" class="easyui-window" title="Designation Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:600px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="designationsearchTbl">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'name'" width="200">Name</th>
                        <th data-options="field:'designation_level'" width="100">Designation Level</th>
                        <th data-options="field:'employee_category'" width="140">Employee Category</th>
                        <th data-options="field:'grade'" width="60">Grade</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opendesignationwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsAllEmployeeBudgetController.js"></script>
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

	$('#employeebudgetFrm [id="department_id"]').combobox();
    $('#employeebudgetFrm [id="subsection_id"]').combobox();
    $('#employeebudgetFrm [id="section_id"]').combobox();
    
})(jQuery);

</script>