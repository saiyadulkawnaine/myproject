<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Monthly Export Invoice'" style="padding:2px">
        <table id="monthlyexpinvoicereportTbl" style="width:100%">
            <thead>
                <tr>
                    <th width="100">1</th>
                    <th width="100">2</th>
                    <th width="100">3</th>
                    <th width="100">4</th>
                    <th width="80">5</th>
                    <th width="80">6</th>
                    <th width="120">7</th>
                    <th width="80">8</th>
                    <th width="100">9</th>
                    <th width="100">10</th>
                    <th width="100">11</th>
                    <th width="100">12</th>
                    <th width="100">13</th>
                    <th width="100">14</th>
                    <th width="80">15</th>
                </tr>

                <tr>
                    
                    <th data-options="field:'company_id',halign:'center'" align="center" width="100">Beneficiary<br/>Company</th>
                    <th data-options="field:'pcompany',halign:'center'" align="center" width="100">Producing<br/>Company</th>
                    <th data-options="field:'lc_sc_no',halign:'center'" width="100">LC/SC NO</th>
                    <th data-options="field:'lc_sc_date',halign:'center'" width="100">LC/SC Date </th>
                    <th data-options="field:'file_no'" width="80">File No</th>
                    <th data-options="field:'lien_bank',halign:'center'" width="80">Lien Bank</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="120">Buyer</th>
                    <th data-options="field:'invoice_no',halign:'center'" width="80" formatter="MsMonthlyExpInvoiceReport.formatOrderCIPdf" >Invoice No</th>
                    <th data-options="field:'invoice_date',halign:'center',align:'center'" width="100">Invoice Date</th>
                    <th data-options="field:'invoice_qty',halign:'center'" width="100" align="right"  >Invoice Qty</th>
                    <th data-options="field:'invoice_rate',halign:'center'" width="100" align="right"  >Invoice Rate</th>
                    <th data-options="field:'invoice_amount',halign:'center',"  width="100" align="right"  >Invoice Amount</th>
                    <th data-options="field:'style_ref',halign:'center'" width="100" align="left">Style Ref</th>
                    <th data-options="field:'sale_order_no',halign:'center'" align="left" width="100" >Order No</th>
                    <th data-options="field:'ship_date',halign:'center'" align="left" width="80">Ship Date</th>
                    
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#monthlyexpinvoicereportFrmFt'" style="width:350px; padding:2px">
        <form id="monthlyexpinvoicereportFrm">
            <div id="container">
                <div id="body">
                <code>
                   
                    <div class="row middle">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Buyer </div>
                        <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">LC/SC No</div>
                        <div class="col-sm-8">
                            <input type="text" name="lc_sc_no" id="lc_sc_no"  />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">LC/SC Date</div>
                        <div class="col-sm-4">
                            <input type="text" name="lc_sc_date_from" id="lc_sc_date_from" class="datepicker"/>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="lc_sc_date_to" id="lc_sc_date_to" class="datepicker"/>
                        </div>
                    </div>             
                    <div class="row middle">
                        <div class="col-sm-4">Invoice Date</div>
                        <div class="col-sm-4">
                            <input type="text" name="invoice_date_from" id="invoice_date_from" class="datepicker" />
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="invoice_date_to" id="invoice_date_to" class="datepicker" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Exfactory Date</div>
                        <div class="col-sm-4">
                            <input type="text" name="ex_factory_date_from" id="ex_factory_date_from" class="datepicker" />
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="ex_factory_date_to" id="ex_factory_date_to" class="datepicker" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Status</div>
                        <div class="col-sm-8">
                            {!! Form::select('invoice_status_id', $invoicestatus,'2',array('id'=>'invoice_status_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Lien bank</div>
                        <div class="col-sm-8">
                            {!! Form::select('exporter_bank_branch_id', $bankbranch,'',array('id'=>'exporter_bank_branch_id')) !!} 
                        </div>
                    </div>           
                </code>
            </div>
            </div>
            <div id="monthlyexpinvoicereportFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMonthlyExpInvoiceReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMonthlyExpInvoiceReport.getInvoiceWise()">InvoiceWise</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMonthlyExpInvoiceReport.resetForm('monthlyexpinvoicereportFrm')" >Reset</a>
            </div>
      </form>
    </div>
    <div data-options="region:'east',border:true,collapsed:true,hideCollapsedContent:false,title:'Summary'" style="width:500px; padding:2px">
        <div class="easyui-tabs" style="width:100%;height:100%; border:none" id="jobtabs">
            <div title="Month" style="padding:1px" data-options="selected:true">
                <table id="monthlyexpinvoicereportMonthTbl" style="width:100%">
                <thead>
                    <th data-options="field:'month',halign:'center'" align="center" width="100">Month</th>
                    <th data-options="field:'no_of_invoice',halign:'center'" align="right" width="60">No Of <br/>Invoice</th>
                    <th data-options="field:'invoice_qty',halign:'center'" align="right" width="80">Qty</th>
                    <th data-options="field:'invoice_amount',halign:'center'" align="right" width="100">Amount</th>
                     <th data-options="field:'net_invoice_amount',halign:'center'" align="right" width="100">Net Invoice Value</th>
                </thead>
                </table>
            </div>
            <div title="Buyer" style="padding:1px" data-options="selected:true">
                <table id="monthlyexpinvoicereportBuyerTbl" style="width:100%">
                    <thead>
                        <th data-options="field:'buyer_name',halign:'center'" align="center" width="100">Buyer</th>
                        <th data-options="field:'no_of_invoice',halign:'center'" align="right" width="60">No Of <br/>Invoice</th>
                        <th data-options="field:'invoice_qty',halign:'center'" align="right" width="80">Qty</th>
                        <th data-options="field:'invoice_amount',halign:'center'" align="right" width="100">Amount</th>
                         <th data-options="field:'net_invoice_amount',halign:'center'" align="right" width="100">Net Invoice Value</th>
                    </thead>
                </table>
            </div>
            <div title="Company" style="padding:1px" data-options="selected:true">
                <table id="monthlyexpinvoicereportCompanyTbl" style="width:100%">
                    <thead>
                        <th data-options="field:'company_name',halign:'center'" align="center" width="100">Company</th>
                        <th data-options="field:'no_of_invoice',halign:'center'" align="right" width="60">No Of <br/>Invoice</th>
                        <th data-options="field:'invoice_qty',halign:'center'" align="right" width="80">Qty</th>
                        <th data-options="field:'invoice_amount',halign:'center'" align="right" width="100">Amount</th>
                         <th data-options="field:'net_invoice_amount',halign:'center'" align="right" width="100">Net Invoice Value</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="invoicewisereportWindow" class="easyui-window" title="Invoice wise Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="invoicewisereportTbl">
        <thead>
            <tr>
                <th data-options="field:'lc_sc_no'" width="150">LC/SC NO</th>
                <th data-options="field:'lc_sc_date'" width="100">LC/SC Date </th>
                <th data-options="field:'file_no'" width="80">File No</th>
                <th data-options="field:'lien_bank'" width="150">Lien Bank</th>
                <th data-options="field:'buyer_name'" width="150">Buyer</th>
                <th data-options="field:'invoice_no'" width="100" formatter="MsMonthlyExpInvoiceReport.formatOrderCIPdf" >Invoice No</th>
                <th data-options="field:'ex_factory_date'" align="left" width="80">Exfactory <br>Date</th>
                <th data-options="field:'invoice_date'" width="100">Invoice<br> Date</th>
                <th data-options="field:'invoice_qty',halign:'center'" width="100" align="right"  >Invoice<br> Qty</th>
                <th data-options="field:'invoice_rate',halign:'center'" width="80" align="right"  >Invoice Rate</th>
                <th data-options="field:'invoice_amount',halign:'center',"  width="100" align="right">Invoice<br> Amount</th>
                <th data-options="field:'currency_code',halign:'center'" width="60">Currency</th>
                <th data-options="field:'stuffing_date'" width="80">Stuffing Date</th>
                <th data-options="field:'net_invoice_amount',halign:'center'" align="right" width="100">Net Invoice<br> Value</th>
                <th data-options="field:'net_wgt_exp_qty'" width="70" align="right">Net Wgt</th>
                <th data-options="field:'gross_wgt_exp_qty'" width="70" align="right">Gross Wgt</th>
                <th data-options="field:'bl_cargo_no'" width="100">BL/Cargo No</th>
                <th data-options="field:'bl_cargo_date'" width="80">BL/Cargo Date</th>
                <th data-options="field:'origin_bl_rev_date'" width="80">Original BL<br>Receive Date</th>
                <th data-options="field:'ic_recv_date'" width="80">IC Receive<br> Date</th>
                <th data-options="field:'ship_mode_id'" width="80">Ship Mode</th>
                <th data-options="field:'etd_port'" width="80">ETD Port</th>
                <th data-options="field:'eta_port'" width="80">ETA Port</th>
                <th data-options="field:'feeder_vessel'" width="120">Feeder <br>Vessel</th>
                <th data-options="field:'mother_vessel'" width="120">Mother <br>Vessel</th>
                <th data-options="field:'port_of_loading'" width="120">Port of <br>Loading</th>
                <th data-options="field:'port_of_discharge'" width="150">Port of <br>Discharge</th>
                <th data-options="field:'total_ctn_qty'" align="right" width="80">Carton Qty</th>
                <th data-options="field:'advice_date'" align="left" width="80">Advise Date</th>
                <th data-options="field:'exp_doc_submission_id'" width="80">Doc <br>Submission ID</th>
                <th data-options="field:'bank_ref_bill_no'" width="80">Bank Ref<br>Bill No</th>
                <th data-options="field:'bank_ref_date'" width="80">Bank Ref<br>Date</th>
            </tr>
        </thead>
    </table>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsMonthlyExpInvoiceReportController.js"></script>
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
    $('#monthlyexpinvoicereportFrm [id="buyer_id"]').combobox();
</script>