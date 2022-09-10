let MsBudgetCmModel = require('./MsBudgetCmModel');
class MsBudgetCmController {
	constructor(MsBudgetCmModel)
	{
		this.MsBudgetCmModel = MsBudgetCmModel;
		this.formId='budgetcmFrm';
		this.dataTable='#budgetcmTbl';
		this.route=msApp.baseUrl()+"/budgetcm"
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
			this.MsBudgetCmModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetCmModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetCmModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetCmModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{

		let method_id = $('#budgetcmFrm  [name=method_id]').val();
		msApp.resetForm('budgetcmFrm');
		$('#budgetcmFrm  [name=method_id]').val(method_id);
		let mkt_cost_id = $('#budgetFrm  [name=id]').val();
		$('#budgetcmFrm  [name=budget_id]').val(mkt_cost_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetCm.get(mkt_cost_id);
		//MsBudgetProfit.get(mkt_cost_id);
		MsBudgetCommission.get(mkt_cost_id);
		MsBudget.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetCmModel.get(index,row);
	}

	get(mkt_cost_id){
		let data= axios.get(this.route+"?budget_id="+mkt_cost_id)
		.then(function (response) {
			MsBudgetCm.showGrid(response.data.listdata)
			$('#budgetcmFrm  [name=amount]').val(response.data.cmsamount);
			MsBudgetCm.calculate();
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
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetCm.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate()
	{
		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		let order_qty=$('#budgetFrm  [name=order_qty]').val();
		let amount=$('#budgetcmFrm  [name=amount]').val();
		let bom_amount=msApp.multiply((order_qty/costing_unit),amount);
		$('#budgetcmFrm  [name=bom_amount]').val(bom_amount);
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}


	calCmPerPcs(){
		let cpm = $('#budgetcmFrm [name="cpm"]').val();
		if(cpm=='' || cpm ==0){
			alert('Please set CMP first');
			return;
		}
		let smv = Number($('#budgetcmFrm [name="smv"]').val());
		let sewing_effi_per = Number($('#budgetcmFrm [name="sewing_effi_per"]').val())/100;
		cpm=Number(cpm);
		
		let cm_per_pcs=(smv*cpm)/sewing_effi_per;
		$('#budgetcmFrm [name="cm_per_pcs"]').val(cm_per_pcs.toFixed(4));
		this.calProdHour();
	}

	calProdHour(){
		let smv = Number($('#budgetcmFrm [name="smv"]').val());
		let sewing_effi_per = Number($('#budgetcmFrm [name="sewing_effi_per"]').val())/100;
		let no_of_man_power = Number($('#budgetcmFrm [name="no_of_man_power"]').val());
		let prod_per_hour=(60*no_of_man_power*sewing_effi_per)/smv;
		$('#budgetcmFrm [name="prod_per_hour"]').val(prod_per_hour.toFixed());
	}

	getGmtItem(){
		let budget_id = $('#budgetFrm  [name=id]').val();
		let data= axios.get(this.route+"/gmtitem?budget_id="+budget_id)
		.then(function (response) {
			$('#budgetcmgmtsTbl').datagrid('loadData',response.data);
			$('#budgetCmGmtItemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridGmtItem(data)
	{
		let self=this;
		$("#budgetcmgmtsTbl").datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			data:data,
			onClickRow: function(index,row){
				//self.edit(index,row);
				$('#budgetcmFrm [name="style_gmt_id"]').val(row.id);
				$('#budgetcmFrm [name="style_gmt_name"]').val(row.name);
				$('#budgetcmFrm [name="cpm"]').val(row.cpm);
				$('#budgetCmGmtItemWindow').window('close');
			}
		});
	}

}
window.MsBudgetCm=new MsBudgetCmController(new MsBudgetCmModel());
MsBudgetCm.showGridGmtItem([]);
