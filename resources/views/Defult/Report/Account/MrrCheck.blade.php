<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
     <table id="mrrcheckTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'print'" formatter="MsMrrCheck.formatjournalpdf" width="80"></th>
                <th data-options="field:'trans_no'" width="70">Trans. No</th>
                <th data-options="field:'company_id'" width="70">Company</th>
                <th data-options="field:'trans_type_id'" width="70">Trans. Type</th>
                <th data-options="field:'trans_date'" width="80">Trans. Date</th>
                <th data-options="field:'amount'" width="70" align="right">Amount</th>
                <th data-options="field:'is_locked'" width="70">Is Locked</th>
                <th data-options="field:'user_name'" width="80">Created By</th>
                <th data-options="field:'updated_by'" width="80">Updated By</th>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'narration'">Narration</th>
                
            </tr>
        </thead>
     </table>
    </div>
	<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
		<form id="mrrcheckFrm">
		<div id="container">
		<div id="body">
		<code>
		<div class="row middle">
		<div class="col-sm-4 req-text">MRR No</div>
		<div class="col-sm-8">
		<input type="text" name="mrr_no" id="mrr_no"/>
		</div>
		</div>
		</code>
		</div>
		</div>
		<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
		<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMrrCheck.get()">Show</a>
		<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMrrCheck.resetForm('mrrcheckFrm')" >Reset</a>
		</div>

		</form>
	</div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsMrrCheckController.js"></script>
<script>
 $(".datepicker" ).datepicker({
     dateFormat: 'yy-mm-dd',
     changeMonth: true,
     changeYear: true
 });
 
 $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
 $('#mrrcheckFrm [id="supplier_id"]').combobox();
$('#mrrcheckFrm [id="buyer_id"]').combobox();
</script>