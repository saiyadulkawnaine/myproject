<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Employee Inactive Summary'" style="padding:2px">
        <div id="employeeinactivesummerymatrix">
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#Ft2'" style="width:400px; padding:2px">
        <form id="employeeinactivesummeryFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row">
                            <div class="col-sm-4">Company</div>
                            <div class="col-sm-8">
                              {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}  
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Status Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Designation Level</div>
                            <div class="col-sm-8">{!! Form::select('designation_level_id', $designationlevel,'',array('id'=>'designation_level_id')) !!}</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Employee Category</div>
                            <div class="col-sm-8">{!! Form::select('employee_category_id', $employeecategory,'',array('id'=>'employee_category_id')) !!}</div>
                          </div>
                    </code>
                </div>
            </div>
            <div id="Ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsEmployeeInactiveSummery.getSection()">Section</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsEmployeeInactiveSummery.getSubsection()">Sub-section</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsEmployeeInactiveSummery.getDepartment()">Department</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsEmployeeInactiveSummery.getDesignation()">Designation</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeInactiveSummery.resetForm('employeeinactivesummeryFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>

<div id="employeedetailwindow" class="easyui-window" title="Employee Details Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="employeedetailTbl">
    </table>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/HRM/MsEmployeeInactiveSummeryController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>