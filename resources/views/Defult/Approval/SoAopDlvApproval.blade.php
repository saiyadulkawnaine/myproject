<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soaopdlvapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soaopdlvapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsSoAopDlvApproval.approveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'buyer_name'" width="150">Customer</th>
                            <th data-options="field:'issue_no'" width="80">GIN</th>
                            <th data-options="field:'issue_date'" width="80">Issue Date</th>
                            <th data-options="field:'currency_code'" width="60">Currency</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                            <th data-options="field:'bill'" width="60" formatter='MsSoAopDlvApproval.billButton'></th>
                            <th data-options="field:'dc'" width="60" formatter='MsSoAopDlvApproval.dcButton'></th>
                            <th data-options="field:'requestletter'" width="100" formatter='MsSoAopDlvApproval.requestletterButton'></th>

                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soaopdlvapprovalFrmFt'" style="width:300px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soaopdlvapprovalFrm">
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                
                                <div class="row middle">
                                    <div class="col-sm-4"> Dlv. Date</div>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopDlvApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaopdlvapprovalFrm')">Reset</a>
                    
                </div>
            </div>
        </div>
    </div>
    <div title="Approved List" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="aopdlvapprovedTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsSoAopDlvApproval.unapproveButton'></th>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'issue_no'" width="100">GIN</th>
                            <th data-options="field:'issue_date'" width="100">Issue Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                            <th data-options="field:'bill'" width="60" formatter='MsSoAopDlvApproval.billButton'></th>
                            <th data-options="field:'dc'" width="60" formatter='MsSoAopDlvApproval.dcButton'></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Search',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#aopdlvapprovedFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="aopdlvapprovedFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4"> Dlv. Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="app_date_from" id="app_date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="app_date_to" id="app_date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="aopdlvapprovedFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopDlvApproval.getApp()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('aopdlvapprovedFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsSoAopDlvApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});
</script>