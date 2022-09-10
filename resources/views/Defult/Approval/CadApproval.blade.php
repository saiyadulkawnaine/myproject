<div class="easyui-layout"  data-options="fit:true">
	<div data-options="region:'center',border:true" style="padding:2px">
		<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="cadApprovalAccordion">
			@permission('approvefirst.cads')
			<div title="First Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#cadapprovalfirstTblft1'" style="padding:2px">
						<table id="cadapprovalfirstTbl" style="width:100%">
						<thead>
						<tr>
							<th data-options="field:'html',halign:'center'" width="60"  formatter="MsCadApproval.formatHtmlFirst" align="center">Details</th>
							
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'team_name',halign:'center'" width="70">Team <br/> Leader</th>
							<th data-options="field:'team_member',halign:'center'" width="70">Team <br/> Member</th>
							<th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
							<th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
							<th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
							<th data-options="field:'season_name',halign:'center'" width="80">Season</th>
							<th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
						</tr>
						</thead>
						</table>
						<div id="cadapprovalfirstTblft1" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvefirst.cads')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.selectAll('#cadapprovalfirstTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.unselectAll('#cadapprovalfirstTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.approved('firstapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvesecond.cads')

			<div title="Second Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#cadapprovalsecoundTblft2'" style="padding:2px">
						<table id="cadapprovalsecoundTbl" style="width:100%">
						<thead>
						<tr>
							<th data-options="field:'html',halign:'center'" width="60"  formatter="MsCadApproval.formatHtmlFirst" align="center">Details</th>
							
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'team_name',halign:'center'" width="70">Team <br/> Leader</th>
							<th data-options="field:'team_member',halign:'center'" width="70">Team <br/> Member</th>
							<th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
							<th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
							<th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
							<th data-options="field:'season_name',halign:'center'" width="80">Season</th>
							<th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
						</tr>
						</thead>
						</table>
						<div id="cadapprovalsecoundTblft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvesecond.cads')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.selectAll('#cadapprovalsecoundTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.unselectAll('#cadapprovalsecoundTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.approved('secondapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvethird.cads')
			<div title="Third Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#cadapprovalthirdTblft3'" style="padding:2px">
						<table id="cadapprovalthirdTbl" style="width:100%">
						<thead>
						<tr>
							<th data-options="field:'html',halign:'center'" width="60"  formatter="MsCadApproval.formatHtmlFirst" align="center">Details</th>
							
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'team_name',halign:'center'" width="70">Team <br/> Leader</th>
							<th data-options="field:'team_member',halign:'center'" width="70">Team <br/> Member</th>
							<th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
							<th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
							<th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
							<th data-options="field:'season_name',halign:'center'" width="80">Season</th>
							<th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
						</tr>
						</thead>
						</table>
						<div id="cadapprovalthirdTblft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvethird.cads')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.selectAll('#cadapprovalthirdTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.unselectAll('#cadapprovalthirdTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.approved('thirdapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvefinal.cads')
			<div title="Final Approval" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#cadapprovalfinalTblft4'" style="padding:2px">
						<table id="cadapprovalfinalTbl" style="width:100%">
						<thead>
						<tr>
							<th data-options="field:'html',halign:'center'" width="60"  formatter="MsCadApproval.formatHtmlFirst" align="center">Details</th>
							
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'team_name',halign:'center'" width="70">Team <br/> Leader</th>
							<th data-options="field:'team_member',halign:'center'" width="70">Team <br/> Member</th>
							<th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
							<th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
							<th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
							<th data-options="field:'season_name',halign:'center'" width="80">Season</th>
							<th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
						</tr>
						</thead>
						</table>
						<div id="cadapprovalfinalTblft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvefinal.cads')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.selectAll('#cadapprovalfinalTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.unselectAll('#cadapprovalfinalTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.approved('finalapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div><!-- final -->
			@endpermission
		</div><!-- accordian -->
	</div>
	<div data-options="region:'west',border:true,title:'Search',footer:'#cadapprovalFrmFt'" style="width:350px; padding:2px">
		<form id="cadapprovalFrm">
			<div id="container">
				<div id="body">
					<code>
					<div class="row middle">
					<div class="col-sm-4">Cad. Date </div>
					<div class="col-sm-4" style="padding-right:0px">
					<input type="text" name="cad_date_from" id="cad_date_from" class="datepicker" placeholder="From" />
					</div>
					<div class="col-sm-4" style="padding-left:0px">
					<input type="text" name="cad_date_to" id="cad_date_to" class="datepicker"  placeholder="To" />
					</div>
					</div>
					</code>
				</div>
			</div>
			<div id="cadapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadApproval.show()">Show</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCadApproval.resetForm('cadapprovalFrm')" >Reset</a>
			</div>
		</form>
	</div>
</div>

<div id="cadApprovalDetailWindow" class="easyui-window" title="Cad Details" data-options="modal:true,closed:true,closable:true" style="width:100%;height:100%;padding:2px;">
	<div id="cadApprovalDetailContainer"></div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsCadApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>