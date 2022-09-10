<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="smssetuptabs">
    <div title="Menu" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Menu'" style="padding:2px">
                <table id="smssetupTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'menu_name'" width="250">Sms Name</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sms Setup',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="smssetupFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Sms Name</div>
                                <div class="col-sm-7">
                                    <input type="hidden" name="id" id="id" value="" />
                                    {!! Form::select('menu_id', $menu,'',array('id'=>'menu_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmsSetup.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmsSetup.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmsSetup.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Sms To " style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#smssetupsmstoFrmFt'" style="width: 400px; padding:2px">
                <form id="smssetupsmstoFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                            <div class="row middle" style="display:none">
                                <input type="hidden" name="id" id="id" value="" />
                                <input type="hidden" name="sms_setup_id" id="sms_setup_id" value="" />
                            </div>
                            <!--Pop Up-->
                            <div class="row middle">
                                <div class="col-sm-5">Employee</div>
                                <div class="col-sm-7">
                                    <input type="hidden" name="employee_h_r_id" id="employee_h_r_id">
                                    <input type="text" name="name" id="name" placeholder="Dobule Click" ondblclick="MsSmsSetupSmsTo.openSmsEmployee()" readonly>
                                </div>
                            </div>
                            </code>
                        </div>
                    </div>
                    <div id="smssetupsmstoFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSmsSetupSmsTo.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmsSetupSmsTo.resetForm('smssetupsmstoFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSmsSetupSmsTo.remove()" >Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Sms To'" style="padding:2px">
                <table id="smssetupsmstoTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'employee_name'" width="200">Employee Name</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- employee search Window --}}
<div id="smssetupsmstoEmployeeWindow" class="easyui-window" title="Employee Search Window"
    data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                    <form id="smssetupsmstoSearchFrm">
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Company</div>
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
                    onClick="MsSmsSetupSmsTo.searchSmsEmployeeGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="smssetupsmstoSearchTbl" style="width:700px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'name'" width="100">Name</th>
                        <th data-options="field:'code'" width="100">User Given Code</th>
                        <th data-options="field:'designation_id'" width="100">Designation</th>
                        <th data-options="field:'department_id'" width="100">Department</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'grade'" width="100">Grade</th>
                        <th data-options="field:'date_of_join'" width="120">Date of Join</th>
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'national_id'" width="100">National ID</th>
                        <th data-options="field:'address'" width="100">Address</th>
                        <th data-options="field:'is_advanced_applicable'" width="100">Advance Applicable</th>
                        <th data-options="field:'last_education'" width="100">Last Education</th>
                        <th data-options="field:'experience'" width="100">Experience</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
            onclick="$('#smssetupsmstoEmployeeWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllSmsSetupController.js"></script>
<script>
   (function(){

      $('#smssetupFrm [id="menu_id"]').combobox();

   })(jQuery);
</script>