let MsBudgetYarnDyeingModel = require('./MsBudgetYarnDyeingModel');
class MsBudgetYarnDyeingController {
	constructor(MsBudgetYarnDyeingModel)
	{
		this.MsBudgetYarnDyeingModel = MsBudgetYarnDyeingModel;
		this.formId='budgetyarndyeingFrm';
		this.dataTable='#budgetyarndyeingTbl';
		this.route=msApp.baseUrl()+"/budgetyarndyeing"
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
			this.MsBudgetYarnDyeingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetYarnDyeingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetYarnDyeingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetYarnDyeingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('budgetyarndyeingFrm');
		let budget_id = $('#budgetFrm  [name=id]').val();
		$('#budgetyarndyeingFrm  [name=budget_id]').val(budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetYarnDyeing.get(budget_id);
		//MsbudgetCommercial.get(budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsBudgetYarnDyeingModel.get(index,row);
		data.then(function (response) {
			MsBudgetYarnDyeing.companyDropDown(response.data.companyArr);
		})
		.catch(function (error) {
			console.log(error);
		})
	}

	get(budget_id){
		let data= axios.get(this.route+"?budget_id="+budget_id)
		.then(function (response) {
			MsBudgetYarnDyeing.showGrid(response.data.list)
			MsBudgetYarnDyeing.fabricDropDown(response.data.dropdown)
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarnDyeing.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatAddCons(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarnDyeing.openConsWindow(event,'+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
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

		let data= axios.get(msApp.baseUrl()+"/budgetyarndyeingcon/create?budget_yarn_dyeing_id="+id);
		let g=data.then(function (response) {
			$('#budgetyarndyeingconscs').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#BudgetYarnDyeingConsWindow').window('open');
		})
	}

	calculatemount(){
		let cons;
		let rate;
		cons= $('#budgetyarndyeingFrm  [name=cons]').val();
		rate=$('#budgetyarndyeingFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#budgetyarndyeingFrm  [name=amount]').val(amount)

	}
	fabricDropDown(data)
	{
	    $('select[name="budget_fabric_id"]').empty();
		$('select[name="budget_fabric_id"]').append('<option value="">-Select-</option>');
		$.each(data, function(key, value) {
		$('select[name="budget_fabric_id"]').append('<option value="'+ key +'">'+ value +'</option>');
		});
	}
	getFabricCons(budget_fabric_id){
		let data= axios.get(this.route+"/cons?budget_fabric_id="+budget_fabric_id);
			let g=data.then(function (response) {
			$.each(response.data, function(key, value) {
			$('#budgetyarndyeingFrm [name="cons"]').val(value.req_cons);
			$('#budgetyarndyeingFrm [name="req_cons"]').val(value.req_cons);
			MsBudgetYarnDyeing.calculatemount()
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
			MsBudgetYarnDyeing.companyDropDown(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	companyDropDown(data)
	{
		$('#budgetyarndyeingFrm select[name="company_id"]').empty();
		var keyCount  = Object.keys(data).length;
		if(keyCount>1){
		$('#budgetyarndyeingFrm select[name="company_id"]').append('<option value="">-Select-</option>');
		}
		$.each(data, function(key, value) {
		$('#budgetyarndyeingFrm select[name="company_id"]').append('<option value="'+ key +'">'+ value +'</option>');
		});
	}
}
window.MsBudgetYarnDyeing=new MsBudgetYarnDyeingController(new MsBudgetYarnDyeingModel());
