let MsBudgetOtherModel = require('./MsBudgetOtherModel');
class MsBudgetOtherController {
	constructor(MsBudgetOtherModel)
	{
		this.MsBudgetOtherModel = MsBudgetOtherModel;
		this.formId='budgetotherFrm';
		this.dataTable='#budgetotherTbl';
		this.route=msApp.baseUrl()+"/budgetother"
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
			this.MsBudgetOtherModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetOtherModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetOtherModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetOtherModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#budgetotherTbl').datagrid('reload');

		msApp.resetForm('budgetotherFrm');
		let budget_id = $('#budgetFrm  [name=id]').val();
		$('#budgetotherFrm  [name=budget_id]').val(budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetOther.get(budget_id);
		MsBudgetCommission.get(budget_id);
		MsBudget.reloadDetails(budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetOtherModel.get(index,row);
	}

	get(budget_id){
		let data= axios.get(this.route+"?budget_id="+budget_id)
		.then(function (response) {
			MsBudgetOther.showGrid(response.data)
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
			showFooter:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			//queryParams:data,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetOther.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate()
	{
		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		let order_qty=$('#budgetFrm  [name=order_qty]').val();
		let amount=$('#budgetotherFrm  [name=amount]').val();

		let bom_amount=msApp.multiply((order_qty/costing_unit),amount);
		$('#budgetotherFrm  [name=bom_amount]').val(bom_amount);
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsBudgetOther=new MsBudgetOtherController(new MsBudgetOtherModel());
