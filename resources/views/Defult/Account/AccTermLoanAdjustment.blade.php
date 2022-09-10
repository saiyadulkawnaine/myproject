<div class="easyui-tabs" style="width:100%;height:100%; border:none" >
    <div title="Account Term Loan Adjustment" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="acctermloanadjustmentTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'payment_date',halign:'center'" width="90">Payment Date</th>
                            <th data-options="field:'commercial_head',halign:'center'" width="150">Loan Name</th>
                            <th data-options="field:'loan_ref_no',halign:'center'" width="100" >Loan Ref No</th>
                            <th data-options="field:'amount',halign:'center'" width="100" align="right">Principle Amount</th>
                            <th data-options="field:'interest_rate',halign:'center'" width="60" align="right">Interest</th>
                            <th data-options="field:'other_charge_amount',halign:'center'" width="90" align="right">Other Charges</th>
                            <th data-options="field:'delay_charge_amount',halign:'center'" width="90" align="right">Delay Charges</th>
                            <th data-options="field:'payment_source',halign:'center'" width="140">Payment Source</th>
                            <th data-options="field:'remarks',halign:'center'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="acctermloanadjustmentFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                            <input type="hidden" name="id" id="id" value="" />
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Payment Date </div>
                                <div class="col-sm-7">
                                    <input type="text" name="payment_date" id="payment_date" class="datepicker" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Loan Name </div>
                                <div class="col-sm-7">
                                    {!! Form::select('commercial_head_id', $commercialhead,'',array('id'=>'commercial_head_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Loan No</div>
                                <div class="col-sm-7">
                                    <input type="hidden" name="other_loan_ref_id" id="other_loan_ref_id" />
                                    <input type="text" name="loan_ref_no" id="loan_ref_no" placeholder="Dobule Click" ondblclick="MsAccTermLoanAdjustment.TermLoanWindow()"  readonly/>
                                </div>
                            </div> 
                            <div class="row middle">
                                <div class="col-sm-5">Principle Amount</div>
                                <div class="col-sm-7">
                                    <input type="text" name="amount" id="amount" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Interest</div>
                                <div class="col-sm-7">
                                    <input type="text" name="interest_rate" id="interest_rate" class="number integer" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5">Other Charges</div>
                                <div class="col-sm-7">
                                    <input type="text" name="other_charge_amount" id="other_charge_amount" value="" class="number integer"/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5">Delay Charge</div>
                                <div class="col-sm-7">
                                    <input type="text" name="delay_charge_amount" id="delay_charge_amount" value="" class="number integer" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Payment Source</div>
                                <div class="col-sm-7">
                                    {!! Form::select('payment_source_id', $commercialhead,'',array('id'=>'payment_source_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5">Maturity Date</div>
                                <div class="col-sm-7">
                                    <input type="text" name="maturity_date" id="maturity_date" value="" disabled />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5">Remarks</div>
                                <div class="col-sm-7">
                                    <textarea name="remarks" id="remarks"></textarea>
                                </div>
                            </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccTermLoanAdjustment.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccTermLoanAdjustment.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccTermLoanAdjustment.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--------------------Term Loan Search-Window ------------------>
<div id="acctermloanWindow" class="easyui-window" title="Term Loan" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#acctermloansearchTblFt'" style="padding:2px">
            <table id="acctermloanSearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'bank_name'" width="200">Financial Institute</th>
                        <th data-options="field:'loan_ref_no'" width="100">Loan No</th>
                        <th data-options="field:'loan_date'" width="100">Loan Date</th>
                        <th data-options="field:'amount'" width="100" align="right">Loan Amount</th>
                    </tr>
                </thead>
            </table>
            <div id="acctermloansearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#acctermloanWindow').window('close')" style="width:80px">Close</a>
            </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#acctermloansearchFrmFt'" style="padding:2px; width:350px">
            <form id="acctermloansearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Company</div>
                                <div class="col-sm-7">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Financial Institute</div>
                                <div class="col-sm-7">
                                    {!! Form::select('bank_branch_id',
                                    $bankbranch,'',array('id'=>'bank_branch_id')) !!}
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="acctermloansearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsAccTermLoanAdjustment.searchTermLoan()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAccTermLoanAdjustmentController.js"></script>
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
    })(jQuery);
</script>