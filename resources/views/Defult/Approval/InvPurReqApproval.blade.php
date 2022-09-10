<div class="easyui-layout"  data-options="fit:true">
	<div data-options="region:'center',border:true" style="padding:2px">
		<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="invPurReqApprovalAccordion">
			@permission('approvefirst.invpurreqs')
			<div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#invpurreqapprovalfirstTblft1'" style="padding:2px">
						<table id="invpurreqapprovalfirstTbl" style="width:100%">
						<thead>
						<tr>
							<th data-options="field:'html',halign:'center'" width="60"  formatter="MsInvPurReqApproval.formatHtmlFirst" align="center">Details</th>
							<th data-options="field:'pdf',halign:'center'" width="40" formatter="MsInvPurReqApproval.formatpdf" align="center">Details</th>
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'requisition_no'" width="100">Requisition NO</th>
							<th data-options="field:'company_id'" width="100">Company</th>
							<th data-options="field:'req_date'" width="100">Requisition Date</th>
							<th data-options="field:'pay_mode'" width="120">Pay Mode</th>
							<th data-options="field:'currency_id'" width="100">Currency</th>
							<th data-options="field:'location_id'" width="100">Location</th>
							<th data-options="field:'remarks'" width="120">Remarks</th>
						</tr>
						</thead>
						</table>
						<div id="invpurreqapprovalfirstTblft1" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvefirst.invpurreqs')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.selectAll('#invpurreqapprovalfirstTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.unselectAll('#invpurreqapprovalfirstTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.approved('firstapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvesecond.invpurreqs')

			<div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#invpurreqapprovalsecoundTblft2'" style="padding:2px">
						<table id="invpurreqapprovalsecoundTbl" style="width:100%">
						<thead>
						<tr>
							<th data-options="field:'html',halign:'center'" width="60"  formatter="MsInvPurReqApproval.formatHtmlSecond" align="center">Details</th>
							<th data-options="field:'pdf',halign:'center'" width="40" formatter="MsInvPurReqApproval.formatpdf" align="center">Details</th>
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'requisition_no'" width="100">Requisition NO</th>
							<th data-options="field:'company_id'" width="100">Company</th>
							<th data-options="field:'req_date'" width="100">Requisition Date</th>
							<th data-options="field:'pay_mode'" width="120">Pay Mode</th>
							<th data-options="field:'currency_id'" width="100">Currency</th>
							<th data-options="field:'location_id'" width="100">Location</th>
							<th data-options="field:'remarks'" width="120">Remarks</th>
						</tr>
						</thead>
						</table>
						<div id="invpurreqapprovalsecoundTblft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvesecond.invpurreqs')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.selectAll('#invpurreqapprovalsecoundTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.unselectAll('#invpurreqapprovalsecoundTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.approved('secondapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvethird.invpurreqs')
			<div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#invpurreqapprovalthirdTblft3'" style="padding:2px">
						<table id="invpurreqapprovalthirdTbl" style="width:100%">
						<thead>
						<tr>
							<th data-options="field:'html',halign:'center'" width="60"  formatter="MsInvPurReqApproval.formatHtmlThird" align="center">Details</th>
							<th data-options="field:'pdf',halign:'center'" width="40" formatter="MsInvPurReqApproval.formatpdf" align="center">Details</th>
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'requisition_no'" width="100">Requisition NO</th>
							<th data-options="field:'company_id'" width="100">Company</th>
							<th data-options="field:'req_date'" width="100">Requisition Date</th>
							<th data-options="field:'pay_mode'" width="120">Pay Mode</th>
							<th data-options="field:'currency_id'" width="100">Currency</th>
							<th data-options="field:'location_id'" width="100">Location</th>
							<th data-options="field:'remarks'" width="120">Remarks</th>
						</tr>
						</thead>
						</table>
						<div id="invpurreqapprovalthirdTblft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvethird.invpurreqs')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.selectAll('#invpurreqapprovalthirdTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.unselectAll('#invpurreqapprovalthirdTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.approved('thirdapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvefinal.invpurreqs')
			<div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#invpurreqapprovalfinalTblft4'" style="padding:2px">
						<table id="invpurreqapprovalfinalTbl" style="width:100%">
						<thead>
						<tr>
							<th data-options="field:'html',halign:'center'" width="60" formatter="MsInvPurReqApproval.formatHtmlFinal" align="center">Details</th>
							<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsInvPurReqApproval.formatpdf" align="center">Details</th>
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'requisition_no'" width="100">Requisition NO</th>
							<th data-options="field:'company_id'" width="100">Company</th>
							<th data-options="field:'req_date'" width="100">Requisition Date</th>
							<th data-options="field:'pay_mode'" width="120">Pay Mode</th>
							<th data-options="field:'currency_id'" width="100">Currency</th>
							<th data-options="field:'location_id'" width="100">Location</th>
							<th data-options="field:'remarks'" width="120">Remarks</th>
						</tr>
						</thead>
						</table>
						<div id="invpurreqapprovalfinalTblft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvefinal.invpurreqs')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.selectAll('#invpurreqapprovalfinalTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.unselectAll('#invpurreqapprovalfinalTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.approved('finalapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div><!-- final -->
			@endpermission
		</div><!-- accordian -->
	</div>
	<div data-options="region:'west',border:true,title:'Search',footer:'#invpurreqapprovalFrmFt'" style="width:350px; padding:2px">
		<form id="invpurreqapprovalFrm">
			<div id="container">
				<div id="body">
					<code>
						
					<div class="row">
					<div class="col-sm-4">Company </div>
					<div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
					</div>
					
					<div class="row middle">
					<div class="col-sm-4">Req. Date </div>
					<div class="col-sm-4" style="padding-right:0px">
					<input type="text" name="req_date_from" id="req_date_from" class="datepicker" placeholder="From" />
					</div>
					<div class="col-sm-4" style="padding-left:0px">
					<input type="text" name="req_date_to" id="req_date_to" class="datepicker"  placeholder="To" />
					</div>
					</div>
					</code>
				</div>
			</div>
			<div id="invpurreqapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.show()">Show</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvPurReqApproval.resetForm('invpurreqapprovalFrm')" >Reset</a>
			</div>
		</form>
	</div>
</div>

<div id="invpurreqApprovalDetailWindow" class="easyui-window" title="Purchase Requisition Details" data-options="modal:true,closed:true,closable:false" style="width:100%;height:100%;padding:2px;">
	<div id="invpurreqApprovalDetailContainer"></div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsInvPurReqApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>