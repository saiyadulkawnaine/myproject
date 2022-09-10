<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="accothertradefinancetabs">
    <div title="Other Trade Finance" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                     <table id="accothertradefinanceTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'bank_branch_id'" width="150">Financial Institute</th>
                            <th data-options="field:'account_no'" width="150">Loan Name</th>
                            <th data-options="field:'loan_ref_no'" width="100">Loan No</th>
                            <th data-options="field:'loan_date'" width="100">Loan Date</th>
                            <th data-options="field:'amount',halign:'center'" width="100" align="right">Loan Amount</th>
                            <th data-options="field:'grace_period'" width="100">Grace Period</th>
                            <th data-options="field:'installment_amount',halign:'center'" width="100" align="right">Installment Amount</th>
                            <th data-options="field:'no_of_installment',halign:'center'" width="100">No of Installment</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="accothertradefinanceFrm">
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
                                        <input type="text" name="account_no" id="account_no" value=""  ondblclick="MsAccOtherTradeFinance.otherbankaccountWindowOpen()"  placeholder="Double Click" readonly/>
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
                                    <div class="col-sm-5">Interest Rate</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Tenure</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="grace_period" id="grace_period" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Maturity Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="maturity_date" id="maturity_date" value="" class="datepicker" />
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
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccOtherTradeFinance.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccOtherTradeFinance.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccOtherTradeFinance.remove()">Delete</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div id="openotherbankaccountWindow" class="easyui-window" title="bank Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openotherbankaccountWindow').window('close')" style="width:80px">Close</a>
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
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsAccOtherTradeFinance.searchOtherBankAccount()">Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAccOtherTradeFinanceController.js"></script>
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

    $('#loan_date').change(function(){
        MsAccOtherTradeFinance.setMaturityDate();
    });
    $('#grace_period').change(function(){
        MsAccOtherTradeFinance.setMaturityDate();
    });

    })(jQuery);
</script>
