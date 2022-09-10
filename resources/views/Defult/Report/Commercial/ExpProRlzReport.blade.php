<style>
.datagrid-footer .datagrid-row a:link{
    background: #4cae4c;font-weight:bold;color: #fff;
    text-decoration: none;
  }
</style>
<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'west',border:true,title:'Search',footer:'#expprorlzreportFrmft'" style="padding:2px; width: 300px">
        <div class="easyui-layout"  data-options="fit:true">
            <form id="expprorlzreportFrm">
                <div class="row middle">
                    <div class="col-sm-4">Realization. Date</div>
                    <div class="col-sm-4" style="padding-right:0px">
                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
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
            </form>
            <div id="expprorlzreportFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="MsExpProRlzReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="msApp.toExcel('expprorlzreportTbl','')">Excel</a>
            </div>
        </div>
    </div>
    <div data-options="region:'center',border:true,title:'Realization Report'" style="padding:2px">
        <table id="expprorlzreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'company_name',halign:'center'" width="60">1<br/>Company</th>
                    <th data-options="field:'realization_date',halign:'center'" width="100">2<br/>Realized Date</th> 
                    <th data-options="field:'bank_name',halign:'center'" width="100">3<br/>Bank</th> 
                    <th data-options="field:'file_no',halign:'center'" width="60" align="right">4<br/>File No</th>   
                    <th data-options="field:'buyer_name',halign:'center'" width="100">5<br/>Buyer</th> 
                    <th data-options="field:'submission_date',halign:'center'" align="center" width="80">6<br/>Submission <br/> Date</th> 
                    <th data-options="field:'invoice_value',halign:'center'" align="right"  width="80">7<br/>Gross <br/> Bill Value</th> 
                    <th data-options="field:'deduction',halign:'center'" align="right" width="80">8<br/>Deductions</th>  
                    <th data-options="field:'net_invoice_value',halign:'center'" align="right" width="80">9<br/>Net Bill<br/>Value</th> 
                    <th data-options="field:'bank_ref_bill_no',halign:'center'" align="left" width="80">10<br/>Bank Bill No</th>
                    <th data-options="field:'ad_pu_amount',halign:'center'" align="right" width="100">11<br/>Adjustted <br/>Purchase Amount</th> 
                    <th data-options="field:'pc_ad',halign:'center'" align="right" width="100">12<br/>PC <br/> Adjusted</th> 
                    <th data-options="field:'btbm_built',halign:'center'" align="right" width="100">13<br/>BTB Margin <br/> Built</th> 
                    <th data-options="field:'cda_cr',halign:'center'" align="right" width="100">14<br/>CD Account</th> 
                    <th data-options="field:'fdr_cr',halign:'center'" align="right" width="100">15<br/>FDR</th> 
                    <th data-options="field:'erq_cr',halign:'center'" align="right" width="100">16<br/>ERQ <br/> Credited</th> 
                    <th data-options="field:'mdan_cr',halign:'center'" align="right" width="100">17<br/>MDA Normal <br/> Credited</th> 
                    <th data-options="field:'mdas_cr',halign:'center'" align="right" width="100">18<br/>MDA Special <br/> Credited</th> 
                    <th data-options="field:'mdau_cr',halign:'center'" align="right" width="100">19<br/>MDA UR <br/> Credited</th> 
                    <th data-options="field:'sct_deduct',halign:'center'" align="right" width="100">20<br/>Source Tax <br/> Deducted</th> 
                    <th data-options="field:'fbc_ad',halign:'center'" align="right" width="100">21<br/>Foreign Bank <br/> Charge</th> 
                    <th data-options="field:'commi_cr',halign:'center'" align="right" width="100">22<br/>Buyer Commision</th> 
                    <th data-options="field:'exp_docp',halign:'center'" align="right" width="100">23<br/>Interest/<br/>Expense<br/>Doc.Purchase</th> 
                    <th data-options="field:'cntrl_fund',halign:'center'" align="right" width="100">24<br/>Central/<br/> Fund</th> 
                    <th data-options="field:'oth_crg',halign:'center'" align="right" width="100">25<br/>Other/<br/> Charge</th> 
                    <th data-options="field:'exch_vari',halign:'center'" align="right" width="100">26<br/>Currency/<br/> Exchange</th> 
                    <th data-options="field:'disc_cr',halign:'center'" align="right" width="100">27<br/>Discount</th> 
                    <th data-options="field:'sht_rlz',halign:'center'" align="right" width="100">28<br/>Short Realization</th> 
                    <th data-options="field:'discrip_cr',halign:'center'" align="right" width="100">29<br/>Discripency</th> 
                    <th data-options="field:'total_cr',halign:'center'" align="right" width="100">30<br/>Total</th> 
                    <th data-options="field:'bnk_to_bnk_cour_no',halign:'center'" align="left" width="80">31<br/>Bank to Bank<br/>Cour.No</th>
                    <th data-options="field:'bnk_to_bnk_cour_date',halign:'center'" align="left" width="80">32<br/>Bank to Bank<br/>Cour.Date</th>
                    <th data-options="field:'bank_ref_date',halign:'center'" align="center" width="80">33<br/>Bank Ref Date</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsExpProRlzReportController.js"></script>
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
    $('#expprorlzreportFrm [id="buyer_id"]').combobox();
</script>