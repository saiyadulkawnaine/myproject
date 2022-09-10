<div class="easyui-layout"  data-options="fit:true">
	<div data-options="region:'center',border:true" style="padding:2px">
		<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="invGeneralItemIsuReqApprovalAccordion">
			@permission('approvefirst.invgeneralitemisureqs')
			<div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#invgeneralitemisureqapprovalfirstTblft1'" style="padding:2px">
						<table id="invgeneralitemisureqapprovalfirstTbl" style="width:100%">
						<thead>
						<tr>
						<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsInvGeneralItemIsuReqApproval.formatpdf" align="center">Details</th>
						<th data-options="field:'id'" width="40">ID</th>
						<th data-options="field:'rq_no'" width="100">Requisition NO</th>
						<th data-options="field:'company_id'" width="100">Company</th>
						<th data-options="field:'req_date'" width="100">Requisition Date</th>
						
						<th data-options="field:'location_id'" width="100">Location</th>
						<th data-options="field:'remarks'" width="120">Remarks</th>
						</tr>
						</thead>
						</table>
						<div id="invgeneralitemisureqapprovalfirstTblft1" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvefirst.invgeneralitemisureqs')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.selectAll('#invgeneralitemisureqapprovalfirstTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.unselectAll('#invgeneralitemisureqapprovalfirstTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.approved('firstapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvesecond.invgeneralitemisureqs')

			<div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#invgeneralitemisureqapprovalsecoundTblft2'" style="padding:2px">
						<table id="invgeneralitemisureqapprovalsecoundTbl" style="width:100%">
						<thead>
						<tr>
						<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsInvGeneralItemIsuReqApproval.formatpdf" align="center">Details</th>
						<th data-options="field:'id'" width="40">ID</th>
						<th data-options="field:'rq_no'" width="100">Requisition NO</th>
						<th data-options="field:'company_id'" width="100">Company</th>
						<th data-options="field:'req_date'" width="100">Requisition Date</th>
						
						<th data-options="field:'location_id'" width="100">Location</th>
						<th data-options="field:'remarks'" width="120">Remarks</th>
						</tr>
						</thead>
						</table>
						<div id="invgeneralitemisureqapprovalsecoundTblft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvesecond.invgeneralitemisureqs')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.selectAll('#invgeneralitemisureqapprovalsecoundTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.unselectAll('#invgeneralitemisureqapprovalsecoundTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.approved('secondapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvethird.invgeneralitemisureqs')
			<div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#invgeneralitemisureqapprovalthirdTblft3'" style="padding:2px">
						<table id="invgeneralitemisureqapprovalthirdTbl" style="width:100%">
						<thead>
						<tr>
						<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsInvGeneralItemIsuReqApproval.formatpdf" align="center">Details</th>
						<th data-options="field:'id'" width="40">ID</th>
						<th data-options="field:'rq_no'" width="100">Requisition NO</th>
						<th data-options="field:'company_id'" width="100">Company</th>
						<th data-options="field:'req_date'" width="100">Requisition Date</th>
						
						<th data-options="field:'location_id'" width="100">Location</th>
						<th data-options="field:'remarks'" width="120">Remarks</th>
						</tr>
						</thead>
						</table>
						<div id="invgeneralitemisureqapprovalthirdTblft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvethird.invgeneralitemisureqs')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.selectAll('#invgeneralitemisureqapprovalthirdTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.unselectAll('#invgeneralitemisureqapprovalthirdTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.approved('thirdapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvefinal.invgeneralitemisureqs')
			<div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#invgeneralitemisureqapprovalfinalTblft4'" style="padding:2px">
						<table id="invgeneralitemisureqapprovalfinalTbl" style="width:100%">
						<thead>
						<tr>
						<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsInvGeneralItemIsuReqApproval.formatpdf" align="center">Details</th>
						<th data-options="field:'id'" width="40">ID</th>
						<th data-options="field:'rq_no'" width="100">Requisition NO</th>
						<th data-options="field:'company_id'" width="100">Company</th>
						<th data-options="field:'req_date'" width="100">Requisition Date</th>
						<th data-options="field:'location_id'" width="100">Location</th>
						<th data-options="field:'remarks'" width="120">Remarks</th>
						</tr>
						</thead>
						</table>
						<div id="invgeneralitemisureqapprovalfinalTblft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvefinal.invgeneralitemisureqs')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.selectAll('#invgeneralitemisureqapprovalfinalTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.unselectAll('#invgeneralitemisureqapprovalfinalTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.approved('finalapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div><!-- final -->
			@endpermission
		</div><!-- accordian -->
	</div>
	<div data-options="region:'west',border:true,title:'Search',footer:'#invgeneralitemisureqapprovalFrmFt'" style="width:350px; padding:2px">
		<form id="invgeneralitemisureqapprovalFrm">
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
			<div id="invgeneralitemisureqapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralItemIsuReqApproval.show()">Show</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralItemIsuReqApproval.resetForm('invgeneralitemisureqapprovalFrm')" >Reset</a>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsInvGeneralItemIsuReqApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>