<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="cashincentivereportTbl" style="width:100%">
            <thead>
                <tr>
                    <th  width="80" >1</th>
                    <th width="100">2</th>
                    <th width="70">3</th>
                    <th width="100">4</th>
                    <th width="100">5</th>
                    <th width="80">6</th>
                    <th width="100">7</th>
                    <th width="80">8</th>
                    <th width="100">9</th>
                    <th width="100">10</th>
                    <th  width="40">11</th>
                    <th width="80">12</th>
                    <th width="80">13</th>
                    <th width="70">14</th>
                    <th width="50">15</th>
                    <th width="50">16</th>
                    <th width="50">17</th>
                    <th width="50">18</th>
                    <th width="50">19</th>
                    <th width="50">20</th>
                    <th width="80">21</th>
                    <th width="70">22</th>
                    <th width="100">23</th>
                    <th width="80">24</th>
                    <th width="50">25</th>
                    <th width="80">26</th>
                    <th width="90">27</th>
                    <th width="100">28</th>
                    <th width="100">29</th>
                    <th width="100">30</th>
                    <th width="100">31</th>
                    <th width="100">32</th>
                    <th width="100">33</th>
                    <th width="100">34</th>
                    <th width="100">35</th>
                    <th width="150">36</th>
                </tr>
                <tr>
                    <th data-options="field:'incentive_no',halign:'center'" width="80" formatter="MsCashIncentiveReport.formatincentiveref">ERP ID</th>
                    <th data-options="field:'lc_sc_no',halign:'center'" align="center" width="100">Lc/SC No</th>
                    <th data-options="field:'company_code',halign:'center'" align="center" width="70">Exporter<br/>Company</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="100">Buyer</th>
                    <th data-options="field:'bank_file_no',halign:'center'" width="100">Bank File <br/> No</th>
                    <th data-options="field:'region_id',halign:'center'" width="80">Region</th>
                    <th data-options="field:'claim_sub_date',halign:'center'" width="100">Claim <br/>Sub.Date</th>
                    <th data-options="field:'exporter_branch_name',halign:'center'" width="80">Lien Bank</th>
                    <th data-options="field:'lc_sc_value',halign:'center',align:'center'" width="100">LC Value</th>
                    <th data-options="field:'bank_bill_no',halign:'center'" width="100"  formatter="MsCashIncentiveReport.formatincentiveclaim">Bank Bill No</th>
                    <th data-options="field:'invoice_no',halign:'center',"  width="40"  formatter="MsCashIncentiveReport.formatincentiveclaim">Export<br/>Invoice No</th>{{-- styler:MsProdGmtDailyExFactoryReport.formatDelays --}}
                    <th data-options="field:'invoice_qty',halign:'center'" width="80" align="right" formatter="MsCashIncentiveReport.formatClaimInvoiceQty">Invoice<br/> Qty</th>
                    <th data-options="field:'invoice_amount',halign:'center'" align="right" width="80" formatter="MsCashIncentiveReport.formatClaimInvoiceAmount">Invoice<br/>Value</th>
                    <th data-options="field:'net_wgt_exp_qty',halign:'center'" align="right" width="70" formatter="MsCashIncentiveReport.formatClaimInvoiceNetWgtExpQty">Net Wgt</th>
                    <th data-options="field:'gsp_certify_btma_arranged',halign:'center'" align="center" width="50" >GSP<br/>BTMA</th>
                    <th data-options="field:'vat_eleven_arranged',halign:'center'" align="center" width="50" >VAT<br/>11</th>
                    <th data-options="field:'ud_copy_arranged',halign:'center'" align="center" width="50" >UD Copy</th>
                    <th data-options="field:'prc_bd_format_arranged',halign:'center'" align="center" width="50">PRC</th>
                    <th data-options="field:'alt_cash_assist_bgmea_arranged',halign:'center'" align="center" width="50" >Cash Alt<br/>BGMEA</th>
                    <th data-options="field:'cash_certify_btma_arranged',halign:'center'" align="center" width="50">Cash<br/>BTMA</th>
                    <th data-options="field:'realized_amount',halign:'center'" align="right" width="80" formatter="MsCashIncentiveReport.formatClaimRealizedAmount">Realized<br/>Amount</th>
                    <th data-options="field:'freight',halign:'center'" align="right" width="70" formatter="MsCashIncentiveReport.formatClaimFreight">Adj. to <br/>Net Amount</th>
                    <th data-options="field:'net_realized_amount',halign:'center'" align="right" width="100" formatter="MsCashIncentiveReport.formatClaimNetRealizedAmount">Net Realized</th>
                    <th data-options="field:'cost_of_export',halign:'center'" align="right" width="80" formatter="MsCashIncentiveReport.formatClaimCostOfExport">Cost Of<br/>Export</th>
                    <th data-options="field:'claim',halign:'center'" align="right" width="50" formatter="MsCashIncentiveReport.formatClaim">Claim %</th>
                    <th data-options="field:'claim_amount',halign:'center'" align="right" width="80" formatter="MsCashIncentiveReport.formatClaimAmount">Claim<br/>Amount<br/> USD</th>
                    <th data-options="field:'local_cur_amount',halign:'center'" align="right" width="90" formatter="MsCashIncentiveReport.formatLocalCurrencyAmount">Claim<br/>Amount<br/> TK</th>
                    <th data-options="field:'advance_amount_tk',halign:'center'" align="right" width="100">Advance<br/>Taken<br/> TK</th>
                    <th data-options="field:'balance_tk',halign:'center'" align="right" width="100">Balance<br/> TK</th>
                    <th data-options="field:'audit_submit_date',halign:'center'" width="100">Audit<br/>Submission<br/>Date</th>
                    <th data-options="field:'audit_complete',halign:'center'" align="right" width="100">Audit<br/>Complete</th>
                    <th data-options="field:'bb_submit_date',halign:'center'" align="right" width="100">Submitted to<br/>B.Bank</th>
                    <th data-options="field:'file_amount',halign:'center'" align="right" width="100">Final Claim<br/>Amount</th>
                    <th data-options="field:'claim_finalized',halign:'center'" align="right" width="100">Claim Finalized<br/>by B.Bank</th>
                    <th data-options="field:'claim_finalized_by_bank',halign:'center'" align="right" width="100">Claim<br/>Realized</th>
                    <th data-options="field:'remarks',halign:'center'" align="right" width="150">Remarks</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Cash Incentive Follow Up Report',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="cashincentivereportFrm">
            <div id="container">
                <div id="body">
                <code>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Year </div>
                        <div class="col-sm-8">{!! Form::select('year', $years,$selected_year,array('id'=>'year')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Exporter</div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Buyer </div>
                        <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Bank Bill No</div>
                        <div class="col-sm-8">
                            <input type="text" name="bank_bill_no" id="bank_bill_no" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">LC No</div>
                        <div class="col-sm-8">
                            <input type="text" name="lc_sc_no" id="lc_sc_no" value="" />
                        </div>
                    </div>             
                    <div class="row middle">
                        <div class="col-sm-4">Incentive ID</div>
                        <div class="col-sm-8">
                            <input type="text" name="incentive_no" id="incentive_no" value="" />
                        </div>
                    </div>             
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveReport.claimDetailWindow()">PRC/EXP</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveReport.resetForm('cashincentivereportFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>
    
<div id="incentiveDocPrepWindow" class="easyui-window" title="Cash Incentive Document Preparation Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:860px;height:500px;padding:2px;">
    <div id="containerDocWindow" style="width:100%;height:420px;padding:0px;">

    </div>
    <div data-options="region:'south',border:false" style="text-align:right;padding:1px 0 0;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#incentiveDocPrepWindow').window('close')" style="width:80px">Close</a>
    </div>
</div>
    
<div id="incentiveClaimWindow" class="easyui-window" title="Cash Incentive Claim Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;"> 
    <table id="incentiveClaimReportTbl">
        <thead>
            <tr>
                <th data-options="field:'id'" width="40px">ID</th>
                <th data-options="field:'cash_incentive_ref_id'" width="60px">Ref ID</th>
                <th data-options="field:'lc_sc_no'" width="150px">ID</th>
                <th data-options="field:'exp_form_no'" width="70px">EXP No</th>
                <th data-options="field:'bank_bill_no'" width="70px">Bank <br/>Bill No</th>
                <th data-options="field:'invoice_no'" width="100px" align="right">Invoice No</th>
                <th data-options="field:'invoice_qty'" width="80px" align="right">Invoice Qty</th>
                <th data-options="field:'invoice_amount'" width="80px" align="right">Invoice <br/>Amount</th>
                <th data-options="field:'realized_amount'" width="80px" align="right">Realized <br/>Amount</th>
                <th data-options="field:'short_realized_amount'" width="80px" align="right">Short<br/> Realized<br/>Amount</th>
                <th data-options="field:'short_realize_percent'" width="80px" align="right">Short Realized<br/>%</th>
                <th data-options="field:'realized_date'" width="90px" align="right">Realized Date</th>
                <th data-options="field:'net_wgt_exp_qty'" width="80px" align="right">Net Wgt<br/> Export</th>
                
                <th data-options="field:'cost_of_export'" width="80px" align="right">Cost Of <br/>Export</th>
                <th data-options="field:'freight'" width="60px" align="right">Freight</th> 
                <th data-options="field:'net_realized_amount'" width="80px" align="right">Net Realized <br/>Value</th>                       
                <th data-options="field:'claim_amount'" width="80px" align="right">Claim <br/>Amount</th>
                <th data-options="field:'exch_rate'" width="60px" align="right">Conversion<br/> Rate</th>     
                <th data-options="field:'local_cur_amount'" width="80px" align="right">Local Currency<br/> Amount</th>
            </tr>
        </thead>
    </table>  
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsCashIncentiveReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
    $('#cashincentivereportFrm [id="buyer_id"]').combobox();
</script>