let MsBudgetCommercialModel = require('./MsBudgetCommercialModel');
class MsBudgetCommercialController {
	constructor(MsBudgetCommercialModel)
	{
		this.MsBudgetCommercialModel = MsBudgetCommercialModel;
		this.formId='budgetcommercialFrm';
		this.dataTable='#budgetcommercialTbl';
		this.route=msApp.baseUrl()+"/budgetcommercial"
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
			this.MsBudgetCommercialModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetCommercialModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetCommercialModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetCommercialModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#budgetcommercialTbl').datagrid('reload');
		//$('#budgetcommercialFrm  [name=id]').val(d.id);
		msApp.resetForm('budgetcommercialFrm');
		let budget_id = $('#budgetFrm  [name=id]').val();
		$('#budgetcommercialFrm  [name=budget_id]').val(budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetCommercial.get(budget_id);
		//MsBudgetProfit.get(budget_id);
		MsBudgetCommission.get(budget_id);
		MsBudget.reloadDetails(budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetCommercialModel.get(index,row);
	}

	get(budget_id){
		let data= axios.get(this.route+"?budget_id="+budget_id)
		.then(function (response) {
			MsBudgetCommercial.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		//var data={};
		//data.budget_id=budget_id;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			//queryParams:data,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetCommercial.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculatemount(){
		return;
		let total_cost;
		let rate;
		total_cost= $('#BudgettotalFrm  [name=total_cost]').val();
		rate=$('#BudgetcommercialFrm  [name=rate]').val();
		let amount=(rate/100)*total_cost;
		$('#BudgetcommercialFrm  [name=amount]').val(amount)

	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsBudgetCommercial=new MsBudgetCommercialController(new MsBudgetCommercialModel());
