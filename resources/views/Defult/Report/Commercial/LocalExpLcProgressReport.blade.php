<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="localexplcprogressreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th width="100">1</th>
                    <th width="60">2</th>
                    <th width="50">3</th>
                    <th width="80">4</th>
                    <th width="120">5</th>
                    <th width="80">6</th>
                    <th width="70">7</th>
                    <th width="45">8</th>
                    <th width="80">9</th>
                    <th width="60">10</th>
                    <th width="100">11</th>
                    <th width="70">12</th>
                    <th width="70">13</th>
                    <th width="45">14</th>
                    <th width="80">15</th>
                    <th width="80">16</th>
                    <th width="50">17</th>
                    <th width="120">18</th>
                    <th width="80">19</th>
                    <th width="80">20</th>
                    <th width="100">21</th>
                    <th width="80">22</th>
                    <th width="100">23</th>
                    <th width="80">24</th>
                    <th width="50">25</th>
                    <th width="50">26</th>
                </tr>
                <tr>
                    <th data-options="field:'buyer_name',halign:'center'" width="100">Customer</th>
                    <th data-options="field:'company_code',halign:'center'" width="60">Company</th>
                    <th data-options="field:'production_area_id',halign:'center'" align="center" width="50">Prod.<br/>Area</th>
                    <th data-options="field:'lien_bank',halign:'center'" width="80">Lien Bank</th>
                    <th data-options="field:'local_lc_no',halign:'center'" align="center" width="120">LC No</th>
                    <th data-options="field:'lc_date',halign:'center'" width="80" align="center">LC Date</th>
                    <th data-options="field:'pay_term_id',halign:'center'" width="60" align="center">Pay Term</th>
                    <th data-options="field:'tenor'" width="45" align="center">Tenor</th>
                    <th data-options="field:'local_exp_doc_sub_accept_id',halign:'right'" width="80">Acceptance<br/> ID</th>
                    <th data-options="field:'currency_code',halign:'center'" width="60">Currency</th>
                    <th data-options="field:'invoice_value',halign:'right'" width="100" align="right" formatter="MsLocalExpLcProgressReport.formatInvoice">Invoice Value</th>
                    <th data-options="field:'accept_submit_date',halign:'center'" width="70">Sub Date <br/>For Accept</th>
                    <th data-options="field:'accept_receive_date',halign:'center'" width="70">Party<br/>Accept<br/>Date</th>
                    <th data-options="field:'days_taken_to_accept'" width="45" align="center">Days<br/>Taken<br/>To <br/>Accept</th>
                    <th data-options="field:'bank_submit_date'" width="80" align="center">Sub<br/>Date<br/>at Bank</th>
                    <th data-options="field:'maturity_rcv_date'" width="80" align="center">Maturity<br/>Recv.<br/>Date</th>
                    <th data-options="field:'days_taken_to_maturity'" width="50" align="center">Days<br/>Taken<br/>To <br/>Maturity</th>
                    <th data-options="field:'bank_ref_bill_no'" width="120" align="center">Bank<br/>Bill No</th>
                    <th data-options="field:'maturity_date'" width="80" align="center">Maturity<br/>Date</th>
                    <th data-options="field:'place_for_purchase'" width="80" align="center">Placed to<br/>Purchase<br/> Date</th>
                    <th data-options="field:'purchase_amount'" width="100" align="right">Purchase<br/>Amount</th>
                    <th data-options="field:'negotiation_date'" width="80" align="center">Purchase<br/> Date</th>
                    <th data-options="field:'realized_amount'" width="100" align="right">Realized Amount</th>
                    <th data-options="field:'realization_date'" width="80" align="center">Realized<br/>Date</th>
                    <th data-options="field:'overdue_days'" width="50" align="center">Overdue <br/>Days</th>
                    <th data-options="field:'days_taken_to_realized'" width="60" align="center">Days <br/>Taken<br/> to <br/>Realize</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Local Export Bill Progress',footer:'#ft2'" style="width:300px; padding:2px">
        <form id="localexplcprogressreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">Date Range</div>
                            <div class="col-sm-3" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Maturity Date</div>
                            <div class="col-sm-3" style="padding-right:0px">
                                <input type="text" name="maturity_date_from" id="maturity_date_from" class="datepicker" placeholder="  From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="maturity_date_to" id="maturity_date_to" class="datepicker" placeholder="  To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Company</div>
                            <div class="col-sm-7">
                                {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Production Area</div>
                            <div class="col-sm-7">
                                {!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Customer</div>
                            <div class="col-sm-7">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>  
                        <div class="row middle">
                            <div class="col-sm-5">LC No</div>
                            <div class="col-sm-7">
                                <input type="text" name="local_lc_no" id="local_lc_no" value="" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">
                                {!! Form::select('available_doc_id', $availabledocs,'',array('id'=>'available_doc_id')) !!}.
                            </div>
                            <div class="col-sm-7">
                                {!! Form::select('status_id', $yesno,'',array('id'=>'status_id')) !!}
                            </div>
                        </div>      
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpLcProgressReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpLcProgressReport.resetForm('localexplcprogressreportFrm')">Reset</a>
            </div>
        </form>
    </div>
</div>

    
<div id="localexpinvoiceWindow" class="easyui-window" title="Local Export Invoice Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:70%;height:500px;padding:2px;margin:2px;"> 
    <table id="localexpinvoiceprogressTbl">
        <thead>
            <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'local_lc_no'" width="150">Local LC No</th>
                <th data-options="field:'local_invoice_no'" width="150">Invoice No</th>
                {{--  <th data-options="field:'beneficiary'" width="100">Beneficiary</th> --}}
                <th data-options="field:'local_invoice_date'" width="100">Invoice Date</th>
                <th data-options="field:'invoice_qty'" width="100">Invoice Qty</th>
                <th data-options="field:'invoice_amount'" width="100">Invoice Value</th>
                <th data-options="field:'actual_delivery_date'" width="100">Actual Delivery Date</th>
                <th data-options="field:'remarks'" width="100">Remarks</th>
            </tr>
        </thead>
    </table>  
</div>
    
<div id="localtransectionWindow" class="easyui-window" title="Local Export Invoice Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:70%;height:500px;padding:2px;margin:2px;"> 
    <table id="localtransectionTbl">
        <thead>
            <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'local_lc_no'" width="150">Local LC No</th>
                <th data-options="field:'local_invoice_no'" width="150">Invoice No</th>
                {{--  <th data-options="field:'beneficiary'" width="100">Beneficiary</th> --}}
                <th data-options="field:'local_invoice_date'" width="100">Invoice Date</th>
                <th data-options="field:'invoice_qty'" width="100">Invoice Qty</th>
                <th data-options="field:'invoice_amount'" width="100">Invoice Value</th>
                <th data-options="field:'actual_delivery_date'" width="100">Actual Delivery Date</th>
                <th data-options="field:'remarks'" width="100">Remarks</th>
            </tr>
        </thead>
    </table>  
</div>

<div id="openlocallcwindow" class="easyui-window" title="Local Export LC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="localexplcsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">LC No</div>
                                <div class="col-sm-8">
                                   <input type="text" name="local_lc_no" id="local_lc_no" value="" />
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
               <p class="footer">
                   <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsLocalExpLcProgressReport.searchLocalExpLcGrid()">Search</a>
               </p>
           </div>
       </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="localexplcsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'local_exp_lc_id'" width="80">ID</th>
                        <th data-options="field:'local_lc_no'" width="100">LC No</th>
                        <th data-options="field:'beneficiary_id'" width="100">Beneficiary</th>
                        <th data-options="field:'buyer_id'" width="100">Buyer</th>
                        <th data-options="field:'lc_date'" width="100">LC Date</th>
                        <th data-options="field:'lc_value'" width="100" align="right">LC value</th>
                        <th data-options="field:'currency'" width="100">Currency</th>
                        <th data-options="field:'exch_rate'" width="100">Exch Rate</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
           <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openlocallcwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
  
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsLocalExpLcProgressReportController.js"></script>
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
    $('#localexplcprogressreportFrm [id="buyer_id"]').combobox();
</script>