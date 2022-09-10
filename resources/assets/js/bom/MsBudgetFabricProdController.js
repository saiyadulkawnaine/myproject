let MsBudgetFabricProdModel = require('./MsBudgetFabricProdModel');
class MsBudgetFabricProdController {
	constructor(MsBudgetFabricProdModel)
	{
		this.MsBudgetFabricProdModel = MsBudgetFabricProdModel;
		this.formId='budgetfabricprodFrm';
		this.dataTable='#budgetfabricprodTbl';
		this.route=msApp.baseUrl()+"/budgetfabricprod"
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
			this.MsBudgetFabricProdModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetFabricProdModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetFabricProdModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetFabricProdModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#budgetfabricprodTbl').datagrid('reload');

		msApp.resetForm('budgetfabricprodFrm');
		let budget_id = $('#budgetFrm  [name=id]').val();
		$('#budgetfabricprodFrm  [name=budget_id]').val(budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetFabricProd.get(budget_id);
		//MsbudgetCommercial.get(budget_id);
		//MsbudgetProfit.get(budget_id);
		//MsbudgetCommission.get(budget_id);
		//Msbudget.reloadDetails(budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsBudgetFabricProdModel.get(index,row);
		data.then(function (response) {
			MsBudgetFabricProd.companyDropDown(response.data.companyArr);
		})
		.catch(function (error) {
			console.log(error);
		})
	}

	get(budget_id){
		let data= axios.get(this.route+"?budget_id="+budget_id)
		.then(function (response) {
			MsBudgetFabricProd.showGrid(response.data.list)
			MsBudgetFabricProd.fabricDropDown(response.data.dropdown)
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
			//queryParams:data,
			//url:this.route,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetFabricProd.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatAddCons(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsBudgetFabricProd.openConsWindow(event,'+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow(event,id){
		if(!id){
			alert('Save First');
			return;
		}

		if (!event) var event = window.event;                // Get the window event
		event.cancelBubble = true;                       // IE Stop propagation
		if (event.stopPropagation) event.stopPropagation();

		let data= axios.get(msApp.baseUrl()+"/budgetfabricprodcon/create?budget_fabric_prod_id="+id);
		let g=data.then(function (response) {
			$('#budgetfabricprodconscs').html(response.data);
		/*for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}*/
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#BudgetFabricProdConsWindow').window('open');
		})
	}

	calculatemount(){
		let cons;
		let rate;
		cons= $('#budgetfabricprodFrm  [name=cons]').val();
		rate=$('#budgetfabricprodFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#budgetfabricprodFrm  [name=amount]').val(amount)

	}
	fabricDropDown(data){
		$('select[name="budget_fabric_id"]').empty();
			$('select[name="budget_fabric_id"]').append('<option value="">-Select-</option>');
			$.each(data, function(key, value) {
			$('select[name="budget_fabric_id"]').append('<option value="'+ key +'">'+ value +'</option>');
			});
		/*let data= axios.get(this.route+"/create?budget_id="+budget_id);
		let g=data.then(function (response) {
			$('select[name="budget_fabric_id"]').empty();
			$('select[name="budget_fabric_id"]').append('<option value="">-Select-</option>');
			$.each(response.data, function(key, value) {
			$('select[name="budget_fabric_id"]').append('<option value="'+ key +'">'+ value +'</option>');
			});
		})
		.catch(function (error) {
		 	console.log(error);
		});*/
	}
	getFabricCons(budget_fabric_id){
		let data= axios.get(this.route+"/cons?budget_fabric_id="+budget_fabric_id);
			let g=data.then(function (response) {
			$.each(response.data, function(key, value) {
			//$(value.req_cons)
			$('#budgetfabricprodFrm [name="cons"]').val(value.req_cons);
			$('#budgetfabricprodFrm [name="req_cons"]').val(value.req_cons);
			MsBudgetFabricProd.calculatemount()

			});
		})
		.catch(function (error) {
		 	console.log(error);
		});
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	processChange(production_process_id)
	{
		let data= axios.get(this.route+"/processChange?production_process_id="+production_process_id);
		let g=data.then(function (response) {
			MsBudgetFabricProd.companyDropDown(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	companyDropDown(data)
	{
		$('#budgetfabricprodFrm select[name="company_id"]').empty();
		var keyCount  = Object.keys(data).length;
		if(keyCount>1){
		$('#budgetfabricprodFrm select[name="company_id"]').append('<option value="">-Select-</option>');
		}
		$.each(data, function(key, value) {
		$('#budgetfabricprodFrm select[name="company_id"]').append('<option value="'+ key +'">'+ value +'</option>');
		});
	}
}
window.MsBudgetFabricProd=new MsBudgetFabricProdController(new MsBudgetFabricProdModel());
