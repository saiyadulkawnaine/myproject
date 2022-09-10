let MsBudgetModel = require('./MsBudgetModel');
class MsBudgetController {
	constructor(MsBudgetModel)
	{
		this.MsBudgetModel = MsBudgetModel;
		this.formId='budgetFrm';
		this.dataTable='#budgetTbl';
		this.route=msApp.baseUrl()+"/budget"
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
			this.MsBudgetModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#budgetTbl').datagrid('reload');
		//$('#budgetFrm  [name=id]').val(d.id);
		msApp.resetForm('budgetFrm');
		MsBudget.get();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsBudgetModel.get(index,row);
		data.then(function (response) {
			$('#budgettotalFrm  [name=total_cost]').val(response.data.fromData.totalcost);
			$('#budgetotherFrm  [name=order_qty]').val(response.data.fromData.order_qty.toFixed(0));
			$('#budgetotherFrm  [name=uom_id]').val(response.data.fromData.uom_id)
			$('#budgetcmFrm  [name=order_qty]').val(response.data.fromData.order_qty.toFixed(0));
			$('#budgetcmFrm  [name=uom_id]').val(response.data.fromData.uom_id);
			let order_amount=+response.data.fromData.order_amount;
			$('#budgetcommissionFrm  [name=order_amount]').val(order_amount.toFixed(4));
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get()
	{
		let data= axios.get(this.route)
		.then(function (response) {
			MsBudget.showGrid(response.data)
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
			fit:true,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudget.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
 }
 
	calculateLeadTime(){
		 var est_ship_date = $('#est_ship_date').datepicker('getDate');
   var op_date   = $('#op_date').datepicker('getDate');
		 $('#lead_time').val(msApp.dateDiffDays(op_date,est_ship_date));
		 this.setWeek();
 }
 
	setWeek(){
		var est_ship_date = $('#est_ship_date').datepicker('getDate');
		$('#week_no').val(msApp.weekno(est_ship_date));
 }
 
	openJobWindow()
	{
		$('#jobwindow').window('open');
 }

	showJobGrid()
	{
		let data={};
		data.job_no = $('#jobsearch  [name=job_no]').val();
		data.company_id = $('#jobsearch  [name=company_id]').val();
		data.buyer_id = $('#jobsearch  [name=buyer_id]').val();
		data.style_ref = $('#jobsearch  [name=style_ref]').val();
		data.style_description = $('#jobsearch  [name=style_description]').val();
		let self=this;
		var ff=$('#jobsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/job",
			onClickRow: function(index,row){

				$('#budgetFrm  [name=job_id]').val(row.id);
				$('#budgetFrm  [name=job_no]').val(row.job_no);
				$('#budgetFrm  [name=style_id]').val(row.style_id);
				$('#budgetFrm  [name=buyer_id]').val(row.buyer_id);
				$('#budgetFrm  [name=company_id]').val(row.company_id);
				$('#budgetFrm  [name=currency_id]').val(row.currency_id);
				$('#budgetFrm  [name=exch_rate]').val(row.exch_rate);
				$('#budgetFrm  [name=uom_id]').val(row.uom_name);
				$('#jobwindow').window('close')
			}
		});
		ff.datagrid('enableFilter');
	}

	pdf(){
		var id= $('#budgetFrm  [name=id]').val();
		if(id==""){
			alert("Select a Costing");
			return;
		}
		window.open(this.route+"/report?id="+id);
 }
 
	mos(){
		var id= $('#budgetFrm  [name=id]').val();
		if(id==""){
			alert("Select a Costing");
			return;
		}
		window.open(this.route+"/mos?id="+id);
 }
 
	mosbyshipdate(){
		var id= $('#budgetFrm  [name=id]').val();
		if(id==""){
			alert("Select a Costing");
			return;
		}
		window.open(this.route+"/mosbyshipdate?id="+id);
	}

	searchBudget() {
		let params = {};
		params.buyer_search_id = $('#buyer_search_id').val();
		params.style_ref = $('#style_ref').val();
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios(this.route + "/searchbudget", {params});
		data.then(function (response) {
			$('#budgetTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	reloadDetails(budget_id){
		let data= axios.get(this.route+'/'+budget_id+'/edit');
		data.then(function (response) {

		})
		.catch(function (error) {
			console.log(error);
		});
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsBudget=new MsBudgetController(new MsBudgetModel());
MsBudget.get();

$('#budgetAccordion').accordion({
	onSelect:function(title,index){
		let budget_id = $('#budgetFrm  [name=id]').val();
		if(index==1){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',1);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			MsBudgetFabric.LoadView(budget_id);
		}
		if(index==2){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',2);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			MsBudgetFabric.LoadView(budget_id);
			
		}
		if(index==3){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',3);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			MsBudgetYarn.get(budget_id);
			
		}
		if(index==4){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',4);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('budgetyarndyeingFrm');
			$('#budgetyarndyeingFrm  [name=budget_id]').val(budget_id);
			MsBudgetYarnDyeing.get(budget_id)
		}

		if(index==5){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',4);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('budgetfabricprodFrm');
			$('#budgetfabricprodFrm  [name=budget_id]').val(budget_id);
			MsBudgetFabricProd.get(budget_id)
		}


		if(index==6){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',5);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('budgettrimFrm');
			$('#budgettrimFrm  [name=budget_id]').val(budget_id);
			MsBudgetTrim.get(budget_id);
		}

		if(index==7){
			if(budget_id===''){
			msApp.showError('Select Cost First',0);
			$('#budgetAccordion').accordion('unselect',7);
			$('#budgetAccordion').accordion('select',0);
			return;
			}
			MsBudgetEmb.LoadView(budget_id);
		}

		if(index==8){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',7);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('budgetotherFrm');
			$('#budgetotherFrm  [name=budget_id]').val(budget_id);
			MsBudgetOther.get(budget_id);
		}

		if(index==9){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',8);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			let method_id = $('#budgetcmFrm  [name=method_id]').val();
			msApp.resetForm('budgetcmFrm');
			$('#budgetcmFrm  [name=method_id]').val(method_id);
			$('#budgetcmFrm  [name=budget_id]').val(budget_id);
			MsBudgetCm.get(budget_id);
		}

		if(index==10){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',9);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('budgetcommercialFrm');
			$('#budgetcommercialFrm  [name=budget_id]').val(budget_id);
			MsBudgetCommercial.get(budget_id);
		}

		if(index==11){
			if(budget_id===''){
			msApp.showError('Select Cost First',0);
			$('#budgetAccordion').accordion('unselect',11);
			$('#budgetAccordion').accordion('select',0);
			return;
			}
			msApp.resetForm('budgetcommissionFrm');
			$('#budgetcommissionFrm  [name=budget_id]').val(budget_id);
			MsBudgetCommission.get(budget_id);
		}

		if(index==12){
			if(budget_id===''){
				msApp.showError('Select Cost First',0);
				$('#budgetAccordion').accordion('unselect',11);
				$('#budgetAccordion').accordion('select',0);
				return;
			}
		}
	}
})
