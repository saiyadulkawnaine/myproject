<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="acctermloantabs">
    <div title="Loan Reference" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="acctermloanTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'bank_branch_id'" width="150">Financial Institute</th>
                            <th data-options="field:'account_type_id'" width="100">A/C Type</th>
                            <th data-options="field:'account_no'" width="150">Loan Name</th>
                            <th data-options="field:'loan_ref_no'" width="100">Loan No</th>
                            <th data-options="field:'loan_date'" width="100">Loan Date</th>
                            <th data-options="field:'amount',halign:'center'" width="100" align="right">Loan Amount</th>
                            <th data-options="field:'grace_period'" width="100" align="center">Grace Period</th>
                            <th data-options="field:'installment_amount',halign:'center'" width="100" align="right">Installment Amount</th>
                            <th data-options="field:'no_of_installment',halign:'center'" width="100">No of Installment</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="acctermloanFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Financial Institute</div>
                                    <div class="col-sm-7">
                                       {!! Form::select('bank_branch_id',
                                       $bankbranch,'',array('id'=>'bank_branch_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">A/C Type</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('account_type_id', $commercialhead,'',array('id'=>'account_type_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Loan Name </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="account_no" id="account_no" value=""  ondblclick="MsAccTermLoan.bankaccountWindowOpen()"  placeholder="Double Click" readonly/>
                                        <input type="hidden" name="bank_account_id" id="bank_account_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Loan Number</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="loan_ref_no" id="loan_ref_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Loan Date </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="loan_date" id="loan_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Loan Amount </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Grace Period</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="grace_period" id="grace_period" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Interest Rate</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Installment Amount</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="installment_amount" id="installment_amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">No of Installment</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_installment" id="no_of_installment" value="" class="number integer" />
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
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccTermLoan.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccTermLoan.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccTermLoan.remove()">Delete</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div title="Installment Details" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Installment Details',footer:'#acctermloaninstallmentFrmFt'" style="width: 400px; padding:2px">
                <form id="acctermloaninstallmentFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="acc_term_loan_id" id="acc_term_loan_id" value="" />
                                    <input type="hidden" name="sort_id" id="sort_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Loan Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="loan_date" id="loan_date" value="" class="datepicker" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Installment Amount</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Due Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="due_date" id="due_date" value="" class="datepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Paid Amount </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="paid_amount" id="paid_amount" value="" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Previous Paid Amount</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="prev_paid_amount" id="prev_paid_amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Balance Amount</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="balance_amount" id="balance_amount" value="" class="number integer" />
                                    </div>
                                </div> 
                            </code>
                        </div>
                    </div>
                    <div id="acctermloaninstallmentFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccTermLoanInstallment.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('acctermloaninstallmentFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccTermLoanInstallment.remove()" >Delete</a>
                    </div>
                </form>
            </div> 
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="acctermloaninstallmentTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'sort_id'" width="30" align="center">SL</th>
                            <th data-options="field:'loan_date'" width="80" align="center">Loan Date</th>
                            <th data-options="field:'amount'" width="80" align="right">Installment Amt.</th>
                            <th data-options="field:'due_date'" width="80" align="center">Due Date</th>
                            <th data-options="field:'paid_amount'" width="80" align="right">Paid Amt.</th>
                            <th data-options="field:'balance_amount'" width="80" align="right">Balance Amt.</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Payment Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="acctermloanpaymentTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'payment_date'" width="80" align="center">Payment Date</th>
                            <th data-options="field:'amount'" width="80" align="right">Principal Amount</th>
                            <th data-options="field:'interest_amount'" width="70" align="center">Interest</th>
                            <th data-options="field:'delay_charge_amount'" width="80" align="right">Delay Charge</th>
                            <th data-options="field:'payment_source_id'" width="80" align="right">Payment Source</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Payment Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'" style="width:400px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="acctermloanpaymentFrm">
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="acc_term_loan_installment_id" id="acc_term_loan_installment_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Payment Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="payment_date" id="payment_date" value="" class="datepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Installment No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sort_id" id="sort_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Principal Amt</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Interest</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="interest_amount" id="interest_amount" value="" class="number integer" />
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
                                    <div class="col-sm-5">Payment Source</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('payment_source_id', $commercialhead,'',array('id'=>'payment_source_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7"> 
                                        <textarea name="remarks" id="remarks" ></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft4" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccTermLoanPayment.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccTermLoanPayment.resetForm()">Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccTermLoanPayment.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="openbankaccountWindow" class="easyui-window" title="bank Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#banksearchTblFt'" style="padding:2px">
            <table id="bankaccountsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'account_no'" width="100">A/C No.</th>      
                        <th data-options="field:'account_type'" width="100">A/C Type</th>
                        <th data-options="field:'currency_name'" width="100">Currency</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'bank_name'" width="100">Bank</th>
                        <th data-options="field:'branch_name'" width="100">Branch</th>
                    </tr>
                </thead>
            </table>
            <div id="banksearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openbankaccountWindow').window('close')" style="width:80px">Close</a>
            </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#banksearchFrmFt'" style="padding:2px; width:350px">
            <form id="bankaccountsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row">
                                <div class="col-sm-4 req-text">Name</div>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="name" />
                                   
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Account No. </div>
                                <div class="col-sm-8">
                                    <input type="text" name="account_no" id="account_no" value="" />
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="banksearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsAccTermLoan.searchBankAccount()">Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAllAccTermLoanController.js"></script>
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