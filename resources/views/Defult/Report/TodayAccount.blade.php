<div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Accounts'" style="padding:2px">
        <div id="todayaccountdatamatrix">
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#todayAccountFrmft'" style="width:350px; padding:2px">
        <form id="todayAccountFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Date </div>
                            
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="trans_date" id="trans_date" class="datepicker"  placeholder="To" value="<?php echo $date_to; ?>" />
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="todayAccountFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTodayAccount.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTodayAccount.getreceiptpayments()">Receipts & Payments</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTodayAccount.resetForm('todayAccountFrm')" >Reset</a>
            </div>
        </form>
    </div> 
</div>


<div id="todayinflowWindow" class="easyui-window" title="Inflow Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinflowTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'head_code'" width="100">Account Code</th>
            <th data-options="field:'head_name'" width="250">Account Head</th>
            <th data-options="field:'amount'" width="100" align="right">Amount</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<div id="todayrevenueWindow" class="easyui-window" title="Inflow Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayrevenueTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'company_code'" width="100">Company</th>
            <th data-options="field:'bill_no'" width="100">Invoice No</th>
            <th data-options="field:'trans_no'" width="100">JV No</th>
            <th data-options="field:'head_code'" width="100">Account Code</th>
            <th data-options="field:'head_name'" width="250">Account Head</th>
            <th data-options="field:'amount'" width="100" align="right">Amount</th>
            <th data-options="field:'chld_narration'" width="400">Narration</th>
            </tr>
            </thead>
        </table>
    </div>
</div>


<div id="todayreceiptWindow" class="easyui-window" title="Inflow Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayreceiptTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'trans_no'" formatter="MsTodayAccount.formatjournalpdf" width="100">JV No</th>
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

<div id="multipleheadtodayreceiptWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="multipleheadtodayreceiptTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'trans_no'" formatter="MsTodayAccount.formatjournalpdf" width="100">JV No</th>
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

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTodayAccountController.js"></script>
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
