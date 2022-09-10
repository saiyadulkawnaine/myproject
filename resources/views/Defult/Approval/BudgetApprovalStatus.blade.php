<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="budgetapprovalstatustabs">
    <div title="Approval Status" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="budgetapprovalstatusTbl" style="width:100%">
                        <thead>
                            <tr>
                                <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetApprovalStatus.formatHtmlFirst" align="center">Details</th>
                                <th data-options="field:'app'" width="60" formatter='MsBudgetApprovalStatus.budVsMktButton'></th>
                                <th data-options="field:'id'" width="80">ID</th>
                                <th data-options="field:'job_no'" width="70">Job No</th>
                                <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                <th data-options="field:'company_name'" width="70">Company</th>
                                <th data-options="field:'buyer_name'" width="80">Buyer</th>
                                <th data-options="field:'buying_agent'" width="80">Buying House</th>
                                <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                <th data-options="field:'currency_code'" width="70">Currency</th>
                                <th data-options="field:'fabric_first_approved_by'" width="120">Fabric First Approved</th>
                                <th data-options="field:'fabric_second_approved_by'" width="120">Fabric Second Approved</th>
                                <th data-options="field:'fabric_third_approved_by'" width="120">Fabric Third Approved</th>
                                <th data-options="field:'fabric_final_approved_by'" width="120">Fabric Final Approved</th>

                                <th data-options="field:'yarn_first_approved_by'" width="120">Yarn First Approved</th>
                                <th data-options="field:'yarn_second_approved_by'" width="120">Yarn Second Approved</th>
                                <th data-options="field:'yarn_third_approved_by'" width="120">Yarn Third Approved</th>
                                <th data-options="field:'yarn_final_approved_by'" width="120">Yarn Final Approved</th>

                                <th data-options="field:'yarndye_first_approved_by'" width="120">Yarn Dyeing First Approved</th>
                                <th data-options="field:'yarndye_second_approved_by'" width="120">Yarn DyeingSecond Approved</th>
                                <th data-options="field:'yarndye_third_approved_by'" width="120">Yarn Dyeing Third Approved</th>
                                <th data-options="field:'yarndye_final_approved_by'" width="120">Yarn  Dyeing Final Approved</th>


                                <th data-options="field:'fabricprod_first_approved_by'" width="120">Fabric Prod First Approved</th>
                                <th data-options="field:'fabricprod_second_approved_by'" width="120">Fabric Prod Second Approved</th>
                                <th data-options="field:'fabricprod_third_approved_by'" width="120">Fabric Prod Third Approved</th>
                                <th data-options="field:'fabricprod_final_approved_by'" width="120">Fabric Prod Final Approved</th>

                                <th data-options="field:'embel_first_approved_by'" width="120">Embel. First Approved</th>
                                <th data-options="field:'embel_second_approved_by'" width="120">Embel. Second Approved</th>
                                <th data-options="field:'embel_third_approved_by'" width="120">Embel. Third Approved</th>
                                <th data-options="field:'embel_final_approved_by'" width="120">Embel. Final Approved</th>

                                <th data-options="field:'trim_first_approved_by'" width="120">Trim First Approved</th>
                                <th data-options="field:'trim_second_approved_by'" width="120">Trim Second Approved</th>
                                <th data-options="field:'trim_third_approved_by'" width="120">Trim Third Approved</th>
                                <th data-options="field:'trim_final_approved_by'" width="120">Trim Final Approved</th>

                                <th data-options="field:'other_first_approved_by'" width="120">Other First Approved</th>
                                <th data-options="field:'other_second_approved_by'" width="120">Other Second Approved</th>
                                <th data-options="field:'other_third_approved_by'" width="120">Other Third Approved</th>
                                <th data-options="field:'other_final_approved_by'" width="120">Other Final Approved</th>


                                <th data-options="field:'bill'" width="100" formatter='MsBudgetApprovalStatus.formatpdf'></th>
                            </tr>
                        </thead>
                    </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetfabricapprovalstatusFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetfabricapprovalstatusFrm">
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
                                    <input type="text" name="date_from" id="date_from_fabrin" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_fabric" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetfabricapprovalstatusFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetApprovalStatus.get()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetfabricapprovalstatusFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Returned" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="budgetreturnedstatusTbl" style="width:100%">
                        <thead>
                            <tr>
                                <th data-options="field:'html',halign:'center'" width="40" formatter="MsBudgetApprovalStatus.formatHtmlFirst" align="center">Details</th>
                                <th data-options="field:'app'" width="60" formatter='MsBudgetApprovalStatus.budVsMktButton'></th>
                                <th data-options="field:'id'" width="80">ID</th>
                                <th data-options="field:'job_no'" width="70">Job No</th>
                                <th data-options="field:'style_ref'" width="70">Style Ref</th>
                                <th data-options="field:'company_name'" width="70">Company</th>
                                <th data-options="field:'buyer_name'" width="70">Buyer</th>
                                 <th data-options="field:'buying_agent'" width="80">Buying House</th>
                                <th data-options="field:'budget_date'" width="70">Budget Date</th>
                                <th data-options="field:'currency_code'" width="70">Currency</th>
                                <th data-options="field:'fabric_returned_by'" width="120">Fabric Returned</th>
                                <th data-options="field:'fabric_returned_coments'" width="120">Fabric Comments</th>

                                <th data-options="field:'yarn_returned_by'" width="120">Yarn Returned</th>
                                <th data-options="field:'yarn_returned_coments'" width="120">Yarn Comments</th>

                                <th data-options="field:'yarndye_returned_by'" width="120">Yarn Dyeing Returned</th>
                                <th data-options="field:'yarndye_returned_coments'" width="120">Yarn Dyeing Comments</th>

                                <th data-options="field:'fabricprod_returned_by'" width="120">Fabric Prod  Returned</th>
                                <th data-options="field:'fabricprod_returned_coments'" width="120">Fabric Prod  Comments</th>

                                <th data-options="field:'embel_returned_by'" width="120">Embel  Returned</th>
                                <th data-options="field:'embel_returned_coments'" width="120">Embel  Comments</th>

                                <th data-options="field:'trim_returned_by'" width="120">Trim  Returned</th>
                                <th data-options="field:'trim_returned_coments'" width="120">Trim  Comments</th>

                                <th data-options="field:'other_returned_by'" width="120">Other  Returned</th>
                                <th data-options="field:'other_returned_coments'" width="120">Other  Comments</th>
                                


                                <th data-options="field:'bill'" width="100" formatter='MsBudgetApprovalStatus.formatpdf'></th>
                            </tr>
                        </thead>
                    </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#budgetfabricreturnedstatusFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="budgetfabricreturnedstatusFrm">
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
                                    <input type="text" name="date_from" id="date_from_fabrin" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to_fabric" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="budgetfabricreturnedstatusFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetApprovalStatus.getRtn()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetfabricreturnedstatusFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
    
    
    
    

    
</div>

<div id="budgetApprovalStatusDetailWindow" class="easyui-window" title="Budget Details" data-options="modal:true,closed:true," style="width:100%;height:100%;padding:2px;">
    <div id="budgetApprovalStatusDetailContainer"></div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsBudgetApprovalStatusController.js"></script>
<script>
$(".datepickery" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>
    