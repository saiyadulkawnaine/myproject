let MsBudgetCommissionModel = require('./MsBudgetCommissionModel');
class MsBudgetCommissionController {
	constructor(MsBudgetCommissionModel)
	{
		this.MsBudgetCommissionModel = MsBudgetCommissionModel;
		this.formId='budgetcommissionFrm';
		this.dataTable='#budgetcommissionTbl';
		this.route=msApp.baseUrl()+"/budgetcommission"
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
			this.MsBudgetCommissionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetCommissionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetCommissionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetCommissionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{

		//$('#budgetcommissionFrm  [name=id]').val(d.id);
		msApp.resetForm('budgetcommissionFrm');
		let budget_id = $('#budgetFrm  [name=id]').val();
		$('#budgetcommissionFrm  [name=budget_id]').val(budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		//$('#budgetpriceaftercommissionFrm  [name=price_after_commission]').val(d.price_after_commission);
		MsBudgetCommission.get(budget_id);
		MsBudget.reloadDetails(budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetCommissionModel.get(index,row);
	}
	 get(budget_id){
		let data= axios.get(this.route+"?budget_id="+budget_id)
		.then(function (response) {
			MsBudgetCommission.showGrid(response.data)
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetCommission.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculatemount(){
		/*let price_before_commission;
		let rate;
		price_before_commission= $('#budgetpricebeforecommissionFrm  [name=price_before_commission]').val();
		rate=$('#budgetcommissionFrm  [name=rate]').val();
		let margin_method=1-(rate/100);
		let amount=(price_before_commission/margin_method)-price_before_commission;
		//let amount=(rate/100)*price_before_commission;
		$('#budgetcommissionFrm  [name=amount]').val(amount)*/

		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		let order_amount=$('#budgetFrm  [name=order_amount]').val();
		let rate=$('#budgetcommissionFrm  [name=rate]').val();

		//let bom_amount=msApp.multiply((order_amount/costing_unit),rate);
		let bom_amount=(order_amount*rate)/100;
		$('#budgetcommissionFrm  [name=amount]').val(bom_amount);

	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsBudgetCommission=new MsBudgetCommissionController(new MsBudgetCommissionModel());
