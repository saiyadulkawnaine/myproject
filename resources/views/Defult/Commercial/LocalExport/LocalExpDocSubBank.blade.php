<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comlocalexpdocsubtabs">
    <div title="Reference Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="localexpdocsubbankTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'beneficiary_id'" width="60">Beneficiary</th>
                            <th data-options="field:'local_lc_no'" width="180">LC</th>
                            <th data-options="field:'submission_date'" width="100">Submission Date</th>
                            <th data-options="field:'submission_type_id'" width="100">Submission Type</th>
                            <th data-options="field:'negotiation_date'" width="100">Negotiation Date</th>
                            <th data-options="field:'bank_ref_bill_no'" width="100">Bank Bill No</th>
                            <th data-options="field:'bank_ref_date'" width="100">Bank ref date</th>
                            <th data-options="field:'courier_recpt_no'" width="100">Courier Receipt No</th>
                            <th data-options="field:'courier_company'" width="100">Courier Company</th>
                            <th data-options="field:'bnk_to_bnk_cour_no'" width="100">Bnk-2-Bnk Cour No</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Doc. submission to bank',footer:'#ft2'" style="width: 360px; padding:2px">
                <form id="localexpdocsubbankFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5">Accept Id</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="local_lc_no_accept_id" id="local_lc_no_accept_id" ondblclick="MsLocalExpDocSubBank.openLocalDocSubAcceptWindow()" placeholder=" Double Click">
                                        <input type="hidden" name="local_exp_doc_sub_accept_id" id="local_exp_doc_sub_accept_id" value="" />
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
                                        <input type="text" name="bank_ref_bill_no" id="bank_ref_bill_no" placeholder=" write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bank Ref Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bank_ref_date" id="bank_ref_date" class="datepicker" placeholder=" yyyy-mm-dd"/>
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Doc Value</div>
                                    <div class="col-sm-7">
                                        {{-- <input type="text" name="doc_value" id="doc_value" class="number integer" readonly /> --}}
                                        <input type="text" name="local_invoice_value" id="local_invoice_value" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Courier Receipt No. </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="courier_recpt_no" id="courier_recpt_no" placeholder=" txt"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Courier Company</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="courier_company" id="courier_company" placeholder=" txt" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bnk-2-Bnk Cour No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bnk_to_bnk_cour_no" id="bnk_to_bnk_cour_no" placeholder=" txt" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Bnk-2-Bnk Cour Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="bnk_to_bnk_cour_date" id="bnk_to_bnk_cour_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Maturity Rcv Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="maturity_rcv_date" id="maturity_rcv_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Place for Purchase</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="place_for_purchase" id="place_for_purchase" class="datepicker" placeholder="yyyy-mm-dd" />
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
                                    <div class="col-sm-5">LC Currency</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="currency_id" id="currency_id" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpDocSubBank.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('localexpdocsubbankFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpDocSubBank.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpDocSubBank.latter()">Nego</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Transaction Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="localexpdocsubtransTbl" style="width:100%">
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
                <form id="localexpdocsubtransFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-12">
                                        <input type="radio" name="aa" value="1" checked="checked"> Document Currency
                                        <input type="radio" name="aa" value="2"> Conv. Rate
                                        <input type="radio" name="aa" value="3"> Domestic Currency
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="local_exp_doc_sub_bank_id" id="local_exp_doc_sub_bank_id" value="" />   
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Transaction Head</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('commercialhead_id', $commercialhead,'',array('id'=>'commercialhead_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">A/C Loan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="ac_loan_no" id="ac_loan_no" placeholder=" write" />
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Domestic Currency</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="dom_value" id="dom_value" class="number integer" onchange="MsLocalExpDocSubTrans.calculate()" />
                                        
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Conv. Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exch_rate" id="exch_rate" class="number integer" onchange="MsLocalExpDocSubTrans.calculate()"/>
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Document Currency</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="doc_value" id="doc_value" class="number integer" onchange="MsLocalExpDocSubTrans.calculate()"/>
                                    </div>                                   
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">Account Head</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="" id="" placeholder=" display" readonly />
                                    </div>                                   
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpDocSubTrans.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpDocSubTrans.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpDocSubTrans.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="localdocsubacceptwindow" class="easyui-window" title="Document Submission To Bank / LC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#docsubmissionwindowft'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="localdocsubacceptsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4"> LC No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="local_lc_no" id="local_lc_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">LC Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_date" id="lc_date" value="" class="datepicker" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="docsubmissionwindowft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="height:25px; border-radius:1px" onClick="MsLocalExpDocSubBank.searchDocSubAccept()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="localdocsubacceptsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'local_exp_doc_sub_accept_id'" width="30">Acc. ID</th>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'local_lc_no'" width="100">LC No</th>
                        <th data-options="field:'lc_value'" width="100">LC Value</th>
                        <th data-options="field:'lc_date'" width="100"> LC Date</th> 
                        <th data-options="field:'last_delivery_date'" width="100" align="right"> Last Delivery Date</th>  
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

 
<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/LocalExport/MsAllLocalExpDocSubBankController.js"></script>
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
    $('.integer').keyup(function () {
			if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
			this.value = this.value.replace(/[^0-9\.]/g, '');
			}
		});
    $('#localexpdocsubtransFrm [id="commercialhead_id"]').combobox();
    
})(jQuery);

</script>
 