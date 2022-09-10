<style>
.datagrid-footer .datagrid-row a:link{
    background: #4cae4c;font-weight:bold;color: #fff;
    text-decoration: none;
  }
</style>
<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'west',border:true,title:'Search',footer:'#importconsignmentreportFrmft'" style="padding:2px; width: 300px">
        <div class="easyui-layout"  data-options="fit:true">
            <form id="importconsignmentreportFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row middle">
                                <div class="col-sm-4">LC Date </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Company </div>
                                <div class="col-sm-8">
                                    {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id'))
                                    !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Supplier</div>
                                <div class="col-sm-8">
                                    {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Issuing Bank</div>
                                <div class="col-sm-8">
                                    {!! Form::select('issuing_bank_branch_id', $bankbranch,'',array('id'=>'issuing_bank_branch_id')) !!}
                                </div>
                            </div>

                            <div class="row middle">
                                <div class="col-sm-4">Item</div>
                                <div class="col-sm-8">
                                    {!! Form::select('menu_id', $menu,'',array('id'=>'menu_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">LC Type</div>
                                <div class="col-sm-8">
                                    {!! Form::select('lc_type_id', $lctype,'',array('id'=>'lc_type_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">LC No </div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_no" id="lc_no">
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
            </form>
            <div id="importconsignmentreportFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="MsImportConsignmentReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImportConsignmentReport.resetForm('importconsignmentreportFrm')">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="MsImportConsignmentReport.getbankpending()">Bank Pending</a>
            </div>
        </div>
    </div>
    <div data-options="region:'center',border:true,title:'Import Consignment Report'" style="padding:2px">
        <table id="importconsignmentreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th align="center" width="130" >1</th>
                    <th align="center" width="130">2</th>
                    <th align="center" width="100">3</th>
                    <th align="center" width="80">4</th>
                    <th align="center" width="80">5</th>
                    <th align="center" width="80">6</th>
                    <th align="center" width="60">7</th>
                    <th align="center" width="200">8</th>
                    <th align="center" width="80">9</th> 
                    <th align="center" width="80">10</th> 
                    <th align="center" width="80">11</th>  
                    <th width="220" align="left">12</th>   
                    <th align="center" width="150">13</th> 
                    <th align="center" width="80">14</th> 
                    <th align="center" width="80">15</th> 
                    <th align="center" width="60">16</th> 
                    <th align="center" width="60">17</th> 
                    <th align="center" width="80">18</th> 
                    <th align="center" width="80">19</th> 
                    <th align="center" width="80">20</th> 
                    <th align="center" width="80">21</th> 
                    <th align="center" width="80">22</th> 
                    <th align="center" width="80">23</th> 
                    <th align="center" width="80">24</th> 
                    <th align="center" width="80">25</th> 
                    <th align="center" width="80">26</th> 
                    <th align="center" width="80">27</th> 
                    <th align="center" width="80">28</th> 
                    <th align="center" width="80">29</th> 
                    <th align="center" width="80">30</th> 
                    <th align="center" width="80">31</th> 
                    <th align="center" width="80">32</th> 
                    <th align="center" width="80">33</th> 
                    <th align="center" width="80">34</th> 
                    <th align="center" width="80">35</th> 
                    <th align="center" width="80">36</th> 
                    <th align="center" width="80">37</th> 
                    <th align="center" width="80">38</th> 
                    <th align="center" width="80">39</th> 
                    <th align="center" width="80">40</th> 
                    <th align="center" width="80">41</th> 
                    <th align="center" width="80">42</th> 
                    <th align="center" width="80">43</th> 
                    <th align="center" width="80">44</th> 
                    <th align="center" width="80">45</th> 
                    <th align="center" width="80">46</th> 
                    <th align="center" width="80">47</th> 
                    <th align="center" width="80">48</th> 
                    <th align="center" width="80">49</th> 
                    <th align="center" width="80">50</th> 
                    <th align="center" width="80">51</th> 
                    <th align="center" width="80">52</th> 
                    <th align="center" width="80">53</th> 
                    <th align="center" width="150">54</th> 
                </tr>
                <tr>
                    <th data-options="field:'menu_id',halign:'center'" width="130" formatter="MsImportConsignmentReport.formatImportfile">Item</th>
                    <th data-options="field:'lc_no',halign:'center'" width="130">LC NO</th>
                    <th data-options="field:'file_no',halign:'center'" width="100">File No</th>
                    <th data-options="field:'lc_application_date',halign:'center'" width="80" align="center">Applied Date</th>
                    <th data-options="field:'lc_date',halign:'center'" width="80" align="center">LC Date</th>
                    <th data-options="field:'days_taken_to_apply',halign:'center'" width="80" align="center">Days Taken to Apply</th>
                    <th data-options="field:'company_name',halign:'center'" width="60">Importer</th>
                    <th data-options="field:'supplier_name',halign:'center'" width="200">Supplier</th> 
                    <th data-options="field:'lc_amount',halign:'center'" align="right" width="80">LC Value</th> 
                    <th data-options="field:'currency_code',halign:'center'" align="center" width="80">Currency</th> 
                    <th data-options="field:'lctype',halign:'center'" align="center" width="80">LC Type</th>
                    <th data-options="field:'bankbranch',halign:'center'" width="220" align="left">Issuing Bank<br/> Branch</th>
                    <th data-options="field:'invoice_no',halign:'center'" align="center" width="150">Invoice No</th> 
                    <th data-options="field:'invoice_date',halign:'center'" align="center"  width="80">Invoice <br/>Date</th> 
                    <th data-options="field:'doc_value',halign:'center'" align="right"  width="80">Invoice Value</th> 
                    <th data-options="field:'payterm',halign:'center'" align="center" width="60">Pay Term</th> 
                    <th data-options="field:'tenor',halign:'center'" align="center" width="60">Tenure</th> 
                    <th data-options="field:'paln_shipment_date',halign:'center'" align="center" width="80">Plan Ship <br/>Date</th> 
                    <th data-options="field:'actual_shipment_date',halign:'center'" align="center" width="80">Actual <br/>Ship Date</th> 
                    <th data-options="field:'docstatus',halign:'center'" align="center" width="80">Doc. Status</th> 
                    <th data-options="field:'copy_doc_rcv_date',halign:'center'" align="center" width="80">Copy Doc.<br/> Receive Date</th> 
                    <th data-options="field:'original_doc_rcv_date',halign:'center'" align="center" width="80">Org. Doc. <br/>Receive Date</th> 
                    <th data-options="field:'company_accep_date',halign:'center'" align="center" width="80">Company <br/>Accept Date</th> 
                    <th data-options="field:'bank_accep_date',halign:'center'" align="center" width="80">Bank <br/>Accept Date</th> 
                    <th data-options="field:'days_taken_to_accept',halign:'center'" align="center" width="80">Days Taken <br/>To Accept</th> 

                    <th data-options="field:'bank_ref',halign:'center'" align="center" width="80">Bank Ref.</th> 
                    <th data-options="field:'commercial_head_id',halign:'center'" align="center" width="80">Means of <br/>Retirement</th> 
                    <th data-options="field:'loan_ref',halign:'center'" align="center" width="80">Loan Ref</th> 
                    <th data-options="field:'maturity_date',halign:'center'" align="center" width="80">Maturity Date</th> 
                    
                    <th data-options="field:'rate',halign:'center'" align="right" width="80">Interest Rate</th> 
                    <th data-options="field:'payment_date',halign:'center'" align="center" width="80">Paid Date</th> 
                    <th data-options="field:'paid_amount',halign:'center'" align="right" width="80">Paid Amount</th> 
                    <th data-options="field:'overdue',halign:'center'" align="right" width="80">Deviation</th> 
                    <th data-options="field:'shipment_mode',halign:'center'" align="center" width="80">Ship Mode</th> 
                    <th data-options="field:'bl_cargo_no',halign:'center'" align="center" width="80">BL/Cargo No</th> 
                    <th data-options="field:'bl_cargo_date',halign:'center'" align="center" width="80">BL/Cargo <br/>Date</th> 
                    <th data-options="field:'doc_to_cf_date',halign:'center'" align="center" width="80">Doc To C&F </th> 
                    <th data-options="field:'port_of_loading',halign:'center'" align="center" width="80">Port Of Loading</th> 
                    <th data-options="field:'feeder_vessel',halign:'center'" align="center" width="80">Feeder Vessel</th> 
                    <th data-options="field:'mother_vessel',halign:'center'" align="center" width="80">Mother Vessel</th> 
                    <th data-options="field:'eta_date',halign:'center'" align="center" width="80">ETA Date</th> 
                    <th data-options="field:'discharge_date',halign:'center'" align="center" width="80">Discharge <br/>Date</th> 
                    <th data-options="field:'port_of_discharge',halign:'center'" align="center" width="80">Port Of <br/>Discharge</th> 
                    <th data-options="field:'ic_received_date',halign:'center'" align="center" width="80">IC Received<br/> Date</th> 
                    <th data-options="field:'port_clearing_date',halign:'center'" align="center" width="80">Port Clearing <br/>Date</th> 
                    <th data-options="field:'shipping_bill_no',halign:'center'" align="center" width="80">Shipping <br/>Bill No</th> 
                    <th data-options="field:'incoterm',halign:'center'" align="center" width="80">Incoterm</th> 
                    <th data-options="field:'incoterm_place',halign:'center'" align="center" width="80">Incoterm <br/>Place</th> 
                    <th data-options="field:'internal_file_no',halign:'center'" align="center" width="80">Internal <br/>File No</th> 
                    <th data-options="field:'bill_of_entry_no',halign:'center'" align="center" width="80">Bill Of <br/>Entry No</th> 
                    <th data-options="field:'psi_ref_no',halign:'center'" align="center" width="80">PSI <br/>Reference No</th> 
                    <th data-options="field:'container_no',halign:'center'" align="center" width="80">Container No</th> 
                    <th data-options="field:'qty',halign:'center'" align="right" width="80">Pakt. Qty</th> 
                    
                    <th data-options="field:'remarks',halign:'center'" align="left" width="150">Remarks</th> 
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="importfilewindow" class="easyui-window" title="Import LC Uploaded Files" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="importfileTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="30">ID</th>
                <th data-options="field:'file_src'" width="180px">File Source </th>
                <th data-options="field:'original_name'" formatter="MsImportConsignmentReport.formatShowFile" width="250px">Original Name</th>
            </tr>
        </thead>
    </table>
</div>
{{-- Bank Support Pending --}}
<div id="containerDocWindow" class="easyui-window" title="Bank Support Pending Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:800px;height:100%;padding:2px;">
    <div id="bankpendingWindow" style="padding:2px;">

    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsImportConsignmentReportController.js"></script>
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
$('#importconsignmentreportFrm [id="supplier_id"]').combobox();
</script>