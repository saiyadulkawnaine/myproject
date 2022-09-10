<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="approvaltabs">
	
    <div title="Marketing Cost" style="padding:2px">
		<div class="easyui-layout"  data-options="fit:true">
			<div data-options="region:'center',border:true" style="padding:2px">
				<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="mktCostApprovalAccordion">
					@permission('approvefirst.mktcosts')
					<div title="Marketing Team (1st)" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
						<div class="easyui-layout"  data-options="fit:true">
							<div data-options="region:'center',border:true,title:'List',footer:'#ft3'" style="padding:2px">
								<table id="mktcostapprovalfirstTbl" style="width:1890px">
									<thead>
										<tr>
										<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsMktcostApproval.formatpdf" align="center">Details</th>
										<th data-options="field:'team_member',halign:'center'" width="70" halign:'center'>Team <br/> Member</th>
										<th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
										<th data-options="field:'style_ref',halign:'center',styler:MsMktcostApproval.styleformat" width="80">Style No</th>
										<th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
										<th data-options="field:'season_name',halign:'center'" width="80">Season</th>
										<th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
										<th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsMktcostApproval.formatimage" width="30">Image</th>
										<th data-options="field:'offer_qty',halign:'center'" width="80" align="right">Offered Qty</th>
										<th data-options="field:'uom_code',halign:'center'" width="50">UOM</th>
										<th data-options="field:'est_ship_date',halign:'center'" width="80">Est. Ship Date</th>
										<th data-options="field:'quot_date',halign:'center'" width="80"> Costing date</th>
										<th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">Cost/Pcs </th>
										<th data-options="field:'price',halign:'center',styler:MsMktcostApproval.quotedprice" width="60" align="right">Quote <br/> Price /Pcs</th>
										<th data-options="field:'comments',halign:'center'" width="100" >Comments</th>
										<th data-options="field:'status',halign:'center'" width="70" >Status</th>
										<th data-options="field:'cm',halign:'center'" width="60" align="right">CM/Dzn</th>
										<th data-options="field:'fab_amount',halign:'center'" width="60" align="right">Fabric <br/>Cost /Dzn</th>
										<th data-options="field:'yarn_amount',halign:'center'" width="60" align="right">Yarn Cost/ <br/> Dzn</th>
										<th data-options="field:'prod_amount',halign:'center'" width="60" align="right">Fabric Prod. <br/> Cost /Dzn</th>
										<th data-options="field:'trim_amount',halign:'center'" width="40" align="right">Trims <br/> Cost /Dzn</th>
										<th data-options="field:'emb_amount',halign:'center'" width="60" align="right">Embel. <br/>Cost /Dzn</th>
										<th data-options="field:'cm_amount',halign:'center'" width="60" align="right">CM Cost / <br/>Dzn</th>
										<th data-options="field:'other_amount',halign:'center'" width="60" align="right">Other Cost / <br/>Dzn</th>
										<th data-options="field:'commercial_amount',halign:'center'" width="60" align="right">Commercial <br/> Cost /Dzn</th>
										<th data-options="field:'commission_on_quoted_price_dzn',halign:'center'" width="80" align="right">Comm. On <br/> Quoted  Price /Dzn </th>
										<th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost <br/> /Dzn</th>
										<th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
										<th data-options="field:'id'" width="50">ID</th>
										</tr>	
									</thead>
								</table>
								<div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
									@permission('approvefirst.mktcosts')
									<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.selectAll('#mktcostapprovalfirstTbl')">Select All</a>
								    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.unselectAll('#mktcostapprovalfirstTbl')">Unselect All</a>
									<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.approved('firstapproved')">Approve</a>
									@endpermission
								</div>
							</div>
						</div>
					</div>
					@endpermission
					@permission('approvesecond.mktcosts')

					<div title="DMD Sir (2nd)" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
						<div class="easyui-layout"  data-options="fit:true">
							<div data-options="region:'center',border:true,title:'List',footer:'#ft4'" style="padding:2px">
								<table id="mktcostapprovalsecondTbl" style="width:1890px">
									<thead>
										<tr>
											<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsMktcostApproval.formatpdf" align="center">Details</th>
											<th data-options="field:'team_member',halign:'center'" width="70" halign:'center'>Team <br/> Member</th>
											<th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
											<th data-options="field:'style_ref',halign:'center',styler:MsMktcostApproval.styleformat" width="80">Style No</th>
											<th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
											<th data-options="field:'season_name',halign:'center'" width="80">Season</th>
											<th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
											<th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsMktcostApproval.formatimage" width="30">Image</th>
											<th data-options="field:'offer_qty',halign:'center'" width="80" align="right">Offered Qty</th>
											<th data-options="field:'uom_code',halign:'center'" width="50">UOM</th>
											<th data-options="field:'est_ship_date',halign:'center'" width="80">Est. Ship Date</th>
											<th data-options="field:'quot_date',halign:'center'" width="80"> Costing date</th>
											<th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">Cost/Pcs </th>
											<th data-options="field:'price',halign:'center',styler:MsMktcostApproval.quotedprice" width="60" align="right">Quote <br/> Price /Pcs</th>
											<th data-options="field:'comments',halign:'center'" width="100" >Comments</th>
											<th data-options="field:'status',halign:'center'" width="70" >Status</th>
											<th data-options="field:'cm',halign:'center'" width="60" align="right">CM/Dzn</th>
											<th data-options="field:'fab_amount',halign:'center'" width="60" align="right">Fabric <br/>Cost /Dzn</th>
											<th data-options="field:'yarn_amount',halign:'center'" width="60" align="right">Yarn Cost/ <br/> Dzn</th>
											<th data-options="field:'prod_amount',halign:'center'" width="60" align="right">Fabric Prod. <br/> Cost /Dzn</th>
											<th data-options="field:'trim_amount',halign:'center'" width="40" align="right">Trims <br/> Cost /Dzn</th>
											<th data-options="field:'emb_amount',halign:'center'" width="60" align="right">Embel. <br/>Cost /Dzn</th>
											<th data-options="field:'cm_amount',halign:'center'" width="60" align="right">CM Cost / <br/>Dzn</th>
											<th data-options="field:'other_amount',halign:'center'" width="60" align="right">Other Cost / <br/>Dzn</th>
											<th data-options="field:'commercial_amount',halign:'center'" width="60" align="right">Commercial <br/> Cost /Dzn</th>
											<th data-options="field:'commission_on_quoted_price_dzn',halign:'center'" width="80" align="right">Comm. On <br/> Quoted  Price /Dzn </th>
											<th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost <br/> /Dzn</th>
											<th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
											<th data-options="field:'id'" width="50">ID</th>
										</tr>
									</thead>
								</table>
								<div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
									@permission('approvesecond.mktcosts')
									<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.selectAll('#mktcostapprovalsecondTbl')">Select All</a>
								    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.unselectAll('#mktcostapprovalsecondTbl')">Unselect All</a>
									<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.approved('secondapproved')">Approve</a>
									@endpermission
								</div>
							</div>
						</div>
					</div>
					@endpermission
					@permission('approvethird.mktcosts')
					<div title="Accounts & Finance (3rd)" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
						<div class="easyui-layout"  data-options="fit:true">
							<div data-options="region:'center',border:true,title:'List',footer:'#ft5'" style="padding:2px">
								<table id="mktcostapprovalthirdTbl" style="width:1890px">
									<thead>
										<tr>
											<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsMktcostApproval.formatpdf" align="center">Details</th>
											<th data-options="field:'team_member',halign:'center'" width="70" halign:'center'>Team <br/> Member</th>
											<th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
											<th data-options="field:'style_ref',halign:'center',styler:MsMktcostApproval.styleformat" width="80">Style No</th>
											<th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
											<th data-options="field:'season_name',halign:'center'" width="80">Season</th>
											<th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
											<th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsMktcostApproval.formatimage" width="30">Image</th>
											<th data-options="field:'offer_qty',halign:'center'" width="80" align="right">Offered Qty</th>
											<th data-options="field:'uom_code',halign:'center'" width="50">UOM</th>
											<th data-options="field:'est_ship_date',halign:'center'" width="80">Est. Ship Date</th>
											<th data-options="field:'quot_date',halign:'center'" width="80"> Costing date</th>
											<th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">Cost/Pcs </th>
											<th data-options="field:'price',halign:'center',styler:MsMktcostApproval.quotedprice" width="60" align="right">Quote <br/> Price /Pcs</th>
											<th data-options="field:'comments',halign:'center'" width="100" >Comments</th>
											<th data-options="field:'status',halign:'center'" width="70" >Status</th>
											<th data-options="field:'cm',halign:'center'" width="60" align="right">CM/Dzn</th>
											<th data-options="field:'fab_amount',halign:'center'" width="60" align="right">Fabric <br/>Cost /Dzn</th>
											<th data-options="field:'yarn_amount',halign:'center'" width="60" align="right">Yarn Cost/ <br/> Dzn</th>
											<th data-options="field:'prod_amount',halign:'center'" width="60" align="right">Fabric Prod. <br/> Cost /Dzn</th>
											<th data-options="field:'trim_amount',halign:'center'" width="40" align="right">Trims <br/> Cost /Dzn</th>
											<th data-options="field:'emb_amount',halign:'center'" width="60" align="right">Embel. <br/>Cost /Dzn</th>
											<th data-options="field:'cm_amount',halign:'center'" width="60" align="right">CM Cost / <br/>Dzn</th>
											<th data-options="field:'other_amount',halign:'center'" width="60" align="right">Other Cost / <br/>Dzn</th>
											<th data-options="field:'commercial_amount',halign:'center'" width="60" align="right">Commercial <br/> Cost /Dzn</th>
											<th data-options="field:'commission_on_quoted_price_dzn',halign:'center'" width="80" align="right">Comm. On <br/> Quoted  Price /Dzn </th>
											<th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost <br/> /Dzn</th>
											<th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
											<th data-options="field:'id'" width="50">ID</th>
										</tr>
									</thead>
								</table>
								<div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
									@permission('approvethird.mktcosts')
									<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.selectAll('#mktcostapprovalthirdTbl')">Select All</a>
								    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.unselectAll('#mktcostapprovalthirdTbl')">Unselect All</a>
									<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.approved('thirdapproved')">Approve</a>
									@endpermission
								</div>
							</div>
						</div>
					</div>
					@endpermission
					@permission('approvefinal.mktcosts')
					<div title="Managing Director Sir (Final)" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
						<div class="easyui-layout"  data-options="fit:true">
							<div data-options="region:'center',border:true,title:'List',footer:'#ft6'" style="padding:2px">
								<table id="mktcostapprovalfinalTbl" style="width:1890px">
								<thead>
								<tr>
								<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsMktcostApproval.formatpdf" align="center">Details</th>
								<th data-options="field:'team_member',halign:'center'" width="70" halign:'center'>Team <br/> Member</th>
								<th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
								<th data-options="field:'style_ref',halign:'center',styler:MsMktcostApproval.styleformat" width="80">Style No</th>
								<th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
								<th data-options="field:'season_name',halign:'center'" width="80">Season</th>
								<th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
								<th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsMktcostApproval.formatimage" width="30">Image</th>
								<th data-options="field:'offer_qty',halign:'center'" width="80" align="right">Offered Qty</th>
								<th data-options="field:'uom_code',halign:'center'" width="50">UOM</th>
								<th data-options="field:'est_ship_date',halign:'center'" width="80">Est. Ship Date</th>
								<th data-options="field:'quot_date',halign:'center'" width="80"> Costing date</th>
								<th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">Cost/Pcs </th>
								<th data-options="field:'price',halign:'center',styler:MsMktcostApproval.quotedprice" width="60" align="right">Quote <br/> Price /Pcs</th>
								<th data-options="field:'comments',halign:'center'" width="100" >Comments</th>
								<th data-options="field:'status',halign:'center'" width="70" >Status</th>


								<th data-options="field:'cm',halign:'center'" width="60" align="right">CM/Dzn</th>
								<th data-options="field:'fab_amount',halign:'center'" width="60" align="right">Fabric <br/>Cost /Dzn</th>
								<th data-options="field:'yarn_amount',halign:'center'" width="60" align="right">Yarn Cost/ <br/> Dzn</th>
								<th data-options="field:'prod_amount',halign:'center'" width="60" align="right">Fabric Prod. <br/> Cost /Dzn</th>
								<th data-options="field:'trim_amount',halign:'center'" width="40" align="right">Trims <br/> Cost /Dzn</th>
								<th data-options="field:'emb_amount',halign:'center'" width="60" align="right">Embel. <br/>Cost /Dzn</th>
								<th data-options="field:'cm_amount',halign:'center'" width="60" align="right">CM Cost / <br/>Dzn</th>
								<th data-options="field:'other_amount',halign:'center'" width="60" align="right">Other Cost / <br/>Dzn</th>
								<th data-options="field:'commercial_amount',halign:'center'" width="60" align="right">Commercial <br/> Cost /Dzn</th>
								<th data-options="field:'commission_on_quoted_price_dzn',halign:'center'" width="80" align="right">Comm. On <br/> Quoted  Price /Dzn </th>
								<th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost <br/> /Dzn</th>



								<th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
								<th data-options="field:'id'" width="50">ID</th>
								</tr>
								</thead>
								</table>
								<div id="ft6" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
								@permission('approvefinal.mktcosts')
								<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.selectAll('#mktcostapprovalfinalTbl')">Select All</a>
								<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.unselectAll('#mktcostapprovalfinalTbl')">Unselect All</a>
								<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.approved('finalapproved')">Approve</a>
								@endpermission
								</div>
							</div>
						</div>
					</div><!-- final -->
					@endpermission
				</div><!-- accordian -->
			</div>
			<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
				<form id="mktcostapprovalFrm">
					<div id="container">
						<div id="body">
							<code>
								
							<div class="row">
							<div class="col-sm-4">Buyer </div>
							<div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
							</div>
							<div class="row middle">
							<div class="col-sm-4">Team</div>
							<div class="col-sm-8">
							{!! Form::select('team_id', $team,'',array('id'=>'team_id')) !!}

							</div>
							</div>
							<div class="row middle">
							<div class="col-sm-4">Team Member</div>
							<div class="col-sm-8">
							{!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id')) !!}

							</div>
							</div>
							<div class="row middle">
							<div class="col-sm-4">Style Ref</div>
							<div class="col-sm-8">
							<input type="text" name="style_ref" id="style_ref" />
							</div>
							</div>
							<div class="row middle">
							<div class="col-sm-4">Est. Ship Date </div>
							<div class="col-sm-4" style="padding-right:0px">
							<input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
							</div>
							<div class="col-sm-4" style="padding-left:0px">
							<input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
							</div>
							</div>
							<div class="row middle">
							<div class="col-sm-4">Confirm Date </div>
							<div class="col-sm-4" style="padding-right:0px">
							<input type="text" name="confirm_from" id="confirm_from" class="datepicker" placeholder="From" />
							</div>
							<div class="col-sm-4" style="padding-left:0px">
							<input type="text" name="confirm_to" id="confirm_to" class="datepicker"  placeholder="To" />
							</div>
							</div>
							<div class="row middle">
							<div class="col-sm-4">Costing Date </div>
							<div class="col-sm-4" style="padding-right:0px">
							<input type="text" name="costing_from" id="costing_from" class="datepicker" placeholder="From" />
							</div>
							<div class="col-sm-4" style="padding-left:0px">
							<input type="text" name="costing_to" id="costing_to" class="datepicker"  placeholder="To" />
							</div>
							</div>
							</code>
						</div>
					</div>
					<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
					<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktcostApproval.show()">Show</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktcostApproval.resetForm('mktcostapprovalFrm')" >Reset</a>
					</div>
				</form>
			</div>
		</div>
    </div>
    <div title="Requisitions" style="padding:2px">
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
    	
    </div>
    <div title="General Item Issue Requisition" style="padding:2px">
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
    </div>
    <div title="Budget" style="padding:2px"></div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsAllApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>
