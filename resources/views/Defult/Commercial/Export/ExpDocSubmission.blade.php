<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comexpdocsubtabs">
    <div title="Reference Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="expdocsubmissionTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'beneficiary_id'" width="60">Beneficiary</th>
                            <th data-options="field:'lc_sc_no'" width="180">LC/SC</th>
                            <th data-options="field:'submission_date'" width="100">Submission Date</th>
                            <th data-options="field:'submission_type_id'" width="100">Submission Type</th>
                            <th data-options="field:'negotiation_date'" width="100">Negotiation Date</th>
                            <th data-options="field:'bank_ref_bill_no'" width="100">Bank Bill No</th>
                            <th data-options="field:'days_to_realize'" width="100">Days to Realize</th>
                            <th data-options="field:'bank_ref_date'" width="100">Bank Ref Date</th>
                            <th data-options="field:'possible_realization_date'" width="100">Possible <br/>Relz Dare</th>
                            <th data-options="field:'bnk_to_bnk_cour_no'" width="100">Bnk-2-Bnk<br/> Cour No</th>
                            <th data-options="field:'stuffing_date'" width="80">Stuffing Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
 
            <div data-options="region:'west',border:true,title:'Doc. submission to bank',footer:'#ft2'" style="width: 370px; padding:2px">
                <form id="expdocsubmissionFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5">LC/SC</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lc_sc_no" id="lc_sc_no" ondblclick="MsExpDocSubmission.openDocSubmissionWindow()" placeholder=" Double Click" readonly />
                                        <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                                        <input type="hidden" name="id" id="id" />
                                    </div>
                                </div>                              
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Submission Date </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="submission_date" id="submission_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Submission Type </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('submission_type_id', $submissiontype,'',array('id'=>'submission_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Negotiation Date </div>
                                    <div class="col-sm-7">
                                            <input type="text" name="negotiation_date" id="negotiation_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bank Ref/ Bill No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bank_ref_bill_no" id="bank_ref_bill_no"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bank Ref Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bank_ref_date" id="bank_ref_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Days to Realize</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="days_to_realize" id="days_to_realize" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Possible Realization Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="possible_realization_date" id="possible_realization_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Courier Receipt No. </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="courier_recpt_no" id="courier_recpt_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">GSP Courier Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="gsp_courier_date" id="gsp_courier_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Courier Company</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="courier_company" id="courier_company" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bnk-2-Bnk Cour No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bnk_to_bnk_cour_no" id="bnk_to_bnk_cour_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bnk-2-Bnk Cour Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bnk_to_bnk_cour_date" id="bnk_to_bnk_cour_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Advice Ref</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="advice_ref" id="advice_ref"  placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Stuffing Date </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="stuffing_date" id="stuffing_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Lien Bank</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="buyers_bank" id="buyers_bank" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Buyer</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="buyer_id" id="buyer_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Company Name</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="beneficiary_id" id="beneficiary_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">LC/SC Currency</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="currency_id" id="currency_id" disabled  />
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
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpDocSubmission.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expdocsubmissionFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocSubmission.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocSubmission.latter()">Nego</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocSubmission.forward()">F.Letter</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocSubmission.boe()">BOE</a>
                    </div>
 
                </form>
            </div>
        </div>
    </div>
    <div title="Invoice Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="expdocsubinvoiceTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'invoice_no'" width="80" align="right">Invoice No</th>
                            <th data-options="field:'invoice_date'" width="80" align="right">Invoice Date</th>
                            <th data-options="field:'lc_sc_no'" width="80" align="right">LC/SC No</th>
                            <th data-options="field:'bl_cargo_no'" width="80" align="right">BL No</th>
                            
                            <th data-options="field:'invoice_value'" width="80" align="right">Gross Value</th>
                            <th data-options="field:'discount_amount'" width="80" align="right">Discount</th>
                             <th data-options="field:'bonus_amount'" width="80" align="right">Bonus</th>
                            <th data-options="field:'claim_amount'" width="80" align="right">Claim Adjustment</th>
                            <th data-options="field:'commission'" width="80" align="right">Commission</th>
                            <th data-options="field:'net_inv_value'" width="80" align="right">Net Invoice Value</th>
                            <th data-options="field:'exp_doc_sub_invoice_id'" width="80" formatter="MsExpDocSubInvoice.formatDetail">Delete</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'north',border:true,title:' Details',footer:'#ft3'" style="height:300px; padding:2px"> 
                <table id="expdocsubinvoiceTbl2" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'invoice_no'" width="80">Invoice No</th>
                            <th data-options="field:'invoice_date'" width="80">Invoice Date</th>
                            <th data-options="field:'lc_sc_no'" width="80">LC/SC No</th>
                            <th data-options="field:'bl_cargo_no'" width="80">BL No</th>
                            <th data-options="field:'invoice_value'" width="80" align="right">Gross Value</th>
                            <th data-options="field:'discount_amount'" width="80" align="right">Discount</th>
                            <th data-options="field:'bonus_amount'" width="80" align="right">Bonus</th>
                            <th data-options="field:'claim_amount'" width="80" align="right">Claim Adjustment</th>
                            <th data-options="field:'commission'" width="80" align="right">Commission</th>
                            <th data-options="field:'net_inv_value'" width="80" align="right">Net Invoice Value</th>
                        </tr>
                    </thead>
                </table>       
                <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpDocSubInvoice.submit()">Save</a>

                </div>             
            </div>
        </div>
    </div>
    <div title="Transaction Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="expdocsubtransectionTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'commercialhead_id'" width="70">Transaction Head</th>
                            <th data-options="field:'ac_loan_no'" width="100">AC/ Loan No</th>
                            <th data-options="field:'dom_value'" width="100">Domestic Currency</th>
                            <th data-options="field:'exch_rate'" width="100">Conversation Rate</th>
                            <th data-options="field:'doc_value'" width="100">Document Currency</th>
                                                   
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#ft4'" style="width: 350px; padding:2px">
                <form id="expdocsubtransectionFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                   
                                    <div class="col-sm-12">
                                        <input type="radio" name="aa" value="1"> Document Currency
                                        <input type="radio" name="aa" value="2"> Conv. Rate
                                        <input type="radio" name="aa" value="3" checked="checked"> Domestic Currency
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="exp_doc_submission_id" id="exp_doc_submission_id" value="" />   
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Transaction Head</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('commercialhead_id', $commercialhead,'',array('id'=>'commercialhead_id')) !!}
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">A/C Loan No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="ac_loan_no" id="ac_loan_no" placeholder=" write" />
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Bank Limit A/C</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="commercial_head_name" id="commercial_head_name" value=""  ondblclick="MsExpDocSubTransection.transbankaccountWindowOpen()"  placeholder="Double Click to Select" readonly/>
                                        <input type="hidden" name="bank_account_id" id="bank_account_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Domestic Currency</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="dom_value" id="dom_value" class="number integer" onchange="MsExpDocSubTransection.calculate()" />
                                        
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Conv. Rate</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="exch_rate" id="exch_rate" class="number integer" onchange="MsExpDocSubTransection.calculate()"/>
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Document Currency</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="doc_value" id="doc_value" class="number integer" onchange="MsExpDocSubTransection.calculate()"/>
                                    </div>                                   
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-5">Account Head</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="" id="" placeholder=" display" readonly />
                                    </div>                                   
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpDocSubTransection.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocSubTransection.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocSubTransection.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="docsubmissionwindow" class="easyui-window" title="Document Submission To Bank / LC/SC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#docsubmissionwindowft'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="docsubexplcscsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">SC / LC No</div>
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
                    <div id="docsubmissionwindowft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="height:25px; border-radius:1px" onClick="MsExpDocSubmission.searchContractGrid()">Search</a>
                    </div>
                
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="docsubexplcscsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sc_or_lc'" width="70">SC\LC</th>
                        <th data-options="field:'lc_sc_no'" width="100">Contract No</th>
                        <th data-options="field:'lc_sc_value'" width="100">Contract Value</th>
                        <th data-options="field:'lc_sc_date'" width="100"> Contract Date</th> 
                        <th data-options="field:'last_delivery_date'" width="100" align="right"> Last Delivery Date</th>  
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="opentransbankaccountWindow" class="easyui-window" title="Bank Account Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#bankaccountsearchTblFt'" style="padding:2px">
            <table id="transbankaccountsearchTbl" style="width:100%">
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
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opentransbankaccountWindow').window('close')" style="width:80px">Close</a>
            </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#bankaccountsearchFrmFt'" style="padding:2px; width:350px">
            <form id="transbankaccountsearchFrm">
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
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsExpDocSubTransection.searchtransbankAccount()">Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
 
<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Export/MsAllMsExpDocSubmissionController.js"></script>
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
    $('#bank_ref_date').change(function(){
        MsExpDocSubmission.setPsRealizationDate();
    });
    $('#days_to_realize').change(function(){
        MsExpDocSubmission.setPsRealizationDate();
    });
</script>
 