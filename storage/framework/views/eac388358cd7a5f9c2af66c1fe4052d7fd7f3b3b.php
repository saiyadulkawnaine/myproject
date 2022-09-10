<div class="easyui-layout animated rollIn" data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="employeeattendenceTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'company_id'" width="140">Company</th>
                    <th data-options="field:'attendence_date'" width="100">Attendence Date</th>
                    <th data-options="field:'operator'" width="80" align="right">Operator</th>
                    <th data-options="field:'helper'" width="80" align="right">Helper</th>
                    <th data-options="field:'prod_staff'" width="80" align="right">Prod.Staff</th>
                    <th data-options="field:'supporting_staff'" width="80" align="right">Support.Staff</th>                
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Employee Attendence',footer:'#ft2'" style="width: 400px; padding:2px">
        <form id="employeeattendenceFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle" style="display:none">
                            <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Company</div>
                            <div class="col-sm-8">
                                <?php echo Form::select('company_id', $company,'',array('id'=>'company_id')); ?>

                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Attend. Date</div>
                            <div class="col-sm-8">
                                <input type="text" name="attendence_date" id="attendence_date" class="datepicker" />
                            </div>
                        </div>     
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Operator</div>
                            <div class="col-sm-8"><input type="text" name="operator" id="operator"  class="number integer"/></div>
                        </div>                    
                        <div class="row middle">
                            <div class="col-sm-4">Helper </div>
                            <div class="col-sm-8"><input type="text" name="helper" id="helper" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Prod Staff</div>
                            <div class="col-sm-8"><input type="text" name="prod_staff" id="prod_staff" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Support Staff </div>
                            <div class="col-sm-8"><input type="text" name="supporting_staff" id="supporting_staff" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Cutting Staff </div>
                            <div class="col-sm-8"><input type="text" name="cutting_staff" id="cutting_staff" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Embroidery Staff </div>
                            <div class="col-sm-8"><input type="text" name="embroidery_staff" id="embroidery_staff" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">ScreenPrint Staff </div>
                            <div class="col-sm-8"><input type="text" name="printing_staff" id="printing_staff" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Finishing Staff </div>
                            <div class="col-sm-8"><input type="text" name="finishing_staff" id="finishing_staff" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Operator Salary</div>
                            <div class="col-sm-8"><input type="text" name="operator_salary" id="operator_salary" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Helper Salary</div>
                            <div class="col-sm-8"><input type="text" name="helper_salary" id="helper_salary" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Prod.Staff Salary</div>
                            <div class="col-sm-8"><input type="text" name="prod_stuff_salary" id="prod_stuff_salary" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Suport.Staff Salary</div>
                            <div class="col-sm-8"><input type="text" name="supporting_stuff_salary" id="supporting_stuff_salary" class="number integer" />			</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Operator OT</div>
                            <div class="col-sm-8"><input type="text" name="operator_ot" id="operator_ot" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Helper OT</div>
                            <div class="col-sm-8"><input type="text" name="helper_ot" id="helper_ot" class="number integer" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Daily Prod Bill</div>
                            <div class="col-sm-8"><input type="text" name="daily_prod_bill" id="daily_prod_bill" class="number integer" /></div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeAttendence.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeAttendence.resetForm()">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeAttendence.remove()">Delete</a>
            </div>

        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsEmployeeAttendenceController.js"></script>
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

</script>
