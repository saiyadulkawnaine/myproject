<style>
.datagrid-footer .datagrid-row a:link{
    background: #4cae4c;font-weight:bold;color: #fff;
    text-decoration: none;
  }
</style>
<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'west',border:true,title:'Search',footer:'#negotiationreportFrmft'" style="padding:2px; width: 300px">
        <div class="easyui-layout"  data-options="fit:true">
        <form id="negotiationreportFrm">
            <div class="row middle">
                <div class="col-sm-4">Doc. Date </div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                </div>
                <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4">Ps.Rlz.Date</div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <input type="text" name="possible_date_from" id="possible_date_from" class="datepicker" placeholder="From" />
                </div>
                <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="possible_date_to" id="possible_date_to" class="datepicker"  placeholder="To" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Bank</div>
                <div class="col-sm-8">
                    {!! Form::select('bank_id', $bank,'',array('id'=>'bank_id')) !!}
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Company </div>
                <div class="col-sm-8">
                    {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id'))
                    !!}
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Buyer</div>
                <div class="col-sm-8">
                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                </div>
            </div>
            
            <div class="row middle">
                <div class="col-sm-4">File No </div>
                <div class="col-sm-8">
                    <input type="text" name="file_no" id="file_no">
                </div>
            </div>

            <div class="row middle">
                <div class="col-sm-4">System ID </div>
                <div class="col-sm-8">
                    <input type="text" name="submission_id" id="submission_id">
                </div>
            </div>        
        </form>
        <div id="negotiationreportFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="MsNegotiationReport.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="msApp.toExcel('negotiationreportTbl','')">Excel</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px"  plain="true" id="save" onclick="MsNegotiationReport.getbuyerfollowup()">Buyer Follow Up</a>
        </div>
    </div>
    </div>
    <div data-options="region:'center',border:true,title:'Negotiation Report'" style="padding:2px">
        <table id="negotiationreportTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'company_name',halign:'center'" width="60">1<br/>Company</th>
        <th data-options="field:'bank_name',halign:'center'" width="100">2<br/>Bank</th> 
        <th data-options="field:'file_no',halign:'center'" width="60" align="right">3<br/>File No</th>   
        <th data-options="field:'buyer_name',halign:'center'" width="100">4<br/>Buyer</th> 
        <th data-options="field:'submission_date',halign:'center'" align="center" width="80">5<br/>Submission <br/> Date</th> 
        <th data-options="field:'invoice_value',halign:'center'" align="right"  width="80">6<br/>Submited <br/> Invoice Value</th> 
        <th data-options="field:'deduction',halign:'center'" align="right" width="80">7<br/>Deductions</th> 
        <th data-options="field:'local_commission_invoice',halign:'center'" align="right" width="80">8<br/>Local <br/>Commision</th> 
        <th data-options="field:'foreign_commission_invoice',halign:'center'" align="right" width="80">9<br/>Foreign <br/>Commision</th> 
        <th data-options="field:'freight_invoice',halign:'center'" align="right" width="80">10<br/>Freight</th> 
        <th data-options="field:'net_invoice_value',halign:'center'" align="right" width="80">11<br/>Net Invoice <br/>Value</th> 
        <th data-options="field:'fc_held_btb_lc',halign:'center'" align="right" width="80">12<br/>FC Held <br/>BB LC</th> 
        <th data-options="field:'erq_account',halign:'center'" align="right" width="80">13<br/>ERQ <br/>Account</th> 
        <th data-options="field:'packaing_credit',halign:'center'" align="right" width="80">14<br/>Packing Credit</th> 
        <th data-options="field:'cost_of_packaing_credit',halign:'center'" align="right" width="80">15<br/>Cost of <br/> Packing Credit</th> 
        <th data-options="field:'mda_normal',halign:'center'" align="right" width="80">16<br/>MDA Normal</th> 
        <th data-options="field:'inc_int_pur',halign:'center'" align="right" width="80">17<br/>Int. on Doc<br/> Purchase</th> 
        <th data-options="field:'source_tax',halign:'center'" align="right" width="80">18<br/>Source Tax</th> 
        <th data-options="field:'frg_bank_charge',halign:'center'" align="right" width="80">19<br/>Foreign Bank<br/> Charge</th> 
        <th data-options="field:'current_account',halign:'center'" align="right" width="80">20<br/>Current <br/>Account</th> 
        
        <th data-options="field:'rp_lc_sc_value',halign:'center'" align="right" width="80">21<br/>Contract <br/>Value</th> 
        <th data-options="field:'di_lc_sc_value',halign:'center'" align="right" width="80">22<br/>Direct<br/> LC Value</th> 
        <th data-options="field:'file_value',halign:'center'" align="right" width="80">23<br/>File <br/>Value</th> 
        <th data-options="field:'foreign_commission_file',halign:'center'" align="right" width="80">24<br/>F. Commn</th> 
        <th data-options="field:'local_commission_file',halign:'center'" align="right" width="80">25<br/>L. Commn</th> 
        <th data-options="field:'freight_file',halign:'center'" align="right" width="80">26<br/>Freight</th> 
        <th data-options="field:'net_value_file',halign:'center'" align="right" width="80">27<br/>Net <br/>Value</th> 
        <th data-options="field:'btb_openable',halign:'center'" align="right" width="80">28<br/>BTB LC <br/>Openable</th> 
        <th data-options="field:'btb_open_amount',halign:'center'" align="right" width="80">29<br/>BTB LC <br/>Opened</th> 
        <th data-options="field:'btb_open_amount_per',halign:'center'" align="right" width="80">30<br/>BTB LC <br/>Opened %</th> 
        <th data-options="field:'yet_to_btb_opened',halign:'center'" align="right" width="80">31<br/>Yet To BTB <br/>LC Open</th> 
        <th data-options="field:'pc_taken_mount',halign:'center'" align="right" width="80">32<br/>Packing <br/>Credit Taken</th> 
        <th data-options="field:'pc_taken_amount_per',halign:'center'" align="right" width="80">33<br/>Packing <br/>Credit %</th> 
        <th data-options="field:'pc_taken_rate',halign:'center'" align="right" width="80">34<br/>Cost of <br/>Packing Credit</th>

        <th data-options="field:'bank_ref_bill_no',halign:'center'" align="left" width="80">35<br/>Bank Bill No</th>
        <th data-options="field:'bnk_to_bnk_cour_no',halign:'center'" align="left" width="80">36<br/>Bank to Bank<br/>Cour.No</th>
        <th data-options="field:'bnk_to_bnk_cour_date',halign:'center'" align="left" width="80">37<br/>Bank to Bank<br/>Cour.Date</th>
        <th data-options="field:'bank_ref_date',halign:'center'" align="center" width="80">38<br/>Bank Ref Date</th> 
        <th data-options="field:'ad_pu_amount',halign:'center'" align="right" width="100">39<br/>Adjust <br/>Purchase Amount</th> 
        <th data-options="field:'btbm_built',halign:'center'" align="right" width="100">40<br/>BTB Margin <br/> Built</th> 
        <th data-options="field:'erq_cr',halign:'center'" align="right" width="100">41<br/>ERQ <br/> Credited</th> 
        <th data-options="field:'pc_ad',halign:'center'" align="right" width="100">42<br/>PC <br/> Adjusted</th> 
        <th data-options="field:'mdan_cr',halign:'center'" align="right" width="100">43<br/>MDA Normal <br/> Credited</th> 
        <th data-options="field:'mdas_cr',halign:'center'" align="right" width="100">44<br/>MDA Special <br/> Credited</th> 
        <th data-options="field:'mdau_cr',halign:'center'" align="right" width="100">45<br/>MDA UR <br/> Credited</th> 
        <th data-options="field:'sct_deduct',halign:'center'" align="right" width="100">46<br/>Source Tax <br/> Deducted</th> 
        <th data-options="field:'fbc_ad',halign:'center'" align="right" width="100">47<br/>Foreign Bank <br/> Charge</th> 
        <th data-options="field:'cda_cr',halign:'center'" align="right" width="100">48<br/>CD Account</th> 
        <th data-options="field:'commi_cr',halign:'center'" align="right" width="100">49<br/>Buyer Commision</th> 
        <th data-options="field:'exp_docp',halign:'center'" align="right" width="100">50<br/>Interest/<br/> Expense <br/>Doc. Purchase</th> 
        <th data-options="field:'cntrl_fund',halign:'center'" align="right" width="100">51<br/>Central/<br/> Fund</th> 
        <th data-options="field:'oth_crg',halign:'center'" align="right" width="100">52<br/>Other/<br/> Charge</th> 
        <th data-options="field:'exch_vari',halign:'center'" align="right" width="100">53<br/>Currency/<br/> Exchange</th> 
        <th data-options="field:'disc_cr',halign:'center'" align="right" width="100">54<br/>Discount</th> 
        <th data-options="field:'sht_rlz',halign:'center'" align="right" width="100">55<br/>Short Realization</th> 
        <th data-options="field:'discrip_cr',halign:'center'" align="right" width="100">56<br/>Discripency</th> 
        <th data-options="field:'total_cr',halign:'center'" align="right" width="100">57<br/>Total</th> 
        <th data-options="field:'possible_realization_date',halign:'center'" align="center" width="100">58<br/>Realizable<br/> Date</th> 
        <th data-options="field:'over_due_days',halign:'center'" align="right" width="100">59<br/>Overdue<br/> Days</th> 

        <th data-options="field:'id',halign:'center'" align="center" width="80">60<br/>System ID</th> 
        <th data-options="field:'',halign:'center'" align="left" width="80" formatter="MsNegotiationReport.formatDetail">Nego</th>
        </tr>
        </thead>
    </table>
    </div>
    <div data-options="region:'east',border:true,collapsed:true,hideCollapsedContent:false,title:'Summary'" style="width:500px; padding:2px">
        <table id="negotiationreportbuyerTbl" style="width:100%" toolbar="#negotiationreportbuyerTblFt">
        <thead>
        <th data-options="field:'buyer_name',halign:'center'" align="left" width="100">Buyer</th>
        <th data-options="field:'net_invoice_value',halign:'center'" align="right" width="100">Net Invoice Value</th>
        <th data-options="field:'total_cr',halign:'center'" align="right" width="100">Realized Amount</th>
        <th data-options="field:'balance_amount',halign:'center'" align="right" width="100">Balance</th>
        </thead>
        </table>
        <div id="negotiationreportbuyerTblFt" >
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" onclick="MsNegotiationReport.getbuyersummery()">Show</a>

        </div>
    </div>
</div>

<div id="buyerfollowupWindow" class="easyui-window" title="Buyer Follow Up Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;"> 
    <table id="buyerfollowupTbl">
        <thead>
            <tr>
                <th data-options="field:'company_name',halign:'center'" width="60">Company</th>
                <th data-options="field:'bank_name',halign:'center'" width="130">Bank</th> 
                <th data-options="field:'file_no',halign:'center'" width="60" align="right">File No</th>   
                <th data-options="field:'buyer_name',halign:'center'" width="100">Buyer</th> 
                <th data-options="field:'submission_date',halign:'center'" align="center" width="80">Submission <br/> Date</th>
                <th data-options="field:'invoice_value',halign:'center'" align="right"  width="80">Submited <br/> Invoice Value</th>
                <th data-options="field:'net_invoice_value',halign:'center'" align="right" width="80">Net Invoice <br/>Value</th>
                <th data-options="field:'bank_ref_bill_no',halign:'center'" align="left" width="120">Bank Bill No</th>
                <th data-options="field:'bnk_to_bnk_cour_no',halign:'center'" align="left" width="80">Bank to Bank<br/>Cour.No</th>
                <th data-options="field:'bnk_to_bnk_cour_date',halign:'center'" align="left" width="80">Bank to Bank<br/>Cour.Date</th>
                <th data-options="field:'bank_ref_date',halign:'center'" align="center" width="80">Bank Ref Date</th>
                <th data-options="field:'possible_realization_date',halign:'center'" align="center" width="100">Realizable<br/> Date</th> 
                <th data-options="field:'over_due_days',halign:'center'" align="right" width="100">Overdue<br/> Days</th>
            </tr>
        </thead>
    </table>  
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsNegotiationReportController.js"></script>
<script>
$(".datepicker" ).datepicker({
    beforeShow:function(input) {
        $(input).css({
            "position": "relative",
            "z-index": 999999
        });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
      });
    $('#negotiationreportFrm [id="buyer_id"]').combobox();
</script>