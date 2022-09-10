<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Chart Of Accounts',footer:'#coaft2'" style="padding:2px">
        <table  border="1" id="coaTbl">
        <thead>
            <tr>
                <th width="70" data-options="field:'code',halign:'center'">A/C Code</th>
                <th width="50" data-options="field:'name',halign:'center'">Head Name</th>
                <th width="90" data-options="field:'root_id',halign:'center'">Report Head</th>
                <th width="50" data-options="field:'sub_group_name',halign:'center'">Sub Group</th>
                <th width="80" data-options="field:'accchartgroup',halign:'center'">Main Group</th>
                <th width="80" data-options="field:'statement_type_id',halign:'center'">Statement Type</th>
                <th width="100" data-options="field:'control_name_id',halign:'center'">Control Name</th>
                <th width="80" data-options="field:'currency_id',halign:'center'">Currency</th>
                <th width="80" data-options="field:'other_type_id',halign:'center'">Other Type</th>
                <th width="60" data-options="field:'status',halign:'center'">Status</th>
                <th width="60" data-options="field:'normal_balance_id',halign:'center'">Normal Balance</th>
                <th width="60" data-options="field:'is_cm_expense',halign:'center'">CM Expense</th>
                <th width="60" data-options="field:'expense_type_id',halign:'center'">Expense Type</th>
            </tr>
        </thead>
        </table>
        <div id="coaft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="msApp.toExcel('coaTbl','')">Excel</a>
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCoa.pdf()">Pdf</a>
    </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsCoaController.js"></script>
<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});
</script>





