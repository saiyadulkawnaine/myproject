<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'General Ledger',footer:'#ft3'" style="padding:2px" id="glcontainer">
   
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
<form id="glotherrefFrm">
    <div id="container">
         <div id="body">
           <code>               
                <div class="row">
                    <div class="col-sm-4 req-text">Company </div>
                    <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','onchange'=>'MsGlOtherRef.getYear(this.value)')) !!}</div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4 req-text">Year </div>
                    <div class="col-sm-8">
                        <select id="acc_year_id" name="acc_year_id" onchange="MsGlOtherRef.getDateRange(this.value)">
                        </select>
                    </div>
                </div>
                
                
                <div class="row middle">
                    <div class="col-sm-4 req-text">Trans Date </div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">Other Reference </div>
                    <div class="col-sm-8">
                    	<input type="text" name="other_ref_name" id="other_ref_name"  ondblclick="MsGlOtherRef.getRefNo()" placeholder=" Double Click"/>
                        <input type="hidden" name="supplier_id" id="supplier_id"/>
                        {{-- ondblclick="MsGlOtherRef.openotherrefWindow()" --}}
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-4">Code </div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <input type="text" name="code_from" id="code_from" ondblclick="MsGlOtherRef.codefromWindow()" placeholder="From:: Double Click" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="code_to" id="code_to"    ondblclick="MsGlOtherRef.codetoWindow()" placeholder="To:: Double Click" />
                    </div>
                </div>
          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlOtherRef.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGlOtherRef.pdf()">Pdf</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGlOtherRef.resetForm()" >Reset</a>
    </div>

  </form>
</div>
</div>
<!--------------------Other Reference Search- Window------------------>
<div id="refsearchWindow" class="easyui-window" title="Other Reference Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="refnosearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'other_ref_no'" width="200">Other Reference No</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#refsearchWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<!--------------------------------------->  

<div id="glotherrefcodefromwindow" class="easyui-window" title="Accounts Code" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table  border="1" id="glotherrefcodefromTbl">
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

<div id="glotherrefcodetowindow" class="easyui-window" title="Accounts Code" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table  border="1" id="glotherrefcodetoTbl">
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

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsGlOtherRefController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

