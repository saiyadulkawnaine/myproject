<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Expense Statement',footer:'#ft3'" style="padding:2px" id="expensetatementcontainer">

</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
<form id="expensestatementFrm">
    <div id="container">
         <div id="body">
            <code>
                <div class="row">
                    <div class="col-sm-4 req-text">Company </div>
                    <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Date Range</div>
                    <div class="col-sm-4" style="padding-right:0px">
                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Profit Center</div>
                    <div class="col-sm-8">
                        {!! Form::select('profitcenter_id', $profitcenter,'',array('id'=>'profitcenter_id')) !!}
                    </div>
                </div>
            </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpenseStatement.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpenseStatement.pdf()">Pdf</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpenseStatement.resetForm()" >Reset</a>
    </div>

  </form>
</div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsExpenseStatementController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

