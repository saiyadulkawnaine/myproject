<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="tnadelaytabs">
    <div title="Task Progress Delay" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="tnaprogressdelayTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'task_name'" width="100">Custom Name</th>
                            <th data-options="field:'job_no'" width="80">Job No</th>
                            <th data-options="field:'style_ref'" width="100">Style Ref</th>
                            <th data-options="field:'sale_order_no'" width="100">Sales Order</th>
                            <th data-options="field:'ship_date'" width="80">Ship Dare</th>
                            <th data-options="field:'buyer_name'" width="140">Buyer</th>
                            <th data-options="field:'company_code'" width="60">Company</th>
                            <th data-options="field:'produced_company_name'" width="100">Produced Company</th>
                            <th data-options="field:'tna_start_date'" width="80">Plan Start Dare</th>
                            <th data-options="field:'tna_end_date'" width="80">Plan End Date</th>                            
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#Ft2'" style="width:350px; padding:2px">
                <form id="tnaprogressdelayFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" ondblclick="MsTnaProgressDelay.openOrderWindow()" placeholder="double click" readonly/>
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="tna_ord_id" id="tna_ord_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Task</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="task_name" id="task_name" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bnf.Company</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="company_code" id="company_code" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Prod.Company</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="produced_company_name" id="produced_company_name" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shipment Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="ship_date" id="ship_date" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Plan Start Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="tna_start_date" id="tna_start_date" value="" disabled="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Plan End Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="tna_end_date" id="tna_end_date" value="" disabled="" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="Ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTnaProgressDelay.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('tnaprogressdelayFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTnaProgressDelay.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Delay Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Delay Details',iconCls:'icon-more',footer:'#utilmanpowerft'" style="width:450px; padding:2px">
                <form id="tnaprogressdelaydtlFrm">
                    <div id="container">
                        <div id="body">
                            <code>                          
                                <div class="row">  
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="tna_progress_delay_id" id="tna_progress_delay_id" value=""/>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Cause of Delay</div>
                                    <div class="col-sm-8">
                                        <textarea name="cause_of_delay" id="cause_of_delay"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Impact</div>
                                    <div class="col-sm-8">
                                        <textarea name="impact" id="impact"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Action Taken</div>
                                    <div class="col-sm-8">
                                        <textarea name="action_taken" id="action_taken"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Responsible</div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" />
                                        <input type="text" name="name" id="name" ondblclick="MsTnaProgressDelayDtl.openEmployeeHr()" placeholder=" Double Click" readonly/>
                                    </div>
                                </div> 
                            </code>
                        </div>
                    </div>
                    <div id="utilmanpowerft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTnaProgressDelayDtl.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('tnaprogressdelaydtlFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTnaProgressDelayDtl.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="tnaprogressdelaydtlTbl" style="width:100%"> 
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'cause_of_delay'" width="100">Cause of Delay</th> 
                            <th data-options="field:'impact'" width="100">Impact</th>
                            <th data-options="field:'action_taken'" width="100">Action Taken</th>
                            <th data-options="field:'name'" width="100">Responsible</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Order window --}}
<div id="opendelayorderwindow" class="easyui-window" title="Order Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="tnaordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Ship Date </div>
                                <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Buyer </div>
                                <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsTnaProgressDelay.searchOrder()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="tnaordersearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'task_name'" width="100">Custom Name</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'job_no'" width="90">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                        <th data-options="field:'buyer_name'" width="130">Buyer</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'produced_company_name'" width="80">Produced Company</th>
                        <th data-options="field:'tna_start_date'" width="100">Plan Start Date</th>
                        <th data-options="field:'tna_end_date'" width="100">Plan End Date</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opendelayorderwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<!--------------------Employee Search-Window Start------------------>
<div id="openemployeehrwindow" class="easyui-window" title="Responsible Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="employeehrsearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsTnaProgressDelayDtl.searchEmployeeGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="employeehrsearchTbl" style="width:700px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'name'" width="100">Name</th>
                        <th data-options="field:'code'" width="100">User Given Code</th>
                        <th data-options="field:'designation_id'" width="100">Designation</th>
                        <th data-options="field:'department_id'" width="100">Department</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'national_id'" width="100">National ID</th>
                        <th data-options="field:'address'" width="100">Address</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openemployeehrwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Planing/MsAllTnaProgressDelayController.js"></script>
<script>
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

    $('#tnaordersearchFrm [id="buyer_id"]').combobox();
    $('#employeehrsearchFrm [id="designation_id"]').combobox();
    $('#employeehrsearchFrm [id="department_id"]').combobox();
</script>