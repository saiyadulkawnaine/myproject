<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="budgetapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="budgetapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetApproval.budVsMktButton" align="center">Details</th>
                            <th data-options="field:'app'" width="60" formatter='MsBudgetApproval.approveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'job_no'" width="70">Job No</th>
                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                            <th data-options="field:'company_name'" width="70">Company</th>
                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                            <th data-options="field:'currency_code'" width="70">Currency</th>
                            <th data-options="field:'uom_code'" width="70">Uom</th>
                            <th data-options="field:'bill'" width="100" formatter='MsBudgetApproval.budgetButton'></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetapprovalFrmFt'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                                    
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
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
                <div id="budgetapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>

    <div title="Approved List" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="budgetapprovedTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsBudgetApproval.unapproveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'job_no'" width="70">Job No</th>
                            <th data-options="field:'style_ref'" width="70">Style Ref</th>
                            <th data-options="field:'company_name'" width="70">Company</th>
                            <th data-options="field:'buyer_name'" width="70">Buyer</th>
                            <th data-options="field:'budget_date'" width="70">Budget Date</th>
                            <th data-options="field:'currency_code'" width="70">Currency</th>
                            <th data-options="field:'uom_code'" width="70">Uom</th>
                            <th data-options="field:'bill'" width="100" formatter='MsBudgetApproval.budgetButton'></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetapprovedFrmFt'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetapprovedFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>                                               
                                <div class="row middle">
                                    <div class="col-sm-4"> Budget Date</div>
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
                <div id="budgetapprovedFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetApproval.getApp()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetapprovedFrm')">Reset</a>
                    {{-- <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetApproval.showExcel()">Excel</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div id="budgetApprovalDetailWindow" class="easyui-window" title="Cost Details" data-options="modal:true,closed:true," style="width:100%;height:100%;padding:2px;">
    <div id="budgetApprovalDetailContainer"></div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsBudgetApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>
    