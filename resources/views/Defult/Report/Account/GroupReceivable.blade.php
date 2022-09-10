<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Group Receivable Report'" style="padding:2px" id="groupreceivablecontainer">
    
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#receivableFrmFt'" style="width:350px; padding:2px">
<form id="groupreceivableFrm">
    <div id="container">
         <div id="body">
           <code>
               <div class="row middle">
                    <div class="col-sm-4">As On </div>
                    
                    <div class="col-sm-8">
                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d') ?>"  />
                    </div>
                </div>
          </code>
       </div>
    </div>
    <div id="receivableFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGroupReceivableReport.get()">Short</a>
        
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGroupReceivableReport.resetForm()" >Reset</a>
    </div>

  </form>
</div>
</div>

<div id="groupreceivablebuyerWindow" class="easyui-window" title="Garments Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Garments'" style="padding:2px">
            <table id="groupreceivablebuyerTbl">
                <thead>
                <tr>
                    <th data-options="field:'acc_name'" width="350" align="left">Head</th>
                    <th data-options="field:'buyer_name'" width="350" align="left">Buyer</th>
                    <th data-options="field:'company_code'" width="70" align="center">Company</th>
                    <th data-options="field:'amount'" width="100" align="right">Amount</th>
                </tr>
            </thead>
            </table>
        </div>
    </div>
</div>





<script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsGroupReceivableReportController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

