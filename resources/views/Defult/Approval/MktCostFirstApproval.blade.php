<div class="easyui-layout"  data-options="fit:true">
	<div data-options="region:'center',border:true" style="padding:2px">	
		<div class="easyui-layout"  data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List',footer:'#ft6'" style="padding:2px">
				<table id="mktcostfirstapprovalTbl" style="width:1890px">
				<thead>
					<tr>
						<th data-options="field:'app'" width="60" formatter='MsMktCostFirstApproval.approveButton'></th>
						<th data-options="field:'pdf',halign:'center'" width="40" formatter="MsMktCostFirstApproval.formatpdf" align="center">Details</th>
						<th data-options="field:'id'" width="50">1<br>ID</th>
						<th data-options="field:'team_member',halign:'center'" width="70">2<br>Team <br/> Member</th>
						<th data-options="field:'buyer_name',halign:'center'" width="70">3<br>Buyer</th>
						<th data-options="field:'style_ref',halign:'center',styler:MsMktCostFirstApproval.styleformat" width="80">4<br>Style No</th>
						<th data-options="field:'style_description',halign:'center'" width="100">5<br>Style Desc.</th>
						<th data-options="field:'season_name',halign:'center'" width="80">6<br>Season</th>
						<th data-options="field:'department_name',halign:'center'" width="80">7<br>Prod. Dept</th>
						<th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsMktCostFirstApproval.formatimage" width="30">8<br>Image</th>
						<th data-options="field:'offer_qty',halign:'center'" width="80" align="right">9<br>Offered Qty</th>
						<th data-options="field:'uom_code',halign:'center'" width="50">10<br>UOM</th>
						<th data-options="field:'est_ship_date',halign:'center'" width="80">11<br>Est. Ship Date</th>
						<th data-options="field:'quot_date',halign:'center'" width="80">12<br> Costing date</th>
						<th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">13<br>Cost/Pcs </th>
						<th data-options="field:'price',halign:'center',styler:MsMktCostFirstApproval.quotedprice" width="60" align="right">14<br>Quote <br/> Price /Pcs</th>
						<th data-options="field:'comments',halign:'center'" width="100">15<br>Comments</th>
						<th data-options="field:'status',halign:'center'" width="70">16<br>Status</th>


						<th data-options="field:'cm',halign:'center'" width="60" align="right">17<br>CM/Dzn</th>
						<th data-options="field:'fab_amount',halign:'center'" width="60" align="right">18<br>Fabric <br/>Cost /Dzn</th>
						<th data-options="field:'yarn_amount',halign:'center'" width="60" align="right">19<br>Yarn Cost/ <br/> Dzn</th>
						<th data-options="field:'prod_amount',halign:'center'" width="60" align="right">20<br>Fabric Prod. <br/> Cost /Dzn</th>
						<th data-options="field:'trim_amount',halign:'center'" width="40" align="right">21<br>Trims <br/> Cost /Dzn</th>
						<th data-options="field:'emb_amount',halign:'center'" width="60" align="right">22<br>Embel. <br/>Cost /Dzn</th>
						<th data-options="field:'cm_amount',halign:'center'" width="60" align="right">23<br>CM Cost / <br/>Dzn</th>
						<th data-options="field:'other_amount',halign:'center'" width="60" align="right">24<br>Other Cost / <br/>Dzn</th>
						<th data-options="field:'commercial_amount',halign:'center'" width="60" align="right">25<br>Commercial <br/> Cost /Dzn</th>
						<th data-options="field:'commission_on_quoted_price_dzn',halign:'center'" width="80" align="right">26<br>Comm. On <br/> Quoted  Price /Dzn </th>
						<th data-options="field:'total_cost',halign:'center'" width="60" align="right">27<br>Total Cost <br/> /Dzn</th>
						<th data-options="field:'remarks',halign:'center'" width="100">28<br>Remarks</th>
					</tr>
				</thead>
				</table>
				{{-- <div id="ft6" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
				
				<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostFirstApproval.selectAll('#mktcostfirstapprovalTbl')">Select All</a>
				<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostFirstApproval.unselectAll('#mktcostfirstapprovalTbl')">Unselect All</a>
				<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostFirstApproval.approved()">Approve</a>
				
				</div> --}}
			</div>
		</div>	
	</div>
	<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
		<form id="mktcostfirstapprovalFrm">
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
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostFirstApproval.get()">Show</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostFirstApproval.resetForm('mktcostfirstapprovalFrm')" >Reset</a>
			</div>
		</form>
	</div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsMktCostFirstApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>
