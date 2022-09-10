let MsBudgetEmbModel = require('./MsBudgetEmbModel');
class MsBudgetEmbController {
	constructor(MsBudgetEmbModel)
	{
		this.MsBudgetEmbModel = MsBudgetEmbModel;
		this.formId='budgetembFrm';
		this.dataTable='#budgetembTbl';
		this.route=msApp.baseUrl()+"/budgetemb"
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
			this.MsBudgetEmbModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetEmbModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetEmbModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetEmbModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#budgetembTbl').datagrid('reload');
		//$('#budgetembFrm  [name=id]').val(d.id);
		//msApp.resetForm('budgetembFrm');
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetEmb.LoadView(d.budget_id);
		MsBudgetCommercial.get(d.mkt_cost_id);
		//MsBudgetProfit.showGrid(d.mkt_cost_id);
		//MsBudgetCommission.showGrid(d.mkt_cost_id);
		//MsBudget.reloadDetails(d.mkt_cost_id);
	}
	get(budget_id){
		let data= axios.get(this.route+"?budget_id="+budget_id)
		.then(function (response) {
			MsBudgetEmb.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	LoadView(budget_id){
		let data= axios.get(this.route+"/create?budget_id="+budget_id)
		.then(function (response) {
			for(var key in response.data.dropDown){
				msApp.setHtml(key,response.data.dropDown[key]);
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetEmbModel.get(index,row);
	}

	showGrid(data)
	{
		//let self=this;
		//var data={};
		//data.mkt_cost_id=mkt_cost_id;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			data: data,
			//fitColumns:true,
			//url:this.route,
			//queryParams:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetEmb.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openConsWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/budgetembcon/create?budget_emb_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#EmbconsWindow').window('open');
		})
	}
	calculate(iteration,count,field){
		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		let cons=$('#budgetembFrm input[name="cons['+iteration+']"]').val();
		cons=(+cons/+costing_unit);
		let rate=$('#budgetembFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply((cons/costing_unit),rate);
		$('#budgetembFrm input[name="amount['+iteration+']"]').val(amount);
		if($('#budgetembFrm #is_copy').is(":checked")){
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
			let rate=$('#budgetembFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(cons,rate);
			$('#budgetembFrm input[name="cons['+i+']"]').val(cons)
			$('#budgetembFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	copyRate(rate,iteration,count)
	{
		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		for(var i=iteration;i<=count;i++)
		{
			let cons=$('#budgetembFrm input[name="cons['+i+']"]').val();
			cons=(cons*1/costing_unit*1);
			let amount=msApp.multiply(cons,rate);
			$('#budgetembFrm input[name="rate['+i+']"]').val(rate)
			$('#budgetembFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsBudgetEmb=new MsBudgetEmbController(new MsBudgetEmbModel());
//MsBudgetEmb.showGrid();
