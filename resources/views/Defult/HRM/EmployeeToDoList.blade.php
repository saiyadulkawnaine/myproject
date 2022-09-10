<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="EmployeeToDoListTabs">
    <div title="Employee" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="employeetodolistTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'exec_date'" width="120">Execution Date</th>
                            <th data-options="field:'user_name'" width="100">User</th>
                            <th data-options="field:'department_name'" width="100">Department</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#employeetodolistFrmft'" style="width: 450px; padding:2px">
                <form id="employeetodolistFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                    <div class="row middle">
                                    <div class="col-sm-4 req-text">Execution Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exec_date" id="exec_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">User</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="user_name" id="user_name" value="{{$userData->user_name}}" disabled/>
                                        <input type="hidden" name="user_id" id="user_id" value="{{$userData->user_id}}">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                    <textarea name="remarks" id="remarks" cols="30" rows="8"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Location</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="location" id="location" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Department</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="department" id="department" value="{{$userData->department_name}}" disabled />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="employeetodolistFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeToDoList.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeToDoList.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeToDoList.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Task Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:100%">
                <table id="employeetodolisttaskTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'task_desc'" width="300">Task</th>
                            <th data-options="field:'priority_id'" width="70">Priority</th>
                            <th data-options="field:'start_date'" width="80">Action <br/>Start Date</th>
                            <th data-options="field:'end_date'" width="80">Action <br/>End Date</th>
                            <th data-options="field:'result_desc'" width="120">Result</th>
                            <th data-options="field:'impact_desc'" width="300">Impact</th>
                            <th data-options="field:'sort_id'" width="60">Sequence</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'List',footer:'#employeetodolisttaskFrmFt'" style="width: 400px; padding:2px">
                <form id="employeetodolisttaskFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="employee_to_do_list_id" id="employee_to_do_list_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Task</div>
                                    <div class="col-sm-8">
                                        <textarea name="task_desc" id="task_desc" cols="30" rows="8"></textarea>
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Priority</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('priority_id', $todopriority,'',array('id'=>'priority_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Time</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="task_time" id="task_time" class="timepicker" placeholder="h:mm am/pm" />
                                        {{-- hr
                                        <input type="text" name="task_hr" id="task_hr" style="width: 30px" />min
                                        <input type="text" name="task_min" id="task_min" style="width: 30px" /> --}}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Action Start Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="start_date" id="start_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Action End Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="end_date" id="end_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Result</div>
                                    <div class="col-sm-8">
                                        <textarea name="result_desc" id="result_desc"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Impact After</div>
                                    <div class="col-sm-8">
                                        <textarea name="impact_desc" id="impact_desc"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" class="number integer" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="employeetodolisttaskFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeToDoListTask.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeToDoListTask.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeToDoListTask.remove()">Delete</a>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    <div title="Barriers" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
                <table id="employeetodolisttaskbarTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'barrier_desc'" width="120">Task</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'List',footer:'#employeetodolisttaskbarFrmFt'" style="width: 400px; padding:2px">
                <form id="employeetodolisttaskbarFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="employee_to_do_list_task_id" id="employee_to_do_list_task_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Barrier</div>
                                    <div class="col-sm-8">
                                        <textarea name="barrier_desc" id="barrier_desc" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="employeetodolisttaskbarFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeToDoListTaskBar.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeToDoListTaskBar.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeToDoListTaskBar.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsAllEmployeeToDoListController.js"></script>
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

    

// $('.timepicker').timepicker({
//     timeFormat: 'h:mm p',
//     interval: 60,
//     minTime: '10',
//     maxTime: '6:00pm',
//     defaultTime: '11',
//     startTime: '10:00',
//     dynamic: false,
//     dropdown: true,
//     scrollbar: true
// });

$('#task_time').timepicker(
    {
        'minTime': '12:00pm',
        'maxTime': '11:59am',
        'showDuration': false,
        'step':1,
        'scrollDefault': 'now',
        'change': function(){
            alert('m')
            }
        }
    );


})(jQuery);

</script>
         