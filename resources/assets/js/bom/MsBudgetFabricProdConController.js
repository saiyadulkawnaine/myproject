let MsBudgetFabricProdConModel = require('./MsBudgetFabricProdConModel');
class MsBudgetFabricProdConController {
	constructor(MsBudgetFabricProdConModel)
	{
		this.MsBudgetFabricProdConModel = MsBudgetFabricProdConModel;
		this.formId='budgetfabricprodconFrm';
		this.dataTable='#budgetfabricprodconTbl';
		this.route=msApp.baseUrl()+"/budgetfabricprodcon"
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
			this.MsBudgetFabricProdConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetFabricProdConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetFabricProdConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetFabricProdConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#budgetfabricprodconTbl').datagrid('reload');
		//$('#budgetfabricprodconFrm  [name=id]').val(d.id);
		msApp.resetForm('budgetfabricprodFrm');
		//let budget_id = $('#budgetFrm  [name=id]').val();
		$('#budgetfabricprodFrm  [name=budget_id]').val(d.budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetFabricProd.get(d.budget_id);
		MsBudgetCommercial.get(d.budget_id);
		$('#BudgetFabricProdConsWindow').window('close')
		//MsBudgetProfit.showGrid(d.budget_id);
		//MsBudgetCommission.get(d.budget_id);
		//MsBudget.reloadDetails(d.budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetFabricProdConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetFabricProdCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	calculate(iteration,count,field){
		let bom_qty=$('#budgetfabricprodconFrm input[name="bom_qty['+iteration+']"]').val();
		let rate=$('#budgetfabricprodconFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(bom_qty,rate);
		$('#budgetfabricprodconFrm input[name="amount['+iteration+']"]').val(amount)

		if($('#budgetfabricprodconFrm  #is_copy').is(":checked")){
			if(field==='bom_qty'){
				this.copyCons(bom_qty,iteration,count);
			}

			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(bom_qty,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{

			$('#budgetfabricprodconFrm input[name="bom_qty['+i+']"]').val(bom_qty)

			let rate=$('#budgetfabricprodconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(bom_qty,rate);
			$('#budgetfabricprodconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let bom_qty=$('#budgetfabricprodconFrm input[name="bom_qty['+i+']"]').val();
			let amount=msApp.multiply(bom_qty,rate);
			$('#budgetfabricprodconFrm input[name="rate['+i+']"]').val(rate)
			$('#budgetfabricprodconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	setGreyAsBom(iteration,count,field,grey_fab)
	{
		//$('#budgetfabricprodconFrm input[name="bom_qty['+iteration+']"]').val()
		$('#budgetfabricprodconFrm input[name="bom_qty['+iteration+']"]').val(grey_fab);
		MsBudgetFabricProdCon.calculate(iteration,count,field);

	}

}
window.MsBudgetFabricProdCon=new MsBudgetFabricProdConController(new MsBudgetFabricProdConModel());
//MsBudgetFabricProdCon.showGrid();
