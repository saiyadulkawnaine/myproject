<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Receivable Report'" style="padding:2px" id="receivablecontainer">
    
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#receivableFrmFt'" style="width:350px; padding:2px">
<form id="receivableFrm">
    <div id="container">
         <div id="body">
           <code>
                <div class="row middle">
                    <div class="col-sm-4">As On </div>
                    
                    <div class="col-sm-8">
                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d') ?>"  />
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Buyer </div>
                    <div class="col-sm-8">
                    	<input type="text" name="buyer_name" id="buyer_name"  ondblclick="MsReceivable.openbuyerWindow()" placeholder="Double Click"/>
                    	<input type="hidden" name="buyer_id" id="buyer_id"/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Receivable Head </div>
                    <div class="col-sm-8">
                    <input type="text" name="coa_name" id="coa_name" ondblclick="MsReceivable.codefromWindow()" placeholder="Double Click"  />
                    <input type="hidden" name="coa_id" id="coa_id"/>

                    </div>
                </div>
          </code>
       </div>
    </div>
    <div id="receivableFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsReceivable.get()">Short</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsReceivable.getl()">Long</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsReceivable.getd()">Details</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsReceivable.resetForm('receivableFrm')">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsReceivable.getRequestLetter()">Request Letter</a>
    </div>

  </form>
</div>
</div>

<div id="receivablesearchWindow" class="easyui-window" title="Search Buyer" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'center',border:true,title:'List',footer:'#receivablesearchTblFt'" style="padding:2px">
     <table id="receivablesearchTbl" style="width:100%">
        <thead>
                <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'name'" width="100">Name</th>
                    <th data-options="field:'code'" width="100">Code</th>
                    <th data-options="field:'vendor_code'" width="100">Vendor Code</th>
                    
                </tr>
            </thead>
        </table>
        <div id="receivablesearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
          
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsReceivable.closeBuyerWindow('html')">Close</a>
            


            </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#receivablesearchFrmFt'" style="width:350px; padding:2px">
    <form id="receivablesearchFrm">
        <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle" style="display:none">
                            <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Name </div>
                            <div class="col-sm-8"><input type="text" name="name" id="name" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Code </div>
                            <div class="col-sm-8">
                                <input type="text" name="code" id="code" value="" />
                            </div>
                        </div>
                      
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Vendor Code</div>
                            <div class="col-sm-8"><input type="text" name="vendor_code" id="vendor_code" value=""/></div>
                       </div>
                        
                       
                    </code>
                </div>
            </div>
            <div id="receivablesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsReceivable.buyersearch()">Search</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('receivablesearchFrm')" >Reset</a>
            


            </div>
        </form>
    </div>
    </div>
</div>

<div id="receivablecodefromwindow" class="easyui-window" title="Accounts Code" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#receivablecodefromTblFt'" style="padding:2px">
        <table  border="1" id="receivablecodefromTbl">
        <thead>
        <tr>
        <th width="70" data-options="field:'code',halign:'center'">A/C Code</th>
        <th width="200" data-options="field:'name',halign:'center'">Head Name</th>
        <th width="100" data-options="field:'root_id',halign:'center'">Report Head</th>
        <th width="100" data-options="field:'sub_group_name',halign:'center'">Sub Group</th>
        <th width="100" data-options="field:'accchartgroup',halign:'center'">Main Group</th>
        <th width="80" data-options="field:'statement_type_id',halign:'center'">Statement Type</th>
        <th width="100" data-options="field:'control_name_id',halign:'center'">Control Name</th>
        <th width="80" data-options="field:'currency_id',halign:'center'">Currency</th>
        <th width="80" data-options="field:'other_type_id',halign:'center'">Other Type</th>
        <th width="60" data-options="field:'normal_balance_id',halign:'center'">Normal Balance</th>
        <th width="60" data-options="field:'status',halign:'center'">Status</th>
        </tr>
        </thead>
        </table>
        </div>
        </div>
        <div id="receivablecodefromTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsReceivable.closecodefromWindow()">Close</a>
        </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsReceivableController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

