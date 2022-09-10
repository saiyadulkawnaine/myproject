<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Order wise Material Cost',footer:'#ft3'" style="padding:2px" id="orderwisematerialcostcontainer">
        <table id="orderwisematerialcostTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'invoice_no'" width="80" formatter="MsOrderWiseMaterialCost.formatOrderCIPdf" >Invoice No</th>
                    <th data-options="field:'ex_factory_date'" align="left" width="80">Exfactory Date</th>
                    <th data-options="field:'buyer_name'" width="150">Buyer</th>
                    <th data-options="field:'style_ref'" width="100" align="left">Style Ref</th>
                    <th data-options="field:'sale_order_no'" align="left" width="100" >Order No</th>
                    <th data-options="field:'order_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">Gmt Qty</th>
                    <th data-options="field:'yarn_req'" align="right" width="100">Req.Yarn<br/>Qty</th>
                    <th data-options="field:'issue_qty'" align="right" width="100">Issued.Yarn<br/>Qty</th>
                    <th data-options="field:'issued_per'" align="right" width="100">Issued %</th>
                    <th data-options="field:'net_consumption'" align="right" width="100" formatter="MsOrderWiseMaterialCost.formatPoDtl">Net<br>Consumption</th>
                    <th data-options="field:'net_cons_per_pcs'" align="right" width="100">Net<br>Cons Per Pcs</th>
                    <th data-options="field:'invoice_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">CI Qty</th>
                    <th data-options="field:'invoice_amount'" align="right" width="120">CI Amount</th>
                    <th data-options="field:'yarn_cost'" align="right" width="100">Yarn Cost</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="orderwisematerialcostFrm">
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
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px;')) !!}</div>
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
                        <div class="row middle">
                            <div class="col-sm-4">Exfactory Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="ex_factory_date_from" id="ex_factory_date_from" class="datepicker" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="ex_factory_date_to" id="ex_factory_date_to" class="datepicker" />
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderWiseMaterialCost.showYarnCostExcel()">Yarn</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderWiseMaterialCost.showFabricCostExcel()">Fabric</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderWiseMaterialCost.showKnittingCostExcel()">Knitting</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderWiseMaterialCost.showDyeingCostExcel()">Dyeing</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderWiseMaterialCost.showAopCostExcel()">AOP</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderWiseMaterialCost.showTrimsCostExcel()">Accessories</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsOrderWiseMaterialCost.resetForm()" >Reset</a>
            </div>
        </form>
    </div>
</div>

<div id="orderwisematerialcostfabricWindow" class="easyui-window" data-options="modal:true,closed:true,iconCls:'icon-save'" title="Invoice Wise Fabric Cost Window"  style="width:100%;height:100%;"> 
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="orderwisematerialcostFabricTbl" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th data-options="field:'invoice_no'" width="80" formatter="MsOrderWiseMaterialCost.formatOrderCIPdf" >Invoice No</th>
                    <th data-options="field:'ex_factory_date'" align="left" width="80">Exfactory Date</th>
                    <th data-options="field:'buyer_name'" width="150">Buyer</th>
                    <th data-options="field:'style_ref'" width="100" align="left">Style Ref</th>
                    <th data-options="field:'sale_order_no'" align="left" width="100" >Order No</th>
                    <th data-options="field:'order_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">Gmt Qty</th>
                    <th data-options="field:'fin_fab_req'" align="right" width="100">Req.Fabric<br/>Qty</th>
                    <th data-options="field:'rcv_qty'" align="right" width="100">Receive Qty</th>
                    <th data-options="field:'rcv_per'" align="right" width="100">Receive %</th>
                    <th data-options="field:'net_consumption'" align="right" width="100" formatter="MsOrderWiseMaterialCost.formatPoDtl">Net<br>Consumption</th>
                    <th data-options="field:'net_cons_per_pcs'" align="right" width="100">Net<br>Cons Per Pcs</th>
                    <th data-options="field:'invoice_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">CI Qty</th>
                    <th data-options="field:'invoice_amount'" align="right" width="120">CI Amount</th>
                    <th data-options="field:'fabric_cost'" align="right" width="100">Fabric Cost</th>
                </tr>
            </thead>
        </table> 
    </div> 
</div>

<div id="orderwisematerialcostknitWindow" class="easyui-window" data-options="modal:true,closed:true,iconCls:'icon-save'" title="Invoice Wise Knitting Cost Window"  style="width:100%;height:100%;"> 
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="orderwisematerialcostknitTbl" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th data-options="field:'invoice_no'" width="80" formatter="MsOrderWiseMaterialCost.formatOrderCIPdf" >Invoice No</th>
                    <th data-options="field:'ex_factory_date'" align="left" width="80">Exfactory Date</th>
                    <th data-options="field:'buyer_name'" width="150">Buyer</th>
                    <th data-options="field:'style_ref'" width="100" align="left">Style Ref</th>
                    <th data-options="field:'sale_order_no'" align="left" width="100">Order No</th>
                    <th data-options="field:'order_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">Gmt Qty</th>
                    <th data-options="field:'knit_req'" align="right" width="100">Req.Grey Qty</th>
                    <th data-options="field:'knit_qty'" align="right" width="100">Knitting Qty</th>
                    <th data-options="field:'knitting_per'" align="right" width="100">Knitting %</th>
                    <th data-options="field:'net_consumption'" align="right" width="100" formatter="MsOrderWiseMaterialCost.formatPoDtl">Net Consumption</th>
                    <th data-options="field:'net_cons_per_pcs'" align="right" width="100">Net Cons Per Pcs</th>
                    <th data-options="field:'invoice_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">CI Qty</th>
                    <th data-options="field:'invoice_amount'" align="right" width="120">CI Amount</th>
                    <th data-options="field:'knitting_cost'" align="right" width="100">Knitting Cost</th>
                </tr>
            </thead>
        </table> 
    </div> 
</div>

<div id="orderwisematerialcostdyeingWindow" class="easyui-window" data-options="modal:true,closed:true,iconCls:'icon-save'" title="Invoice Wise Dyeing Cost Window"  style="width:100%;height:100%;"> 
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="orderwisematerialcostdyeingTbl" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th data-options="field:'invoice_no'" width="80" formatter="MsOrderWiseMaterialCost.formatOrderCIPdf" >Invoice No</th>
                    <th data-options="field:'ex_factory_date'" align="left" width="80">Exfactory Date</th>
                    <th data-options="field:'buyer_name'" width="150">Buyer</th>
                    <th data-options="field:'style_ref'" width="100" align="left">Style Ref</th>
                    <th data-options="field:'sale_order_no'" align="left" width="100">Order No</th>
                    <th data-options="field:'order_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">Gmt Qty</th>
                    <th data-options="field:'dyeing_req'" align="right" width="100">Req.Dyeing Qty</th>
                    <th data-options="field:'dyeing_qc_qty'" align="right" width="100">Dyeing Qty</th>
                    <th data-options="field:'dyeing_per'" align="right" width="100">Dyeing %</th>
                    <th data-options="field:'net_consumption'" align="right" width="100" formatter="MsOrderWiseMaterialCost.formatPoDtl">Net Consumption</th>
                    <th data-options="field:'net_cons_per_pcs'" align="right" width="100">Net Cons Per Pcs</th>
                    <th data-options="field:'invoice_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">CI Qty</th>
                    <th data-options="field:'invoice_amount'" align="right" width="120">CI Amount</th>
                    <th data-options="field:'dyeing_cost'" align="right" width="100">Dyeing Cost</th>
                </tr>
            </thead>
        </table> 
    </div> 
</div>

<div id="orderwisematerialcostaopWindow" class="easyui-window" data-options="modal:true,closed:true,iconCls:'icon-save'" title="Invoice Wise AOP Cost Window"  style="width:100%;height:100%;"> 
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="orderwisematerialcostaopTbl" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th data-options="field:'invoice_no'" width="80" formatter="MsOrderWiseMaterialCost.formatOrderCIPdf" >Invoice No</th>
                    <th data-options="field:'ex_factory_date'" align="left" width="80">Exfactory Date</th>
                    <th data-options="field:'buyer_name'" width="150">Buyer</th>
                    <th data-options="field:'style_ref'" width="100" align="left">Style Ref</th>
                    <th data-options="field:'sale_order_no'" align="left" width="100">Order No</th>
                    <th data-options="field:'order_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">Gmt Qty</th>
                    <th data-options="field:'aop_req'" align="right" width="100">Req.AOP Qty</th>
                    <th data-options="field:'aop_qc_qty'" align="right" width="100">AOP Qty</th>
                    <th data-options="field:'aop_per'" align="right" width="100">AOP %</th>
                    <th data-options="field:'net_consumption'" align="right" width="100" formatter="MsOrderWiseMaterialCost.formatPoDtl">Net Consumption</th>
                    <th data-options="field:'net_cons_per_pcs'" align="right" width="100">Net Cons Per Pcs</th>
                    <th data-options="field:'invoice_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">CI Qty</th>
                    <th data-options="field:'invoice_amount'" align="right" width="120">CI Amount</th>
                    <th data-options="field:'aop_cost'" align="right" width="100">AOP Cost</th>
                </tr>
            </thead>
        </table> 
    </div> 
</div>

<div id="orderwisematerialcosttrimsWindow" class="easyui-window" data-options="modal:true,closed:true,iconCls:'icon-save'" title="Invoice Wise Accessories Purchase Cost"  style="width:100%;height:100%;"> 
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="orderwisematerialcosttrimsTbl" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th data-options="field:'invoice_no'" width="80" formatter="MsOrderWiseMaterialCost.formatOrderCIPdf" >Invoice No</th>
                    <th data-options="field:'ex_factory_date'" align="left" width="80">Exfactory Date</th>
                    <th data-options="field:'buyer_name'" width="150">Buyer</th>
                    <th data-options="field:'style_ref'" width="100" align="left">Style Ref</th>
                    <th data-options="field:'sale_order_no'" align="left" width="100">Order No</th>
                    <th data-options="field:'order_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="120">Gmt Qty</th>
                    <th data-options="field:'req_trims_amount'" align="right" width="120">Req. Accessories Cost</th>
                    <th data-options="field:'rcv_trims_per'" align="right" width="120">Accessories Receive %</th>
                    <th data-options="field:'rcv_trims_amount'" align="right" width="100" formatter="MsOrderWiseMaterialCost.formatPoDtl">Receive Amount</th>
                    <th data-options="field:'net_cons_per_pcs'" align="right" width="100">Net Cons Per Pcs</th>
                    <th data-options="field:'invoice_qty',styler:MsOrderWiseMaterialCost.formatQty" align="right" width="100">CI Qty</th>
                    <th data-options="field:'invoice_amount'" align="right" width="120">CI Amount</th>
                    <th data-options="field:'trims_cost'" align="right" width="120">Accessories Cost</th>
                </tr>
            </thead>
        </table> 
    </div> 
</div>

<div id="purchaseorderdtlWindow1" class="easyui-window" data-options="modal:true,closed:true,iconCls:'icon-save'" title="Purchase Order / Work Order Details"  style="width:100%;height:100%;"> 
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="purchaseorderdtlTbl1" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th data-options="field:'purchase_order_id'" align="left" width="80">ID</th>
                    <th data-options="field:'po_no'" width="80">WO No</th>
                    <th data-options="field:'fabric_description'" width="450">Fabric Description</th>
                    <th data-options="field:'po_qty'" align="right" width="100">WO/PO Qty</th>
                    <th data-options="field:'po_rate'" align="right" width="100">WO/PO Rate</th>
                    <th data-options="field:'po_amount'" align="right" width="100">WO/PO Amount</th>
                    <th data-options="field:'exch_rate'" align="right" width="100">Conv.Rate</th>
                    <th data-options="field:'po_amount_bdt'" align="right" width="120">WO/PO Amount-BDT</th>
                </tr>
            </thead>
        </table> 
    </div> 
</div>

<div id="purchaseorderdtlWindow2" class="easyui-window" data-options="modal:true,closed:true,iconCls:'icon-save'" title="Purchase Order Details"  style="width:100%;height:100%;"> 
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="purchaseorderdtlTbl2" style="width:100%;height:100%;">
            <thead>
                <tr>
                    <th data-options="field:'purchase_order_id'" align="left" width="80">ID</th>
                    <th data-options="field:'po_no'" width="80">WO No</th>
                    <th data-options="field:'item_description'" width="450">Item Description</th>
                    <th data-options="field:'po_qty'" align="right" width="100">PO Qty</th>
                    <th data-options="field:'po_rate'" align="right" width="100">PO Rate</th>
                    <th data-options="field:'po_amount'" align="right" width="100">PO Amount</th>
                    <th data-options="field:'exch_rate'" align="right" width="100">Conv.Rate</th>
                    <th data-options="field:'po_amount_bdt'" align="right" width="120">PO Amount-BDT</th>
                </tr>
            </thead>
        </table> 
    </div> 
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsOrderWiseMaterialCostController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $('#orderwisematerialcostFrm [id="buyer_id"]').combobox();
</script>    