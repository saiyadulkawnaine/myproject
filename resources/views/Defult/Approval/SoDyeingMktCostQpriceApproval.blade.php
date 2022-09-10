<div class="easyui-layout"  data-options="fit:true">
	<div data-options="region:'center',border:true" style="padding:2px">
		<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="mktCostApprovalAccordion">
			@permission('approvefirst.sodyeingmktcostqprices')
			<div title="Director" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#ft3'" style="padding:2px">
						<table id="sodyeingmktcostqpriceapprovalfirstTbl" style="width:1900px">
							<thead>
								<tr>
								<th data-options="field:'html',halign:'center'" width="60"  formatter="MsSoDyeingMktCostQpriceApproval.formatHtmlFirst" align="center">Details</th>
								<th data-options="field:'pdf',halign:'center'" width="40" formatter="MsSoDyeingMktCostQpriceApproval.formatpdf" align="center">PDF</th>
								<th data-options="field:'qprice_date',halign:'center'" width="70">Submission<br/>  Date</th>
								<th data-options="field:'qprice_no',halign:'center'" width="70" >Submission<br/>No</th>
								<th data-options="field:'buyer_name',halign:'center'" width="70">Customer</th>
								<th data-options="field:'est_delv_date',halign:'center'" width="80">Est. Dlv Date</th>
								<th data-options="field:'costing_date',halign:'center'" width="80"> Costing date</th>
								<th data-options="field:'dyes_cost',halign:'center'" width="60" align="right">Dyes<br/> Cost</th>
								<th data-options="field:'chem_cost',halign:'center'" width="60" align="right">Chem<br/> Cost</th>
								<th data-options="field:'special_chem_cost',halign:'center'" width="60" align="right">Add.Pros.<br/>Cost</th>
								<th data-options="field:'overhead_amount',halign:'center'" width="40" align="right">OH</th>
								<th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost</th>
								<th data-options="field:'cost_per_kg',halign:'center'" width="60" align="right">Cost/Kg</th>
								<th data-options="field:'quoted_price_bdt',halign:'center'" width="60" align="right">Quoted Price<br/>TK</th>
								<th data-options="field:'profit_amount_bdt',halign:'center'" width="40" align="right">Profit TK</th>
								<th data-options="field:'profit_per',halign:'center'" width="60" align="right">Profit%</th>
								<th data-options="field:'quoted_price',halign:'center'" width="60" align="right">Cost/Kg<br/>USD</th>
								<th data-options="field:'profit_amount',halign:'center'" width="60" align="right">Profit USD</th>
								<th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
								<th data-options="field:'id'" width="50">ID</th>
								<th data-options="field:'so_dyeing_mkt_cost_id',halign:'center'" width="70">Master<br/>Costing ID</th>
								</tr>	
							</thead>
						</table>
						<div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
							@permission('approvefirst.sodyeingmktcostqprices')
							<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.selectAll('#sodyeingmktcostqpriceapprovalfirstTbl')">Select All</a>
						    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.unselectAll('#sodyeingmktcostqpriceapprovalfirstTbl')">Unselect All</a>
							<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.approved('firstapproved')">Approve</a>
							@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvesecond.sodyeingmktcostqprices')

			<div title="Deputy Managing Director" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#ft4'" style="padding:2px">
						<table id="sodyeingmktcostqpriceapprovalsecondTbl" style="width:1900px">
							<thead>
								<tr>
									<th data-options="field:'html',halign:'center'" width="60" formatter="MsSoDyeingMktCostQpriceApproval.formatHtmlSecond" align="center">Details</th>
									<th data-options="field:'pdf',halign:'center'" width="40" formatter="MsSoDyeingMktCostQpriceApproval.formatpdf" align="center">PDF</th>
									<th data-options="field:'qprice_date',halign:'center'" width="70">Submission<br/>  Date</th>
									<th data-options="field:'qprice_no',halign:'center'" width="70" >Submission<br/>No</th>
									<th data-options="field:'buyer_name',halign:'center'" width="70">Customer</th>
									<th data-options="field:'est_delv_date',halign:'center'" width="80">Est. Dlv Date</th>
									<th data-options="field:'costing_date',halign:'center'" width="80"> Costing date</th>
									<th data-options="field:'dyes_cost',halign:'center'" width="60" align="right">Dyes<br/> Cost</th>
									<th data-options="field:'chem_cost',halign:'center'" width="60" align="right">Chem<br/> Cost</th>
									<th data-options="field:'special_chem_cost',halign:'center'" width="60" align="right">Add.Pros.<br/>Cost</th>
									<th data-options="field:'overhead_amount',halign:'center'" width="40" align="right">OH</th>
									<th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost</th>
									<th data-options="field:'cost_per_kg',halign:'center'" width="60" align="right">Cost/Kg</th>
									<th data-options="field:'quoted_price_bdt',halign:'center'" width="60" align="right">Quoted Price<br/>TK</th>
									<th data-options="field:'profit_amount_bdt',halign:'center'" width="40" align="right">Profit TK</th>
									<th data-options="field:'profit_per',halign:'center'" width="60" align="right">Profit%</th>
									<th data-options="field:'quoted_price',halign:'center'" width="60" align="right">Cost/Kg<br/>USD</th>
									<th data-options="field:'profit_amount',halign:'center'" width="60" align="right">Profit USD</th>
									<th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
									<th data-options="field:'id'" width="50">ID</th>
									<th data-options="field:'so_dyeing_mkt_cost_id',halign:'center'" width="70">Master<br/>Costing ID</th>
								</tr>
							</thead>
						</table>
						<div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
							@permission('approvesecond.sodyeingmktcostqprices')
							<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.selectAll('#sodyeingmktcostqpriceapprovalsecondTbl')">Select All</a>
						    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.unselectAll('#sodyeingmktcostqpriceapprovalsecondTbl')">Unselect All</a>
							<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.approved('secondapproved')">Approve</a>
							@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvethird.sodyeingmktcostqprices')
			<div title="Managing Director" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#ft5'" style="padding:2px">
						<table id="sodyeingmktcostqpriceapprovalthirdTbl" style="width:1900px">
							<thead>
								<tr>
									<th data-options="field:'html',halign:'center'" width="60"formatter="MsSoDyeingMktCostQpriceApproval.formatHtmlThird" align="center">Details</th>
									<th data-options="field:'pdf',halign:'center'" width="40"  formatter="MsSoDyeingMktCostQpriceApproval.formatpdf" align="center">PDF</th>
									<th data-options="field:'qprice_date',halign:'center'" width="70">Submission<br/>  Date</th>
									<th data-options="field:'qprice_no',halign:'center'" width="70" >Submission<br/>No</th>
									<th data-options="field:'buyer_name',halign:'center'" width="70">Customer</th>
									<th data-options="field:'est_delv_date',halign:'center'" width="80">Est. Dlv Date</th>
									<th data-options="field:'costing_date',halign:'center'" width="80"> Costing date</th>
									<th data-options="field:'dyes_cost',halign:'center'" width="60" align="right">Dyes<br/> Cost</th>
									<th data-options="field:'chem_cost',halign:'center'" width="60" align="right">Chem<br/> Cost</th>
									<th data-options="field:'special_chem_cost',halign:'center'" width="60" align="right">Add.Pros.<br/>Cost</th>
									<th data-options="field:'overhead_amount',halign:'center'" width="40" align="right">OH</th>
									<th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost</th>
									<th data-options="field:'cost_per_kg',halign:'center'" width="60" align="right">Cost/Kg</th>
									<th data-options="field:'quoted_price_bdt',halign:'center'" width="60" align="right">Quoted Price<br/>TK</th>
									<th data-options="field:'profit_amount_bdt',halign:'center'" width="40" align="right">Profit TK</th>
									<th data-options="field:'profit_per',halign:'center'" width="60" align="right">Profit%</th>
									<th data-options="field:'quoted_price',halign:'center'" width="60" align="right">Cost/Kg<br/>USD</th>
									<th data-options="field:'profit_amount',halign:'center'" width="60" align="right">Profit USD</th>
									<th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
									<th data-options="field:'id'" width="50">ID</th>
									<th data-options="field:'so_dyeing_mkt_cost_id',halign:'center'" width="70">Master<br/>Costing ID</th>
								</tr>
							</thead>
						</table>
						<div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
							@permission('approvethird.sodyeingmktcostqprices')
							<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.selectAll('#sodyeingmktcostqpriceapprovalthirdTbl')">Select All</a>
						    <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.unselectAll('#sodyeingmktcostqpriceapprovalthirdTbl')">Unselect All</a>
							<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.approved('thirdapproved')">Approve</a>
							@endpermission
						</div>
					</div>
				</div>
			</div>
			@endpermission
			@permission('approvefinal.sodyeingmktcostqprices')
			<div title="Managing Director" data-options="iconCls:'icon-ok'" style="padding:1px;height:450px">
				<div class="easyui-layout"  data-options="fit:true">
					<div data-options="region:'center',border:true,title:'List',footer:'#ft6'" style="padding:2px">
						<table id="sodyeingmktcostqpriceapprovalfinalTbl" style="width:1900px">
						<thead>
							<tr>
								<th data-options="field:'html',halign:'center'" width="60" formatter="MsSoDyeingMktCostQpriceApproval.formatHtmlFinal" align="center">Details</th>
								<th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsSoDyeingMktCostQpriceApproval.formatpdf" align="center">PDF</th>
								<th data-options="field:'qprice_date',halign:'center'" width="70">Submission<br/>  Date</th>
								<th data-options="field:'qprice_no',halign:'center'" width="70" >Submission<br/>No</th>
								<th data-options="field:'buyer_name',halign:'center'" width="70">Customer</th>
								<th data-options="field:'est_delv_date',halign:'center'" width="80">Est. Dlv Date</th>
								<th data-options="field:'costing_date',halign:'center'" width="80"> Costing date</th>
								<th data-options="field:'dyes_cost',halign:'center'" width="60" align="right">Dyes<br/> Cost</th>
								<th data-options="field:'chem_cost',halign:'center'" width="60" align="right">Chem<br/> Cost</th>
								<th data-options="field:'special_chem_cost',halign:'center'" width="60" align="right">Add.Pros.<br/>Cost</th>
								<th data-options="field:'overhead_amount',halign:'center'" width="40" align="right">OH</th>
								<th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost</th>
								<th data-options="field:'cost_per_kg',halign:'center'" width="60" align="right">Cost/Kg</th>
								<th data-options="field:'quoted_price_bdt',halign:'center'" width="60" align="right">Quoted Price<br/>TK</th>
								<th data-options="field:'profit_amount_bdt',halign:'center'" width="40" align="right">Profit TK</th>
								<th data-options="field:'profit_per',halign:'center'" width="60" align="right">Profit%</th>
								<th data-options="field:'quoted_price',halign:'center'" width="60" align="right">Cost/Kg<br/>USD</th>
								<th data-options="field:'profit_amount',halign:'center'" width="60" align="right">Profit USD</th>
								<th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
								<th data-options="field:'id'" width="50">ID</th>
								<th data-options="field:'so_dyeing_mkt_cost_id',halign:'center'" width="70">Master<br/>Costing ID</th>
							</tr>
						</thead>
						</table>
						<div id="ft6" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
						@permission('approvefinal.sodyeingmktcostqprices')
						<a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.selectAll('#sodyeingmktcostqpriceapprovalfinalTbl')">Select All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.unselectAll('#sodyeingmktcostqpriceapprovalfinalTbl')">Unselect All</a>
						<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.approved('finalapproved')">Approve</a>
						@endpermission
						</div>
					</div>
				</div>
			</div><!-- final -->
			@endpermission
		</div><!-- accordian -->
	</div>
	<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
		<form id="sodyeingmktcostqpriceapprovalFrm">
			<div id="container">
				<div id="body">
					<code>
						
					<div class="row">
					<div class="col-sm-4">Buyer </div>
					<div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
					</div>
					<div class="row middle">
					<div class="col-sm-4">Submission No</div>
					<div class="col-sm-8">
					<input type="text" name="qprice_no" id="qprice_no" value="" />
					</div>
					</div>
					<div class="row middle">
					<div class="col-sm-4">Submission Date </div>
					<div class="col-sm-4" style="padding-right:0px">
					<input type="text" name="submission_from" id="submission_from" class="datepicker" placeholder="From" />
					</div>
					<div class="col-sm-4" style="padding-left:0px">
					<input type="text" name="submission_to" id="submission_to" class="datepicker"  placeholder="To" />
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
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.show()">Show</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingMktCostQpriceApproval.resetForm('sodyeingmktcostqpriceapprovalFrm')" >Reset</a>
			</div>
		</form>
	</div>
</div>

<div id="sodyeingMktCostQpriceApprovalDetailWindow" class="easyui-window" title="Subcontract Dyeing Marketing Cost Details" data-options="modal:true,closed:true,closable:false" style="width:100%;height:100%;padding:2px;">
	<div id="sodyeingMktCostQpriceApprovalDetailContainer"></div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsSoDyeingMktCostQpriceApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});

$('#sodyeingmktcostqpriceapprovalFrm [id="buyer_id"]').combobox();
</script>
