<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="EmployeeMovetabs">
    <div title="Employee Information" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
                <table id="employeemovementTbl" style="width:100%" >
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'post_date'" width="80">Post Date</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'code'" width="100">User Given<br/> Code</th>
                            <th data-options="field:'designation_id'" width="100">Designation</th>
                            <th data-options="field:'department_id'" width="100">Department</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'contact'" width="100">Phone No</th>     
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft2'" style="width: 350px;">
                <form id="employeemovementFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Employee</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_name" id="employee_name" onclick="MsEmployeeMovement.openEmpWindow()" placeholder=" Click Here" value="" />
                                        <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Post Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="post_date" id="post_date" value="{{ date('Y-m-d') }}" class="datepicker" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Company</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="company_id" id="company_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Location</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="location_id" id="location_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Designation</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="designation_id" id="designation_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Department</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="department_id" id="department_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">ERP ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="employee_h_r_id" id="employee_h_r_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">ID Card </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="code" id="code" value="" disabled/>
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-5">Phone</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="contact" id="contact" value="" disabled/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeMovement.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeMovement.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeMovement.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeMovement.pdf()">Ticket</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Detail" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
                <table id="employeemovementdtlTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'out_date'" width="200">Out Date</th>
                            <th data-options="field:'out_time'" width="60">Out Time</th>
                            <th data-options="field:'return_date'" width="60">Return Date</th>
                            <th data-options="field:'return_time'" width="60">Return Time</th>
                            <th data-options="field:'purpose_id'" width="100">Purpose</th>
                            <th data-options="field:'work_detail'" width="150">Work Details</th>
                            <th data-options="field:'destination'" width="150">Destination</th>
                            <th data-options="field:'transport_mode_id'" width="150">Transport</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'List',footer:'#HRMLVft'" style="width: 400px; padding:2px">
                <form id="employeemovementdtlFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row" style="display:none;">
                                    <input type="hidden" name="employee_movement_id" id="employee_movement_id" value="" />
                                    <input type="hidden" name="id" id="id" value=""/>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Out date time</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="out_date" id="out_date" value="{{ date('Y-m-d') }}" placeholder="date" class="datepicker"/>
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="out_time" id="out_time" value="" placeholder="time"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Return date time</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="return_date" id="return_date" class="datepicker" placeholder="yyyy-mm-dd" value="" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px;">
                                        <input type="text" name="return_time" id="return_time" placeholder="time" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Purpose</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('purpose_id', $purpose,'',array('id'=>'purpose_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Work Details</div>
                                    <div class="col-sm-8">
                                        <textarea name="work_detail" id="work_detail"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Destination</div>
                                    <div class="col-sm-8">
                                        <textarea name="destination" id="destination"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Conveyance</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">DA</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="ta_da_amount" id="ta_da_amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Mode Of Transport</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('transport_mode_id', $transportmode,'',array('id'=>'transport_mode_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="HRMLVft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeMovementDtl.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeMovementDtl.resetForm()" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeMovementDtl.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Employee HR Search Window --}}
<div id="openemployeewindow" class="easyui-window" title="Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsEmployeeMovement.searchEmployeeHr()">Search</a>
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openemployeewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsAllEmployeeMovementController.js"></script>
<script>
(function(){
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });

    $('#employeemovementdtlFrm [id="purpose_id"]').combobox();
    $('#employeemovementdtlFrm [id="transport_mode_id"]').combobox();

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
    $('#out_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

    $('#return_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 2,
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );
})(jQuery);

</script>