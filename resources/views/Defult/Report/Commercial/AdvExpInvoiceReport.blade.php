<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Advance Export Invoice Status'" style="padding:2px">
        <table id="advexpinvoicereportTbl" style="width:100%">
            <thead>
                <tr>
                    
                    <th data-options="field:'lc_sc_no',halign:'center'" width="100">1<br/>LC/SC NO</th>
                    <th data-options="field:'lc_sc_date',halign:'center'" width="100">2<br/>LC/SC Date </th>
                    <th data-options="field:'invoice_no',halign:'center'" width="100" formatter="MsAdvExpInvoiceReport.formatOrderCIPdf">3<br/>Adv.Invoice No</th>
                    <th data-options="field:'invoice_date',halign:'center',align:'center'" width="100">4<br/>Adv.Invoice Date</th>
                    <th data-options="field:'adv_invoice_qty',halign:'center'" width="80" align="right"  >5<br/>Adv.CI<br/> Qty</th>
                    <th data-options="field:'invoice_qty',halign:'center'" width="80" align="right" formatter="MsAdvExpInvoiceReport.formatInvoiceNo">6<br/>Adj.CI<br/>  Qty</th>
                    <th data-options="field:'yet_to_adj_qty',halign:'center'" width="80" align="right"  >7<br/>Yet to<br/>Adj Qty</th>
                    <th data-options="field:'adv_invoice_rate',halign:'center'" width="80" align="right"  >8<br/>Adv.CI<br/>  Rate</th>
                    <th data-options="field:'adv_invoice_amount',halign:'center',"   width="80" align="right">9<br/>Adv.CI<br/> Amount</th>
                    <th data-options="field:'invoice_amount',halign:'center'," width="80" align="right" formatter="MsAdvExpInvoiceReport.formatInvoiceNo"  >10<br/>Adj.CI<br/>  Amount</th>
                    <th data-options="field:'yet_to_adj_amount',halign:'center'" width="80" align="right"  >11<br/>Yet to Adj<br/>  Amount</th>
                    <th data-options="field:'lien_bank',halign:'center'" width="80">12<br/>Lien Bank</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="120">13<br/>Buyer</th>
                    <th data-options="field:'style_ref',halign:'center'" width="100" align="left">14<br/>Style Ref</th>
                    <th data-options="field:'company_id',halign:'center'" align="center" width="70">15<br/>Bnf.Comp</th>
                    <th data-options="field:'pcompany',halign:'center'" align="center" width="70">16<br/>Prod.Comp</th>
                    <th data-options="field:'sale_order_no',halign:'center'" align="left" width="120" >17<br/>Order No</th>
                    <th data-options="field:'ship_date',halign:'center'" align="left" width="80">18<br/>Ship Date</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#monthlyexpinvoicereportFrmFt'" style="width:350px; padding:2px">
        <form id="advexpinvoicereportFrm">
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
                            <input type="text" name="lc_sc_no" id="lc_sc_no" ondblclick="MsAdvExpInvoiceReport.openLcScWindow()" placeholder=" Double Click">
                            <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">LC/SC Date</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="lc_sc_date_from" id="lc_sc_date_from" class="datepicker"/>
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="lc_sc_date_to" id="lc_sc_date_to" class="datepicker"/>
                        </div>
                    </div>             
                    <div class="row middle">
                        <div class="col-sm-4">Invoice Date</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="invoice_date_from" id="invoice_date_from" class="datepicker" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="invoice_date_to" id="invoice_date_to" class="datepicker" />
                        </div>
                    </div>             
                </code>
            </div>
            </div>
            <div id="monthlyexpinvoicereportFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAdvExpInvoiceReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAdvExpInvoiceReport.showExcel()">XL</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAdvExpInvoiceReport.resetForm('advexpinvoicereportFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>  

<div id="explcscWindow" class="easyui-window" title="Sales Contract/ Export Lc Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="lcscsearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsAdvExpInvoiceReport.searchLcScGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="lcscsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sc_or_lc'" width="70">SC\LC</th>
                         <th data-options="field:'lc_sc_no'" width="100">Contract No</th>
                        <th data-options="field:'lc_sc_value'" width="100">Contract Value</th>
                        <th data-options="field:'lc_sc_nature'" width="100">Contract Nature</th>    
                        <th data-options="field:'lc_sc_date'" width="100"> Contract Date</th> 
                        <th data-options="field:'last_delivery_date'" width="100" align="right"> Last Delivery Date</th>  
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#explcscWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<div id="invWindow" class="easyui-window" title="Adjusted Invoice Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:800px;height:500px;padding:4px;"> 
    <table id="invoiceTbl">
        <thead>
            <tr>
                <th data-options="field:'invoice_no'" width="100px">CI No</th>
                <th data-options="field:'invoice_date'" width="100px">CI Date</th>
                <th data-options="field:'invoice_qty'" width="100px" align="right">CI QTY</th>
                <th data-options="field:'invoice_rate'" width="100px" align="right">CI Rate</th>
                <th data-options="field:'invoice_amount'" width="100px" align="right">CI Amount</th>
            </tr>
        </thead>
    </table>  
</div>

    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsAdvExpInvoiceReportController.js"></script>
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
    $('#advexpinvoicereportFrm [id="buyer_id"]').combobox();
</script>