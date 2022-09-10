<div class="easyui-layout"  data-options="fit:true" id="groupSaleContainerLayout">
        <div data-options="region:'center',border:true,title:'Group Sales'" style="padding:2px" id="groupslaehwindowcontainerlayoutcenter">
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#groupsaleFrmFt'" style="width:350px; padding:2px">
            <form id="groupsaleFrm">
                 <div id="container">
                    <div id="body">
                        <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Date Range </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To"/>
                                    </div>
                                </div>
                        </code>
                    </div>
                </div>
                <div id="groupsaleFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsGroupSale.get()">Show</a>
                </div>
            </form> 
        </div>
    </div>

<div id="groupslaedyeingdetailWindow" class="easyui-window" title="Dyeing Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
         <div data-options="region:'west',border:true,title:'In-house'" style="width:750px;padding:2px">
            <table id="groupslaedyeinginhdetailTbl">
                <thead>
                <tr>
                    <th data-options="field:'company_code'" width="70" align="center">Company</th>
                    <th data-options="field:'id'" width="70" align="center" formatter="MsGroupSale.formatInhDyeDc">Challan</th>
                    <th data-options="field:'issue_no'" width="70" align="center" formatter="MsGroupSale.formatInhDyesBill">Bill No</th>
                    <th data-options="field:'issue_date'" width="80" align="center">Bill Date</th>
                    <th data-options="field:'bill_month'" width="80" align="center">Bill Month</th>
                    <th data-options="field:'buyer_name'" width="200">Customer</th>
                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                    <th data-options="field:'amount_bdt'" width="80" align="right">Amount</th>
                </tr>
            </thead>
            </table>
         </div>
        <div data-options="region:'center',border:true,title:'Sub-contact'" style="padding:2px">
            <table id="groupslaedyeingsubdetailTbl">
                <thead>
                <tr>
                    <th data-options="field:'company_code'" width="70" align="center">Company</th>
                    <th data-options="field:'id'" width="70" align="center" formatter="MsGroupSale.formatSubDyeDc">Challan</th>
                    <th data-options="field:'issue_no'" width="70" align="center" formatter="MsGroupSale.formatSubDyeBill">Bill No</th>
                    <th data-options="field:'issue_date'" width="80" align="center">Bill Date</th>
                    <th data-options="field:'bill_month'" width="80" align="center">Bill Month</th>
                    <th data-options="field:'buyer_name'" width="200">Customer</th>
                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                    <th data-options="field:'amount_bdt'" width="100" align="right">Amount</th>
                </tr>
            </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="height:40px;padding:2px">
            <div class="row" style="margin: 0 0; height: 100%;">
                <div class="col-sm-4"></div>
                <div class="col-sm-4" style="padding-right:0px;padding-left:0px; text-align: center; height: 100%; background-color: #ccc;line-height: 50px;vertical-align: middle;">
                    <input type="hidden" name="groupslaeInhTotalAmount" id="groupslaeInhTotalAmount" value="0">
                    <input type="hidden" name="groupslaeSubTotalAmount" id="groupslaeSubTotalAmount" value="0">
                    <b>Total Amount: <span id='groupslaeTotalAmount'></span></span></b>
                </div>
                <div class="col-sm-4" style="padding-left:0px">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="groupslaeaopdetailWindow" class="easyui-window" title="AOP Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
         <div data-options="region:'west',border:true,title:'In-house'" style="width:750px;padding:2px">
            <table id="groupslaeaopinhdetailTbl">
                <thead>
                <tr>
                    <th data-options="field:'company_code'" width="70" align="center">Company</th>
                    <th data-options="field:'id'" width="70" align="center" formatter="MsGroupSale.formatInhAopDc">Challan</th>
                    <th data-options="field:'issue_no'" width="70" align="center" formatter="MsGroupSale.formatInhAopBill">Bill No</th>
                    <th data-options="field:'issue_date'" width="80" align="center">Bill Date</th>
                    <th data-options="field:'bill_month'" width="80" align="center">Bill Month</th>
                    <th data-options="field:'buyer_name'" width="200">Customer</th>
                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                    <th data-options="field:'amount_bdt'" width="80" align="right">Amount</th>
                </tr>
            </thead>
            </table>
         </div>
        <div data-options="region:'center',border:true,title:'Sub-contact'" style="padding:2px">
            <table id="groupslaeaopsubdetailTbl">
                <thead>
                <tr>
                    <th data-options="field:'company_code'" width="70" align="center">Company</th>
                    <th data-options="field:'id'" width="70" align="center" formatter="MsGroupSale.formatSubAopDc">Challan</th>
                    <th data-options="field:'issue_no'" width="70" align="center" formatter="MsGroupSale.formatSubAopBill">Bill No</th>
                    <th data-options="field:'issue_date'" width="80" align="center">Bill Date</th>
                    <th data-options="field:'bill_month'" width="80" align="center">Bill Month</th>
                    <th data-options="field:'buyer_name'" width="200">Customer</th>
                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                    <th data-options="field:'amount_bdt'" width="100" align="right">Amount</th>
                </tr>
            </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="height:40px;padding:2px">
            <div class="row" style="margin: 0 0; height: 100%;">
                <div class="col-sm-4"></div>
                <div class="col-sm-4" style="padding-right:0px;padding-left:0px; text-align: center; height: 100%; background-color: #ccc;line-height: 50px;vertical-align: middle;">
                    <input type="hidden" name="groupslaeaopInhTotalAmount" id="groupslaeaopInhTotalAmount" value="0">
                    <input type="hidden" name="groupslaeaopSubTotalAmount" id="groupslaeaopSubTotalAmount" value="0">
                    <b>Total Amount: <span id='groupslaeaopTotalAmount'></span></span></b>
                </div>
                <div class="col-sm-4" style="padding-left:0px">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="groupslaeknitingdetailWindow" class="easyui-window" title="Knitting Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
         <div data-options="region:'west',border:true,title:'In-house'" style="width:750px;padding:2px">
            <table id="groupslaeknitinginhdetailTbl">
                <thead>
                <tr>
                    <th data-options="field:'company_code'" width="70" align="center">Company</th>
                    <th data-options="field:'id'" width="70" align="center" formatter="MsGroupSale.formatInhKnitingDc">Challan</th>
                    <th data-options="field:'issue_no'" width="70" align="center" formatter="MsGroupSale.formatInhKnitingBill">Bill No</th>
                    <th data-options="field:'issue_date'" width="80" align="center">Bill Date</th>
                    <th data-options="field:'bill_month'" width="80" align="center">Bill Month</th>
                    <th data-options="field:'buyer_name'" width="200">Customer</th>
                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                    <th data-options="field:'amount_bdt'" width="80" align="right">Amount</th>
                </tr>
            </thead>
            </table>
         </div>
        <div data-options="region:'center',border:true,title:'Sub-contact'" style="padding:2px">
            <table id="groupslaeknitingsubdetailTbl">
                <thead>
                <tr>
                    <th data-options="field:'company_code'" width="70" align="center">Company</th>
                    <th data-options="field:'id'" width="70" align="center" formatter="MsGroupSale.formatSubKnitingDc">Challan</th>
                    <th data-options="field:'issue_no'" width="70" align="center" formatter="MsGroupSale.formatSubKnitingBill">Bill No</th>
                    <th data-options="field:'issue_date'" width="80" align="center">Bill Date</th>
                    <th data-options="field:'bill_month'" width="80" align="center">Bill Month</th>
                    <th data-options="field:'buyer_name'" width="200">Customer</th>
                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                    <th data-options="field:'amount_bdt'" width="100" align="right">Amount</th>
                </tr>
            </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="height:40px;padding:2px">
            <div class="row" style="margin: 0 0; height: 100%;">
                <div class="col-sm-4"></div>
                <div class="col-sm-4" style="padding-right:0px;padding-left:0px; text-align: center; height: 100%; background-color: #ccc;line-height: 50px;vertical-align: middle;">
                    <input type="hidden" name="groupslaeknitingInhTotalAmount" id="groupslaeknitingInhTotalAmount" value="0">
                    <input type="hidden" name="groupslaeknitingSubTotalAmount" id="groupslaeknitingSubTotalAmount" value="0">
                    <b>Total Amount: <span id='groupslaeknitingTotalAmount'></span></span></b>
                </div>
                <div class="col-sm-4" style="padding-left:0px">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="groupslaegmtdetailWindow" class="easyui-window" title="Garments Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Garments'" style="padding:2px">
            <table id="groupslaegmtdetailTbl">
                <thead>
                <tr>
                    <th data-options="field:'id'" width="70" align="center">ID</th>
                    <th data-options="field:'company_code'" width="70" align="center">Company</th>
                    <th data-options="field:'invoice_no'" width="70" align="center" formatter="MsGroupSale.formatGmtBill">Invoice No</th>
                    <th data-options="field:'invoice_date'" width="80" align="center">Invoice Date</th>
                    <th data-options="field:'invoice_month'" width="80" align="center">Invoice Month</th>
                    <th data-options="field:'buyer_name'" width="200">Buyer</th>
                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                    <th data-options="field:'amount_bdt'" width="80" align="right">Amount</th>
                </tr>
            </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsGroupSaleController.js"></script>
<script>
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
</script>
