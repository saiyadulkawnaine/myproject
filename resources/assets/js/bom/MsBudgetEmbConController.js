let MsBudgetEmbConModel = require('./MsBudgetEmbConModel');
class MsBudgetEmbConController {
	constructor(MsBudgetEmbConModel)
	{
		this.MsBudgetEmbConModel = MsBudgetEmbConModel;
		this.formId='budgetembconFrm';
		this.dataTable='#budgetembconTbl';
		this.route=msApp.baseUrl()+"/budgetembcon"
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
			this.MsBudgetEmbConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetEmbConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetEmbConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetEmbConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#budgetembconTbl').datagrid('reload');
		//$('#budgetembConFrm  [name=id]').val(d.id);
		//msApp.resetForm('budgetembconFrm');
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetEmb.LoadView(d.budget_id);
		MsBudgetCommercial.get(d.budget_id);
		$('#EmbconsWindow').window('close');
		//MsBudgetCommission.showGrid(d.budget_id);
		//MsBudget.reloadDetails(d.budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetEmbConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetEmbCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	calculate(iteration,count,field){
		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		let cons=$('#budgetembconFrm input[name="cons['+iteration+']"]').val();
		let plun_cut_qty=$('#budgetembconFrm input[name="plun_cut_qty['+iteration+']"]').val();
		let rate=$('#budgetembconFrm input[name="rate['+iteration+']"]').val();
		var req_cons=(cons/costing_unit)*plun_cut_qty;
		$('#budgetembconFrm input[name="req_cons['+iteration+']"]').val(req_cons);

		if($('#budgetembconFrm  #is_copy').is(":checked")){
			if(field==='cons'){
				this.copyCons(cons,iteration,count);
			}

			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(cons,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
            let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
			let plun_cut_qty=$('#budgetembconFrm input[name="plun_cut_qty['+i+']"]').val();


			var req_cons=(cons/costing_unit)*plun_cut_qty;

			$('#budgetembconFrm input[name="cons['+i+']"]').val(cons)
			$('#budgetembconFrm input[name="req_cons['+i+']"]').val(req_cons)
			let rate=$('#budgetembconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(req_cons,(rate/costing_unit));
			$('#budgetembconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
			let req_cons=$('#budgetembconFrm input[name="req_cons['+i+']"]').val();
			let amount=msApp.multiply(req_cons,(rate/costing_unit));
			$('#budgetembconFrm input[name="rate['+i+']"]').val(rate)
			$('#budgetembconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

}
window.MsBudgetEmbCon=new MsBudgetEmbConController(new MsBudgetEmbConModel());
//MsBudgetEmbCon.showGrid();
