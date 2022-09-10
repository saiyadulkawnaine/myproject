let MsBudgetTrimConModel = require('./MsBudgetTrimConModel');
class MsBudgetTrimConController {
	constructor(MsBudgetTrimConModel)
	{
		this.MsBudgetTrimConModel = MsBudgetTrimConModel;
		this.formId='budgettrimconFrm';
		this.dataTable='#budgettrimconTbl';
		this.route=msApp.baseUrl()+"/budgettrimcon"
	}

	submit()
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});

		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsBudgetTrimConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetTrimConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetTrimConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetTrimConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#budgettrimconTbl').datagrid('reload');
		//$('#budgetTrimConFrm  [name=id]').val(d.id);
		msApp.resetForm('budgettrimFrm');
		$('#budgettrimFrm  [name=budget_id]').val(d.budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetTrim.get(d.budget_id);
		MsBudgetCommercial.get(d.budget_id);
		$('#TrimconsWindow').window('close');
		//MsBudgetProfit.showGrid(d.budget_id);
		//MsBudgetCommission.showGrid(d.budget_id);
		//MsBudget.reloadDetails(d.budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetTrimConModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrimCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	calculate(iteration,count,field){
		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		let plun_cut_qty=$('#budgettrimconFrm input[name="plun_cut_qty['+iteration+']"]').val();
		let cons=$('#budgettrimconFrm input[name="cons['+iteration+']"]').val();
		let process_loss=$('#budgettrimconFrm input[name="process_loss['+iteration+']"]').val();
		let rate=$('#budgettrimconFrm input[name="rate['+iteration+']"]').val();
		var devided_val = 1-(process_loss/100);
		var req_cons=parseFloat(cons/devided_val);

		var req_trim=(cons/costing_unit)*plun_cut_qty;
		var bom_trim=(req_cons/costing_unit)*plun_cut_qty;

		$('#budgettrimconFrm input[name="req_cons['+iteration+']"]').val(req_cons);
		$('#budgettrimconFrm input[name="req_trim['+iteration+']"]').val(req_trim)
		$('#budgettrimconFrm input[name="bom_trim['+iteration+']"]').val(bom_trim)
		
		let amount=msApp.multiply(bom_trim,rate);
		$('#budgettrimconFrm input[name="amount['+iteration+']"]').val(amount)

		if($('#budgettrimconFrm  #is_copy').is(":checked")){
			if(field==='cons'){
				this.copyCons(cons,costing_unit,iteration,count);
			}
			else if(field==='process_loss'){
				this.copyProcessLoss(process_loss,costing_unit,iteration,count);
			}
			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(cons,costing_unit,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let process_loss=$('#budgettrimconFrm input[name="process_loss['+iteration+']"]').val();
			let plun_cut_qty=$('#budgettrimconFrm input[name="plun_cut_qty['+i+']"]').val();
			let devided_val = 1-(process_loss/100);
			let req_cons=parseFloat(cons/devided_val);
			var req_trim=(cons/costing_unit)*plun_cut_qty;
			var bom_trim=(req_cons/costing_unit)*plun_cut_qty;

			$('#budgettrimconFrm input[name="cons['+i+']"]').val(cons)
			$('#budgettrimconFrm input[name="req_trim['+i+']"]').val(req_trim)
			$('#budgettrimconFrm input[name="process_loss['+i+']"]').val(process_loss)
			$('#budgettrimconFrm input[name="req_cons['+i+']"]').val(req_cons)
			$('#budgettrimconFrm input[name="bom_trim['+i+']"]').val(bom_trim)

			let rate=$('#budgettrimconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(bom_trim,rate);
			$('#budgettrimconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	
	copyProcessLoss(process_loss,costing_unit,iteration,count)
	{
		//let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		for(var i=iteration;i<=count;i++)
		{
			let cons=$('#budgettrimconFrm input[name="cons['+i+']"]').val();
			let plun_cut_qty=$('#budgettrimconFrm input[name="plun_cut_qty['+i+']"]').val();
			let devided_val = 1-(process_loss/100);
			let req_cons=parseFloat(cons/devided_val);
			var req_trim=(cons/costing_unit)*plun_cut_qty;
			var bom_trim=(req_cons/costing_unit)*plun_cut_qty;
			
			$('#budgettrimconFrm input[name="process_loss['+i+']"]').val(process_loss)
			$('#budgettrimconFrm input[name="req_cons['+i+']"]').val(req_cons)
			
			$('#budgettrimconFrm input[name="req_trim['+i+']"]').val(req_trim)
			$('#budgettrimconFrm input[name="bom_trim['+i+']"]').val(bom_trim)
			
			let rate=$('#budgettrimconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(bom_trim,rate);
			$('#budgettrimconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let req_cons=$('#budgettrimconFrm input[name="req_cons['+i+']"]').val();
			let bom_trim=$('#budgettrimconFrm input[name="bom_trim['+i+']"]').val();
			let amount=msApp.multiply(bom_trim,rate);
			$('#budgettrimconFrm input[name="rate['+i+']"]').val(rate)
			$('#budgettrimconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	copyColor(color,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#budgettrimconFrm input[name="trim_color['+i+']"]').val(color)
		}
	}
	copyMeasurment(measurment,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#budgettrimconFrm input[name="measurment['+i+']"]').val(measurment)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsBudgetTrimCon=new MsBudgetTrimConController(new MsBudgetTrimConModel());
//MsBudgetTrimCon.showGrid();
