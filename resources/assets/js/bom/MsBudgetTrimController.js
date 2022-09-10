let MsBudgetTrimModel = require('./MsBudgetTrimModel');
class MsBudgetTrimController {
	constructor(MsBudgetTrimModel)
	{
		this.MsBudgetTrimModel = MsBudgetTrimModel;
		this.formId='budgettrimFrm';
		this.dataTable='#budgettrimTbl';
		this.route=msApp.baseUrl()+"/budgettrim"
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
		formObj['budget_id']=$('#budgetFrm  [name=id]').val();
		if(formObj.id){
			this.MsBudgetTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetTrimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetTrimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#budgettrimTbl').datagrid('reload');
		//$('#budgettrimFrm  [name=id]').val(d.id);
		msApp.resetForm('budgettrimFrm');
		let budget_id = $('#budgetFrm  [name=id]').val();
		$('#budgettrimFrm  [name=budget_id]').val(budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetTrim.get(budget_id);
		MsBudgetCommercial.get(budget_id);
		//MsBudgetProfit.get(budget_id);
		//MsBudgetCommission.get(budget_id);
		//MsBudget.reloadDetails(budget_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetTrimModel.get(index,row);
	}
	get(budget_id){
		let data= axios.get(this.route+"?budget_id="+budget_id)
		.then(function (response) {
			MsBudgetTrim.showGrid(response.data)
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
			showFooter:true,
			fit:true,
			singleSelect:true,
			data: data,
			//queryParams:data,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatAddCons(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrim.openConsWindow(event,'+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	formatAddDtm(value,row)
	{
		
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrim.openDtmWindow('+row.budget_id+','+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	calculatemount(){
		let cons;
		let rate;
		cons= $('#budgettrimFrm  [name=cons]').val();
		rate=$('#budgettrimFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#budgettrimFrm  [name=amount]').val(amount)

	}

	openConsWindow(event,id){
		if (!event) var event = window.event;                // Get the window event
		event.cancelBubble = true;                       // IE Stop propagation
		if (event.stopPropagation) event.stopPropagation();

		if(!id){
			alert('Save First');
			return;
		}

		let data= axios.get(msApp.baseUrl()+"/budgettrimcon/create?budget_trim_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#TrimconsWindow').window('open');
		})
	}
	openDtmWindow(budget_id,id){
		if (!event) var event = window.event;                // Get the window event
		event.cancelBubble = true;                       // IE Stop propagation
		if (event.stopPropagation) event.stopPropagation();
		
		if(!id){
			alert('Save First');
			return;
		}
		
		let data= axios.get(msApp.baseUrl()+"/budgettrimdtm/create?budget_id="+budget_id+"&id="+id);
		let g=data.then(function (response) {
			for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
			}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#TrimdtmWindow').window('open');
		})
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	setUom(itemclass_id){
     let data= axios.get(msApp.baseUrl()+"/budgettrim/setuom?itemclass_id="+itemclass_id);
     let g=data.then(function (response) {
     	$('#budgettrimFrm  [name=uom_id]').val(response.data.costing_uom_id);
     	$('#budgettrimFrm  [name=uom_name]').val(response.data.costing_uom_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsBudgetTrim=new MsBudgetTrimController(new MsBudgetTrimModel());
//MsBudgetTrim.showGrid();
