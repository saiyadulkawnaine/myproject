<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soaopdlvapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="employeemovementapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsEmployeeMovementApproval.approveButton'></th>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'post_date'" width="80">Post Date</th>
                            <th data-options="field:'designation'" width="100">Designation</th>
                            <th data-options="field:'department'" width="100">Department</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'bill'" width="60" formatter='MsEmployeeMovementApproval.ticketButton'></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Search',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soaopdlvapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="employeemovementapprovalFrm"> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Designation</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Post Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>        
                            </form>
                        </code>
                    </div>
                </div>
                <div id="soaopdlvapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeMovementApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('employeemovementapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsEmployeeMovementApprovalController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script> 