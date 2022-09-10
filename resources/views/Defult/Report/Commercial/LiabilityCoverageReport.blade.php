<style>
.datagrid-footer .datagrid-row a:link{
    background: #4cae4c;font-weight:bold;color: #fff;
    text-decoration: none;
  }
</style>
<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'west',border:true,title:'Search',footer:'#coaft2'" style="padding:2px; width: 300px">
        <div class="easyui-layout"  data-options="fit:true">
        <form id="explcliabilitysearchFrm">
            <div class="row middle">
                <div class="col-sm-4 req-text">Lien Bank</div>
                <div class="col-sm-8">
                    {!! Form::select('bank_id', $bank,'',array('id'=>'bank_id')) !!}
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">LC No</div>
                <div class="col-sm-8">
                    <input type="text" name="lc_sc_no" id="lc_sc_no" />
                </div>
            </div>
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
                <div class="col-sm-4">Last Ship Date </div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <input type="text" name="last_delivery_date_from" id="last_delivery_date_from" class="datepicker" placeholder="From" />
                </div>
                <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="last_delivery_date_to" id="last_delivery_date_to" class="datepicker"  placeholder="To" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4">File No </div>
                <div class="col-sm-8">
                    <input type="text" name="file_no" id="file_no">
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Beneficiary </div>
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
        </form>
        <div id="coaft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <!-- <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="MsLiabilityCoverageReport.get()">Show</a> -->
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="MsLiabilityCoverageReport.get2()">Show</a>
        </div>
    </div>
    </div>
    <div data-options="region:'center',border:true,title:'Liability Coverage Report'" style="padding:2px">

        <div id="libcodata" style="width: 100%; height: 100%">

        </div>
    </div>
</div>

<div id="libacoreportWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="libacoreportTbl">
    </table>
    <table id="libacoreportTbl2">
    </table>
    <table id="libacoreportTbl3">
    </table>
</div>

<div id="libacoreportbtbopenpoWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="libacoreportbtbopenpoTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'po_no'" width="70" formatter="MsLiabilityCoverageReport.formatimplcpoPdf">PO No</th>
        <th data-options="field:'company_name'" width="60">Company</th>
        <th data-options="field:'supplier_name'" width="60">Supplier</th> 
        <th data-options="field:'amount'" width="60" align="right">Amount</th>   
        <th data-options="field:'currency_name'" width="60">Currency</th> 
        </tr>
        </thead>
    </table>
</div>

<div id="rqbudgetyarnWindow" class="easyui-window" title="Orderwise Yarn Item Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="containerDocWindow" style="width:100%;height:500px;padding:0px;">
        <table id="rqbudgetyarnTbl" style="width:100%;">
            <thead>
                <tr>
                    <th data-options="field:'item_account_id'" width="40">ID</th>
                    <th data-options="field:'yarn_des'" width="250">Yarn Description</th> 
                    <th data-options="field:'yarn_req'" width="80" align="right">BOM Qty</th>   
                    <th data-options="field:'rate'" width="80"  align="right">Rate</th> 
                    <th data-options="field:'req_amount'" width="80"  align="right">BOM Amount</th>  
                    <th data-options="field:'po_qty'" width="80" align="right">PO Qty</th>   
                    <th data-options="field:'po_amount'" width="80"  align="right">PO Amount</th>
                    <th data-options="field:'po_bal_qty'" width="80" align="right">Pending<br>PO Qty</th>   
                    <th data-options="field:'po_bal_amount'" width="80"  align="right">Pending<br>PO Amount</th> 
                    <th data-options="field:'lc_qty'" width="80" align="right">LC Qty</th>   
                    <th data-options="field:'lc_amount'" width="80"  align="right">LC Amount</th>
                    <th data-options="field:'lc_bal_qty'" width="80" align="right">Pending<br>LC Qty</th>   
                    <th data-options="field:'lc_bal_amount'" width="80"  align="right">Pending<br>LC Amount</th>  
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'south',border:false" style="text-align:right;padding:1px 0 0;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="MsLiabilityCoverageReport.showExcel()" style="width:80px">Excel</a>
    </div>
</div>

<div id="rqbudgetyarnWindow2" class="easyui-window" title="File wise Yarn Item Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="containerDocWindow" style="width:100%;height:500px;padding:0px;">
        <table id="rqbudgetyarnTbl2" style="width:100%;">
            <thead>
            <tr>
                <th data-options="field:'item_account_id'" width="40">ID</th>
                <th data-options="field:'yarn_des'" width="250">Yarn Description</th> 
                <th data-options="field:'yarn_req'" width="80" align="right">Yarn Req Qty</th>   
                <th data-options="field:'rate'" width="80"  align="right">Rate</th> 
                <th data-options="field:'req_amount'" width="80"  align="right">Yarn Req Amount</th>  
                <th data-options="field:'po_qty'" width="80" align="right">PO Qty</th>   
                <th data-options="field:'po_amount'" width="80"  align="right">PO Amount</th>
                <th data-options="field:'po_bal_qty'" width="80" align="right">Pending<br>PO Qty</th>   
                <th data-options="field:'po_bal_amount'" width="80"  align="right">Pending<br>PO Amount</th> 
                <th data-options="field:'lc_qty'" width="80" align="right">LC Qty</th>   
                <th data-options="field:'lc_amount'" width="80"  align="right">LC Amount</th>
                <th data-options="field:'lc_bal_qty'" width="80" align="right">Pending<br>LC Qty</th>   
                <th data-options="field:'lc_bal_amount'" width="80"  align="right">Pending<br>LC Amount</th> 
            </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'south',border:false" style="text-align:right;padding:1px 0 0;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="MsLiabilityCoverageReport.showExcel()" style="width:80px">Excel</a>
    </div>
</div>

<div id="rqbudgetfinfabWindow" class="easyui-window" title="Orderwise  Fabric Cost Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="containerDocWindow" style="width:100%;height:500px;padding:0px;">
        <table id="rqbudgetfinfabTbl" style="width:100%;">
            <thead>
                <tr>
                   
                    <th data-options="field:'fabrication'" width="250">Fabric Description</th> 
                    <th data-options="field:'fin_fab_req'" width="80" align="right">Fin Qty</th>   
                    <th data-options="field:'fin_fab_rate'" width="80"  align="right">Rate</th> 
                    <th data-options="field:'fin_fab_req_amount'" width="80"  align="right">Fin Amount</th>  
                    <th data-options="field:'po_qty'" width="80" align="right">PO Qty</th>   
                    <th data-options="field:'po_amount'" width="80"  align="right">PO Amount</th>
                    <th data-options="field:'po_bal_qty'" width="80" align="right">Pending<br>PO Qty</th>   
                    <th data-options="field:'po_bal_amount'" width="80"  align="right">Pending<br>PO Amount</th> 
                    <th data-options="field:'lc_qty'" width="80" align="right">LC Qty</th>   
                    <th data-options="field:'lc_amount'" width="80"  align="right">LC Amount</th>
                    <th data-options="field:'lc_bal_qty'" width="80" align="right">Pending<br>LC Qty</th>   
                    <th data-options="field:'lc_bal_amount'" width="80"  align="right">Pending<br>LC Amount</th>  
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'south',border:false" style="text-align:right;padding:1px 0 0;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="msApp.toExcel('rqbudgetfinfabTbl','Fabric Cost.xls')" style="width:80px">Excel</a>
    </div>
</div>

<div id="rqbudgetfinfabWindow2" class="easyui-window" title="File wise Fabric Cost Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="containerDocWindow" style="width:100%;height:500px;padding:0px;">
        <table id="rqbudgetfinfabTbl2" style="width:100%;">
            <thead>
                <tr>
                    <th data-options="field:'fabrication'" width="250">Fabric Description</th> 
                    <th data-options="field:'fin_fab_req'" width="80" align="right">Budget Qty</th>   
                    <th data-options="field:'fin_fab_rate'" width="80"  align="right">Budget Rate</th> 
                    <th data-options="field:'fin_fab_req_amount'" width="80"  align="right">Budget  Amount</th>  
                    <th data-options="field:'po_qty'" width="80" align="right">PO Qty</th>   
                    <th data-options="field:'po_amount'" width="80"  align="right">PO Amount</th>
                    <th data-options="field:'po_bal_qty'" width="80" align="right">Pending<br>PO Qty</th>   
                    <th data-options="field:'po_bal_amount'" width="80"  align="right">Pending<br>PO Amount</th> 
                    <th data-options="field:'lc_qty'" width="80" align="right">LC Qty</th>   
                    <th data-options="field:'lc_amount'" width="80"  align="right">LC Amount</th>
                    <th data-options="field:'lc_bal_qty'" width="80" align="right">Pending<br>LC Qty</th> 
                    <th data-options="field:'lc_bal_amount'" width="80"  align="right">Pending<br>LC Amount</th> 
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'south',border:false" style="text-align:right;padding:1px 0 0;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="msApp.toExcel('rqbudgetfinfabTbl2','Fabric Cost.xls')" style="width:80px">Excel</a>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsLiabilityCoverageReportController.js"></script>
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
    $('#explcliabilitysearchFrm [id="buyer_id"]').combobox();
</script>





