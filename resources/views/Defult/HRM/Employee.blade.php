<div class="easyui-layout animated rollIn" data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="employeeTbl" style="width:100%">
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
    <div data-options="region:'west',border:true,title:'Add New Employee',footer:'#ft2'" style="width: 350px; padding:2px">
        <form id="employeeFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle" style="display:none">
                            <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Name </div>
                            <div class="col-sm-8"><input type="text" name="name" id="name" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Code </div>
                            <div class="col-sm-8">
                                <input type="text" name="code" id="code" value="" class="number integer"/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Designation</div>
                            <div class="col-sm-8">
                                {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Department</div>
                            <div class="col-sm-8">
                                {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Grade </div>
                            <div class="col-sm-8"><input type="text" name="grade" id="grade" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Date of Birth </div>
                            <div class="col-sm-8">
                                <input type="text" name="date_of_birth" id="date_of_birth" class="datepicker" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Date of Join</div>
                            <div class="col-sm-8">
                                <input type="text" name="date_of_join" id="date_of_join" class="datepicker" />
                            </div>
                        </div>      
                        <div class="row middle">
                            <div class="col-sm-4">National ID </div>
                            <div class="col-sm-8"><input type="text" name="national_id" id="national_id" /></div>
                        </div>
                        
                        <div class="row middle">
                            <div class="col-sm-4">Salary </div>
                            <div class="col-sm-8"><input type="text" name="salary" id="salary" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Last Education </div>
                            <div class="col-sm-8"><input type="text" name="last_education" id="last_education" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Experience </div>
                            <div class="col-sm-8"><input type="text" name="experience" id="experience" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">TIN </div>
                            <div class="col-sm-8"><input type="text" name="tin" id="tin" /></div>
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
                        <div class="row middle">
                            <div class="col-sm-4">Address </div>
                            <div class="col-sm-8">
                            <textarea name="address" id="address"></textarea>
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
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployee.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployee.resetForm()">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployee.remove()">Delete</a>
            </div>

        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsEmployeeController.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
      });

    $('#employeeFrm [id="department_id"]').combobox();
    $('#employeeFrm [id="designation_id"]').combobox(); 

</script>
