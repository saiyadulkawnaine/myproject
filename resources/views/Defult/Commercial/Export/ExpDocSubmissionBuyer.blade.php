<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comexpdocsubbuyertabs">
    <div title="Reference Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="expdocsubmissionbuyerTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'lc_sc_no'" width="180">LC/SC</th>
                            <th data-options="field:'submission_date'" width="100">Submission Date</th>
                            <th data-options="field:'submission_type_id'" width="100">Submission Type</th>
                            <th data-options="field:'negotiation_date'" width="100">Negotiation Date</th>
                            <th data-options="field:'days_to_realize'" width="100">Days to Realize</th>
                            <th data-options="field:'bank_ref_date'" width="100">Bank Ref Date</th>
                            <th data-options="field:'possible_realization_date'" width="100">Possible Relz Date</th>
                            <th data-options="field:'bnk_to_bnk_cour_no'" width="100">Bnk-2-Bnk Cour No</th>
                        </tr>
                    </thead>
                </table>
            </div>
 
            <div data-options="region:'west',border:true,title:'Doc. submission to buyer',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="expdocsubmissionbuyerFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4">LC/SC</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lc_sc_no" id="lc_sc_no" ondblclick="MsExpDocSubmissionBuyer.openDocSubmissionBuyerWindow()" placeholder=" Double Click">
                                        <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                                        <input type="hidden" name="id" id="id" />
                                    </div>
                                </div>                              
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Submission Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="submission_date" id="submission_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Submission Type </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('submission_type_id', $submissiontype,'',array('id'=>'submission_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Negotiation Date </div>
                                    <div class="col-sm-8">
                                            <input type="text" name="negotiation_date" id="negotiation_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bank Ref/ Bill No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_ref_bill_no" id="bank_ref_bill_no"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bank Ref Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_ref_date" id="bank_ref_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Days to Realize</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="days_to_realize" id="days_to_realize" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Possible Realization Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="possible_realization_date" id="possible_realization_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Courier Receipt No. </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="courier_recpt_no" id="courier_recpt_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">GSP Courier Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gsp_courier_date" id="gsp_courier_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Courier Company</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="courier_company" id="courier_company" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bnk-2-Bnk Cour No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bnk_to_bnk_cour_no" id="bnk_to_bnk_cour_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bnk-2-Bnk Cour Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bnk_to_bnk_cour_date" id="bnk_to_bnk_cour_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Advice Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="advice_ref" id="advice_ref"  placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Lien Bank</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyers_bank" id="buyers_bank" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_id" id="buyer_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Company Name</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="beneficiary_id" id="beneficiary_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">LC/SC Currency</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="currency_id" id="currency_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpDocSubmissionBuyer.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expdocsubmissionbuyerFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpDocSubmissionBuyer.remove()">Delete</a>
                    </div>
 
                </form>
            </div>
        </div>
    </div>
    <div title="Invoice Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="expdocsubbuyerinvoiceTbl" style="width:100%">
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
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'north',border:true,title:' Details',footer:'#ft3'" style="height:300px; padding:2px"> 
                <table id="expdocsubbuyerinvoiceTbl2" style="width:100%">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpDocSubBuyerInvoice.submit()">Save</a>
                </div>             
            </div>
        </div>
    </div>
</div>
<div id="docsubmissionbuyerwindow" class="easyui-window" title="Document Submission To Bank / LC/SC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="explcscbuyersearchFrm">
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
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsExpDocSubmissionBuyer.searchBuyerContractGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="explcscbuyersearchTbl" style="width:100%">
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
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#docsubmissionbuyerwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

 
<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Export/MsAllExpDocSubmissionBuyerController.js"></script>
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
         MsExpDocSubmissionBuyer.setPsRealizationDate();
      });
      $('#days_to_realize').change(function(){
         MsExpDocSubmissionBuyer.setPsRealizationDate();
      });
</script>
 