<div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Receipts & Payments'" style="padding:2px">
        <div id="receiptspaymentsaccountdatamatrix">
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#receiptspaymentsAccountFrmft'" style="width:350px; padding:2px">
        <form id="receiptspaymentsAccountFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Date </div>
                            
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="trans_date_from" id="trans_date_from" class="datepicker"  placeholder="From" value="<?php echo $date_to; ?>" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="trans_date_to" id="trans_date_to" class="datepicker"  placeholder="To" value="<?php echo $date_to; ?>" />
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="receiptspaymentsAccountFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsReceiptsPaymentsAccount.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsReceiptsPaymentsAccount.resetForm('receiptspaymentsAccountFrm')" >Reset</a>
            </div>
        </form>
    </div> 
</div>




<div id="receiptWindow" class="easyui-window" title="Inflow Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="receiptTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'trans_no'" formatter="MsReceiptsPaymentsAccount.formatjournalpdf" width="100">JV No</th>
            <th data-options="field:'head_code'" width="100">Account Code</th>
            <th data-options="field:'head_name'" width="250">Account Head</th>
            <th data-options="field:'party_name'" width="250">Party</th>
            <th data-options="field:'amount'" width="100" align="right">Amount</th>
            <th data-options="field:'chld_narration'" width="400">Narration</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<div id="multipleheadreceiptWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="multipleheadreceiptTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'trans_no'" formatter="MsReceiptsPaymentsAccount.formatjournalpdf" width="100">JV No</th>
            <th data-options="field:'head_code'" width="100">Account Code</th>
            <th data-options="field:'head_name'" width="250">Account Head</th>
            <th data-options="field:'party_name'" width="150">Party</th>
            <th data-options="field:'debit_amount'" width="100" align="right">Debit</th>
            <th data-options="field:'credit_amount'" width="100" align="right">Credit</th>
            <th data-options="field:'pay_amount'" width="100" align="right">Paid Amount</th>
            <th data-options="field:'chld_narration'" width="400">Narration</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsReceiptsPaymentsAccountController.js"></script>
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
</script>
