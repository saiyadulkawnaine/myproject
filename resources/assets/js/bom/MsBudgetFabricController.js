let MsBudgetFabricModel = require('./MsBudgetFabricModel');
class MsBudgetFabricController {
	constructor(MsBudgetFabricModel)
	{
		this.MsBudgetFabricModel = MsBudgetFabricModel;
		this.formId='budgetfabricFrm';
		this.dataTable='#budgetfabricTbl';
		this.route=msApp.baseUrl()+"/budgetfabric"
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

		
		let self =this;
		var bud_id= $('#budgetFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		 formObj.bud_id=bud_id;
		if(formObj.id){
			let data=this.MsBudgetFabricModel.saves(this.route+"/"+formObj.id,'put',msApp.qs.stringify(formObj),this.response);
			data.then(function (response) {
				self.LoadView(response.data.budget_id);
				MsBudget.reloadDetails(response.data.budget_id);
				MsBudgetFabricProd.fabricDropDown(response.data.budget_id);
		        MsBudgetFabricProd.get(response.data.budget_id);
			})
		}else{
			let data=this.MsBudgetFabricModel.saves(this.route,'post',msApp.qs.stringify(formObj),this.response);
			data.then(function (response) {
				self.LoadView(response.data.budget_id);
				MsBudget.reloadDetails(response.data.budget_id);
				MsBudgetFabricProd.fabricDropDown(response.data.budget_id);
		        MsBudgetFabricProd.get(response.data.budget_id);
			})
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
	}
	openConsWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/budgetfabriccon/create?budget_fabric_id="+id);
		let g=data.then(function (response) {
			$('#budgetfabricconscs').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#budgetfabricconsWindow').window('open');
		})
	}

	
	openYarnWindow(budget_fabric_id){
		if(!budget_fabric_id){
			alert('Save First');
			return;
		}
		MsBudgetYarn.loadview(budget_fabric_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsBudgetFabricModel.get(index,row);

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
		return '<a href="javascript:void(0)"  onClick="MsBudgetFabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsBudgetFabric=new MsBudgetFabricController(new MsBudgetFabricModel());
