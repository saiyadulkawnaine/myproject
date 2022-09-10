<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="poaopserviceapprovaltabs">
    <div title="Waiting For Confirmation" style="padding:2px">
		<div class="easyui-layout"  data-options="fit:true">
			<div data-options="region:'center',border:true" style="padding:2px">	
				<table id="mktcostconfirmationTbl" style="width:1890px">
					<thead>
						<tr>
							<th data-options="field:'html'" width="40"  formatter="MsMktCostConfirmation.formatHtml" align="center">Show</th>
							<th data-options="field:'pdf'" width="40" formatter="MsMktCostConfirmation.formatpdf" align="center">Details</th>
							<th data-options="field:'id'" width="50">1<br>ID</th>
							<th data-options="field:'team_name',halign:'center'" width="70">2<br>Team <br/> Leader</th>
							<th data-options="field:'team_member',halign:'center'" width="70">3<br>Team <br/> Member</th>
							<th data-options="field:'created_by',halign:'center'" width="70">4<br> Created By</th>
							<th data-options="field:'buyer_name',halign:'center'" width="70">5<br>Buyer</th>
							<th data-options="field:'style_ref',halign:'center',styler:MsMktCostConfirmation.styleformat" width="80">6<br>Style No</th>
							<th data-options="field:'style_description',halign:'center'" width="100">7<br>Style Desc.</th>
							<th data-options="field:'season_name',halign:'center'" width="80">8<br>Season</th>
							<th data-options="field:'department_name',halign:'center'" width="80">9<br>Prod. Dept</th>
							<th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsMktCostConfirmation.formatimage" width="30">10<br>Image</th>
							<th data-options="field:'offer_qty',halign:'center'" width="80" align="right">11<br>Offered Qty</th>
							<th data-options="field:'uom_code',halign:'center'" width="50">12<br>UOM</th>
							<th data-options="field:'est_ship_date',halign:'center'" width="80">13<br>Est. Ship Date</th>
							<th data-options="field:'quot_date',halign:'center'" width="80">14<br> Costing date</th>
							<th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">15<br>Cost/Pcs </th>
							<th data-options="field:'price',halign:'center',styler:MsMktCostConfirmation.quotedprice" width="60" align="right">16<br>Quote <br/> Price /Pcs</th>
							<th data-options="field:'comments',halign:'center'" width="100">17<br>Comments</th>
							<th data-options="field:'status',halign:'center'" width="70">18<br>Status</th>


							<th data-options="field:'cm',halign:'center'" width="60" align="right">19<br>CM/Dzn</th>
							<th data-options="field:'fab_amount',halign:'center'" width="60" align="right">20<br>Fabric <br/>Cost /Dzn</th>
							<th data-options="field:'yarn_amount',halign:'center'" width="60" align="right">21<br>Yarn Cost/ <br/> Dzn</th>
							<th data-options="field:'prod_amount',halign:'center'" width="60" align="right">22<br>Fabric Prod. <br/> Cost /Dzn</th>
							<th data-options="field:'trim_amount',halign:'center'" width="40" align="right">23<br>Trims <br/> Cost /Dzn</th>
							<th data-options="field:'emb_amount',halign:'center'" width="60" align="right">24<br>Embel. <br/>Cost /Dzn</th>
							<th data-options="field:'cm_amount',halign:'center'" width="60" align="right">25<br>CM Cost / <br/>Dzn</th>
							<th data-options="field:'other_amount',halign:'center'" width="60" align="right">26<br>Other Cost / <br/>Dzn</th>
							<th data-options="field:'commercial_amount',halign:'center'" width="60" align="right">27<br>Commercial <br/> Cost /Dzn</th>
							<th data-options="field:'commission_on_quoted_price_dzn',halign:'center'" width="80" align="right">28<br>Comm. On <br/> Quoted  Price /Dzn </th>
							<th data-options="field:'total_cost',halign:'center'" width="60" align="right">29<br>Total Cost <br/> /Dzn</th>
							<th data-options="field:'remarks',halign:'center'" width="100">30<br>Remarks</th>
							
							<th data-options="field:'returned_at',halign:'center'" width="100">30<br>Returned At</th>
							<th data-options="field:'returned_coments',halign:'center'" width="300">31<br>Return Comments</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
				<form id="mktcostconfirmationFrm">
					<div id="container">
						<div id="body">
							<code>
								<div class="row">
								<div class="col-sm-4">Buyer </div>
								<div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
								</div>
								<div class="row middle">
								<div class="col-sm-4">Team</div>
								<div class="col-sm-8">
								{!! Form::select('team_id', $team,'',array('id'=>'team_id','style'=>'width: 100%; border-radius:2px')) !!}

								</div>
								</div>
								<div class="row middle">
								<div class="col-sm-4">Team Member</div>
								<div class="col-sm-8">
								{!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id','style'=>'width: 100%; border-radius:2px')) !!}

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
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostConfirmation.get()">Show</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostConfirmation.resetForm('mktcostconfirmationFrm')" >Reset</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div title="Returned" style="padding:2px">
		<div class="easyui-layout"  data-options="fit:true">
			<div data-options="region:'center',border:true" style="padding:2px">	
				<table id="mktcostreturnedTbl" style="width:1890px">
					<thead>
						<tr>
							<th data-options="field:'html'" width="40"  formatter="MsMktCostConfirmation.formatHtml" align="center">Show</th>
							<th data-options="field:'pdf'" width="40" formatter="MsMktCostConfirmation.formatpdf" align="center">Details</th>
							<th data-options="field:'id',styler:MsMktCostConfirmation.returned" width="50">1<br>ID</th>
							<th data-options="field:'team_name',halign:'center'" width="70">2<br>Team <br/> Leader</th>
							<th data-options="field:'team_member',halign:'center'" width="70">3<br>Team <br/> Member</th>
							<th data-options="field:'created_by',halign:'center'" width="70">4<br> Created By</th>
							<th data-options="field:'buyer_name',halign:'center'" width="70">5<br>Buyer</th>
							<th data-options="field:'style_ref',halign:'center',styler:MsMktCostConfirmation.styleformat" width="80">6<br>Style No</th>
							<th data-options="field:'style_description',halign:'center'" width="100">7<br>Style Desc.</th>
							<th data-options="field:'season_name',halign:'center'" width="80">8<br>Season</th>
							<th data-options="field:'returned_at',halign:'center'" width="200">9<br>Returned At</th>
							<th data-options="field:'returned_coments',halign:'center'" width="300">10<br>Return Comments</th>
							
							<th data-options="field:'department_name',halign:'center'" width="80">11<br>Prod. Dept</th>
							<th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsMktCostConfirmation.formatimage" width="30">12<br>Image</th>
							<th data-options="field:'offer_qty',halign:'center'" width="80" align="right">13<br>Offered Qty</th>
							<th data-options="field:'uom_code',halign:'center'" width="50">14<br>UOM</th>
							<th data-options="field:'est_ship_date',halign:'center'" width="80">15<br>Est. Ship Date</th>
							<th data-options="field:'quot_date',halign:'center'" width="80">16<br> Costing date</th>
							<th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">17<br>Cost/Pcs </th>
							<th data-options="field:'price',halign:'center',styler:MsMktCostConfirmation.quotedprice" width="60" align="right">18<br>Quote <br/> Price /Pcs</th>
							<th data-options="field:'comments',halign:'center'" width="100">19<br>Comments</th>
							<th data-options="field:'status',halign:'center'" width="70">20<br>Status</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Search',footer:'#ft3'" style="width:350px; padding:2px">
				<form id="mktcostreturnedFrm">
					<div id="container">
						<div id="body">
							<code>
								<div class="row">
								<div class="col-sm-4">Buyer </div>
								<div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
								</div>
								<div class="row middle">
								<div class="col-sm-4">Team</div>
								<div class="col-sm-8">
								{!! Form::select('team_id', $team,'',array('id'=>'team_id','style'=>'width: 100%; border-radius:2px')) !!}

								</div>
								</div>
								<div class="row middle">
								<div class="col-sm-4">Team Member</div>
								<div class="col-sm-8">
								{!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id','style'=>'width: 100%; border-radius:2px')) !!}

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
								<input type="text" name="date_from_return" id="date_from_return" class="datepicker" placeholder="From" />
								</div>
								<div class="col-sm-4" style="padding-left:0px">
								<input type="text" name="date_to_return" id="date_to_return" class="datepicker"  placeholder="To" />
								</div>
								</div>
								<div class="row middle">
								<div class="col-sm-4">Confirm Date </div>
								<div class="col-sm-4" style="padding-right:0px">
								<input type="text" name="confirm_from_return" id="confirm_from_return" class="datepicker" placeholder="From" />
								</div>
								<div class="col-sm-4" style="padding-left:0px">
								<input type="text" name="confirm_to_return" id="confirm_to_return" class="datepicker"  placeholder="To" />
								</div>
								</div>
								<div class="row middle">
								<div class="col-sm-4">Costing Date </div>
								<div class="col-sm-4" style="padding-right:0px">
								<input type="text" name="costing_from_return" id="costing_from_return" class="datepicker" placeholder="From" />
								</div>
								<div class="col-sm-4" style="padding-left:0px">
								<input type="text" name="costing_to_return" id="costing_to_return" class="datepicker"  placeholder="To" />
								</div>
								</div>
							</code>
						</div>
					</div>
					<div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostConfirmation.getReturned()">Show</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostConfirmation.resetForm('mktcostreturnedFrm')" >Reset</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div title="Approved Status" style="padding:2px">
		<div class="easyui-layout"  data-options="fit:true">
			<div data-options="region:'center',border:true" style="padding:2px">	
				<table id="mktcostapprovedTbl" style="width:1890px">
					<thead>
						<tr>
							<th data-options="field:'pdf'" width="40" formatter="MsMktCostConfirmation.formatpdf" align="center">Details</th>
							<th data-options="field:'id',styler:MsMktCostConfirmation.returned" width="50">1<br>ID</th>
							<th data-options="field:'team_name',halign:'center'" width="70">2<br>Team <br/> Leader</th>
							<th data-options="field:'team_member',halign:'center'" width="70">3<br>Team <br/> Member</th>
							<th data-options="field:'created_by',halign:'center'" width="70">4<br> Created By</th>
							<th data-options="field:'buyer_name',halign:'center'" width="70">5<br>Buyer</th>
							<th data-options="field:'style_ref',halign:'center',styler:MsMktCostConfirmation.styleformat" width="80">6<br>Style No</th>
							<th data-options="field:'style_description',halign:'center'" width="100">7<br>Style Desc.</th>
							<th data-options="field:'season_name',halign:'center'" width="80">8<br>Season</th>
							<th data-options="field:'ie',halign:'center'" width="200">9<br>Director</th>
							
							<th data-options="field:'dmd',halign:'center'" width="200">10<br>Deputy Managing Director</th>
							
							<th data-options="field:'md',halign:'center'" width="200">11<br>Managing Director</th>
							<th data-options="field:'department_name',halign:'center'" width="80">12<br>Prod. Dept</th>
							<th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsMktCostConfirmation.formatimage" width="30">13<br>Image</th>
							<th data-options="field:'offer_qty',halign:'center'" width="80" align="right">14<br>Offered Qty</th>
							<th data-options="field:'uom_code',halign:'center'" width="50">15<br>UOM</th>
							<th data-options="field:'price',halign:'center',styler:MsMktCostConfirmation.quotedprice" width="60" align="right">16<br>Quote <br/> Price /Unit</th>
							<th data-options="field:'amount',halign:'center'" width="60" align="right">17<br>FOB</th>
							<th data-options="field:'cm',halign:'center'" width="60" align="right">18<br>CM</th>
							<th data-options="field:'est_ship_date',halign:'center'" width="80">19<br>Est. Ship Date</th>
							<th data-options="field:'quot_date',halign:'center'" width="80">20<br> Costing date</th>
							<th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">21<br>Cost/Unit </th>
							
							<th data-options="field:'comments',halign:'center'" width="100">22<br>Comments</th>
							<th data-options="field:'status',halign:'center'" width="70">23<br>Status</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Search',footer:'#ft5'" style="width:350px; padding:2px">
				<form id="mktcostapprovedFrm">
					<div id="container">
						<div id="body">
							<code>
								<div class="row">
								<div class="col-sm-4">Buyer </div>
								<div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
								</div>
								<div class="row middle">
								<div class="col-sm-4">Team</div>
								<div class="col-sm-8">
								{!! Form::select('team_id', $team,'',array('id'=>'team_id','style'=>'width: 100%; border-radius:2px')) !!}

								</div>
								</div>
								<div class="row middle">
								<div class="col-sm-4">Team Member</div>
								<div class="col-sm-8">
								{!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id','style'=>'width: 100%; border-radius:2px')) !!}

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
										<input type="text" name="date_from_approved" id="date_from_approved" class="datepicker" placeholder="From" />
									</div>
									<div class="col-sm-4" style="padding-left:0px">
										<input type="text" name="date_to_approved" id="date_to_approved" class="datepicker"  placeholder="To" />
									</div>
								</div>
								<div class="row middle">
									<div class="col-sm-4">Confirm Date </div>
									<div class="col-sm-4" style="padding-right:0px">
										<input type="text" name="confirm_from_approved" id="confirm_from_approved" class="datepicker" placeholder="From" />
									</div>
									<div class="col-sm-4" style="padding-left:0px">
										<input type="text" name="confirm_to_approved" id="confirm_to_approved" class="datepicker"  placeholder="To" />
									</div>
								</div>
								<div class="row middle">
									<div class="col-sm-4">Costing Date </div>
									<div class="col-sm-4" style="padding-right:0px">
										<input type="text" name="costing_from_approved" id="costing_from_approved" class="datepicker" placeholder="From" />
									</div>
									<div class="col-sm-4" style="padding-left:0px">
										<input type="text" name="costing_to_approved" id="costing_to_approved" class="datepicker"  placeholder="To" />
									</div>
								</div>
								<div class="row middle">
									<div class="col-sm-4">Director Approved AT</div>
									<div class="col-sm-4" style="padding-right:0px">
										<input type="text" name="first_approved_from" id="first_approved_from" class="datepicker" placeholder="From" />
									</div>
									<div class="col-sm-4" style="padding-left:0px">
										<input type="text" name="first_approved_to" id="first_approved_to" class="datepicker"  placeholder="To" />
									</div>
								</div>
								<div class="row middle">
									<div class="col-sm-4">DMD Approved AT</div>
									<div class="col-sm-4" style="padding-right:0px">
										<input type="text" name="second_approved_from" id="second_approved_from" class="datepicker" placeholder="From" />
									</div>
									<div class="col-sm-4" style="padding-left:0px">
										<input type="text" name="second_approved_to" id="second_approved_to" class="datepicker"  placeholder="To" />
									</div>
								</div>
								<div class="row middle">
									<div class="col-sm-4">MD Approved AT</div>
									<div class="col-sm-4" style="padding-right:0px">
										<input type="text" name="third_approved_from" id="third_approved_from" class="datepicker" placeholder="From" />
									</div>
									<div class="col-sm-4" style="padding-left:0px">
										<input type="text" name="third_approved_to" id="third_approved_to" class="datepicker"  placeholder="To" />
									</div>
								</div>
							</code>
						</div>
					</div>
					<div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostConfirmation.getApproved()">Show</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsMktCostConfirmation.resetForm('mktcostapprovedFrm')" >Reset</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
	
<div id="mktcostConfirmationDetailWindow" class="easyui-window" title="Marketing Cost Details" data-options="modal:true,closed:true,closable:false" style="width:100%;height:100%;padding:2px;">
	<div id="mktcostConfirmationDetailContainer"></div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsMktCostConfirmationController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});

$('#mktcostconfirmationFrm [id="buyer_id"]').combobox();
$('#mktcostconfirmationFrm [id="team_id"]').combobox();
$('#mktcostconfirmationFrm [id="teammember_id"]').combobox();
$('#mktcostapprovedFrm [id="buyer_id"]').combobox();
$('#mktcostapprovedFrm [id="team_id"]').combobox();
$('#mktcostapprovedFrm [id="teammember_id"]').combobox();
</script>
