<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comexpcredittabs">
    <div title="Pre Export Credit" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="expprecreditTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'cr_date'" width="100">Credit Date</th>
                            <th data-options="field:'loan_type_id'" width="100">Loan Type</th>
                            <th data-options="field:'loan_no'" width="100">Loan No</th>
                            <th data-options="field:'maturity_date'" width="100">Maturity Date</th>
                            <th data-options="field:'tenor'" width="100">Tenor</th>
                            <th data-options="field:'rate'" width="100">Rate</th>
                            <th data-options="field:'commercial_head_id'" width="100">Deposit Account</th>
                            <th data-options="field:'commercial_head_name'" width="100">Bank Limit A/C</th>  
                            <th data-options="field:'amount'" width="100">Credit Taken</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
 
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="expprecreditFrm">
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','onchange'=>'MsExpPreCredit.setBankAccount(this.value)')) !!}
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Credit Date </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="cr_date" id="cr_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Loan Type </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('loan_type_id', $loantype,'',array('id'=>'loan_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Loan No </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="loan_no" id="loan_no"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Deposit Account</div>
                                        <div class="col-sm-7">
                                        {!! Form::select('commercial_head_id', $commercialhead,'',array('id'=>'commercial_head_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Bank Limit A/C</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="commercial_head_name" id="commercial_head_name" value=""  ondblclick="MsExpPreCredit.bankaccountWindowOpen()"  placeholder="Double Click to Select" readonly/>
                                        <input type="hidden" name="bank_account_id" id="bank_account_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Tenor </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="tenor" id="tenor" class="number integer">
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-5">Interest Rate </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="rate" id="rate" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Maturity Date </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="maturity_date" id="maturity_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Credit Amount </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount" placeholder="display" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Purpose</div>
                                    <div class="col-sm-7">
                                        <textarea name="purpose" id="purpose"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" cols="30" rows="7"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpPreCredit.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expprecreditFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpPreCredit.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpPreCredit.getpackingcredit()">F.Letter</a>
                    </div>
 
                </form>
            </div>
        </div>
    </div>
    <div title="LC/SC Detail" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="expprecreditlcscTbl" style="width:100%">
                    <thead>
                      <tr>
                         <th data-options="field:'id'" width="40">ID</th>
                         <th data-options="field:'lc_sc_no'" width="70">SC / LC</th>
                         <th data-options="field:'buyer_name'" width="100">Buyer</th>    
                         <th data-options="field:'bank_name'" width="100">Lien Bank</th>   
                         <th data-options="field:'credit_taken'" width="100" align="right">Credit Taken</th>
                         <th data-options="field:'exch_rate'" width="100" align="right">Conversion Rate</th>
                         <th data-options="field:'equivalent_fc'" width="100" align="right">Equivalent FC</th>                       
                      </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sales Order Details',footer:'#ft3'" style="width: 350px; padding:2px">
                <form id="expprecreditlcscFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                 <div class="row middle" style="display:none">
                                     <input type="hidden" name="id" id="id" value="" />
                                     <input type="hidden" name="exp_pre_credit_id" id="exp_pre_credit_id" value="" />               
                                 </div>
                                <div class="row middle">
                                     <div class="col-sm-4">LC/SC</div>
                                     <div class="col-sm-8">
                                         <input type="text" name="lc_sc_no" id="lc_sc_no" ondblclick="MsExpPreCreditLcSc.openExpPreCreditLcScWindow()" placeholder=" Double Click" readonly />
                                         <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                                     </div>
                                 </div>
                                 <div class="row middle">
                                     <div class="col-sm-4">Credit Taken </div>
                                     <div class="col-sm-8">
                                         <input type="text" name="credit_taken" id="credit_taken" class="number integer" onchange="MsExpPreCreditLcSc.calculateEquiFc()" />
                                     </div>
                                 </div>
                                 <div class="row middle">
                                     <div class="col-sm-4">Exch. Rate</div>
                                     <div class="col-sm-8">
                                         <input type="text" name="exch_rate" id="exch_rate" class="number integer" onchange="MsExpPreCreditLcSc.calculateEquiFc()" />
                                     </div>
                                 </div>
                                 <div class="row middle">
                                     <div class="col-sm-4">Equivalent FC</div>
                                     <div class="col-sm-8">
                                         <input type="text" name="equivalent_fc" id="equivalent_fc" class="number integer" />
                                     </div>
                                 </div>
                                 
                            </code>
                        </div>
                    </div>
                    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpPreCreditLcSc.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expprecreditlcscFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpPreCreditLcSc.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
 </div>
 <div id="expprecreditlcscWindow" class="easyui-window" title="Pre Export Credit / Sales Contract Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
     <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
             <div class="easyui-layout" data-options="fit:true">
                 <div id="body">
                     <code>
                         <form id="precreditlcscsearchFrm">
                             <div class="row middle">
                                 <div class="col-sm-4">Contract No</div>
                                 <div class="col-sm-8">
                                         <input type="text" name="lc_sc_no" id="lc_sc_no" value="">
                                 </div>
                             </div>
                             <div class="row middle">
                                 <div class="col-sm-4">Contract Date </div>
                                 <div class="col-sm-8">
                                    <input type="text" name="lc_sc_date" id="lc_sc_date" value="" class="datepicker" />
                                 </div>
                             </div>
                         </form>
                     </code>
                 </div>
                 <p class="footer">
                     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsExpPreCreditLcSc.searchPreCreditSalesContractGrid()">Search</a>
                 </p>
             </div>
         </div>
         <div data-options="region:'center'" style="padding:10px;">
             <table id="precreditlcscsearchTbl" style="width:100%">
                 <thead>
                     <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'lc_sc_no'" width="100">Contract No</th>
                        <th data-options="field:'buyer_id'" width="100">Buyer</th>    
                        <th data-options="field:'bank_name'" width="100">Lien Bank</th>   
                        <th data-options="field:'lc_sc_value'" width="100">Contract Value</th>
                        <th data-options="field:'lc_sc_nature'" width="100">Contract Nature</th>
                        <th data-options="field:'lc_sc_date'" width="100"> Contract Date</th> 
                        <th data-options="field:'last_delivery_date'" width="100" align="right"> Last Delivery Date</th>  
                        <th data-options="field:'sc_or_lc'" width="70">SC\LC</th>
                     </tr>
                 </thead>
             </table>
         </div>
         <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#expprecreditlcscWindow').window('close')" style="width:80px">Close</a>
         </div>
     </div>
</div>

<div id="openbankaccountWindow" class="easyui-window" title="Bank Account Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#bankaccountsearchTblFt'" style="padding:2px">
            <table id="bankaccountsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'name'" width="100">Name</th>
                        <th data-options="field:'account_no'" width="100">A/C No.</th>      
                        <th data-options="field:'commercial_head_name'" width="100">A/C Type</th>
                        <th data-options="field:'branch_name'" width="100">Branch Name</th>
                    </tr>
                </thead>
            </table>
            <div id="bankaccountsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openbankaccountWindow').window('close')" style="width:80px">Close</a>
            </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#bankaccountsearchFrmFt'" style="padding:2px; width:350px">
            <form id="bankaccountsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row">
                                <div class="col-sm-4 req-text">Name</div>
                                <div class="col-sm-8">
                                    <input type="text" name="branch_name" id="name" />
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
                <div id="bankaccountsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsExpPreCredit.searchbankAccount()">Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
 
 <script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Export/MsAllExpPreCreditController.js"></script>
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


    $('#cr_date').change(function(){
        MsExpPreCredit.setMaturityDate();
    });
    $('#tenor').change(function(){
        MsExpPreCredit.setMaturityDate();
    });
    $('#expprecreditFrm [id="commercial_head_id"]').combobox();
 
 </script>
 