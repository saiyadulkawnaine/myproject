<div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Visitors" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
                <div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="ApprovalVisitorAccordion">
                    <div title="New Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#invpurreqapprovalfirstTblft1'" style="padding:2px">
                                <table id="approvalvisitorTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'id'" width="40">ID</th>
                                            <th data-options="field:'name'" width="120">Visitor Name</th>
                                            <th data-options="field:'contact_no'" width="100">Phone No</th>
                                            <th data-options="field:'organization_dtl'" width="100">Organization</th>
                                            <th data-options="field:'arrival_time'" width="120">Arrival Time</th>
                                            <th data-options="field:'user_name'" width="120">Meeting With</th>
                                            <th data-options="field:'purpose'" width="120">Purpose</th>
                                            <th data-options="field:'arrival_date'" width="120">Arrival Date</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="invpurreqapprovalfirstTblft1" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsApprovalVisitor.approve()">Approve</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div title="Approved" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
                        <div class="easyui-layout"  data-options="fit:true">
                            <div data-options="region:'center',border:true,title:'List',footer:'#ft2'" style="padding:2px">
                                <table id="approvedvisitorTbl" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'id'" width="40">ID</th>
                                            <th data-options="field:'name'" width="120">Visitor Name</th>
                                            <th data-options="field:'contact_no'" width="100">Phone No</th>
                                            <th data-options="field:'organization_dtl'" width="100">Organization</th>
                                            <th data-options="field:'arrival_time'" width="120">Arrival Time</th>
                                            <th data-options="field:'user_name'" width="120">Meeting With</th>
                                            <th data-options="field:'purpose'" width="120">Purpose</th>
                                            <th data-options="field:'arrival_date'" width="120">Arrival Date</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Search',footer:'#approvalvisitorFrmFt'" style="width:300px; padding:2px">
                <form id="approvalvisitorFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <div class="col-sm-4">Organization</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="organization_dtl" id="organization_dtl" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Arrival Date </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder=" From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder=" To" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="approvalvisitorFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsApprovalVisitor.get()">Show</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsApprovalVisitor.resetForm('approvalvisitorFrm')" >Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsApprovalVisitorController.js"></script>

<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>
    