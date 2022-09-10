let MsBudgetFabricConModel = require('./MsBudgetFabricConModel');
class MsBudgetFabricConController {
	constructor(MsBudgetFabricConModel)
	{
		this.MsBudgetFabricConModel = MsBudgetFabricConModel;
		this.formId='budgetfabricconFrm';
		this.dataTable='#budgetfabricconTbl';
		this.route=msApp.baseUrl()+"/budgetfabriccon"
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
		var bud_id= $('#budgetFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.bud_id=bud_id;
		if(formObj.id){
			this.MsBudgetFabricConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetFabricConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetFabricConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetFabricConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#budgetfabricconTbl').datagrid('reload');
		//$('#budgetFabricConFrm  [name=id]').val(d.id);
		//msApp.resetForm('budgetfabricconFrm');
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetFabric.LoadView(d.budget_id);
		MsBudgetCommercial.get(d.budget_id);
		//MsBudgetProfit.showGrid(d.budget_id);
		//MsBudgetCommission.get(d.budget_id);
		//MsBudget.reloadDetails(d.budget_id);
		MsBudgetFabricCon.refresh_table(d.budget_fabric_id);
		
	}

	refresh_table(budget_fabric_id)
	{
		//alert(budget_fabric_id)
		let data= axios.get(msApp.baseUrl()+"/budgetfabriccon/create?budget_fabric_id="+budget_fabric_id);
		let g=data.then(function (response) {
			$('#budgetfabricconscs').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetFabricConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetFabricCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	calculate(iteration,count,field){

		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		let plun_cut_qty=$('#budgetfabricconFrm input[name="plun_cut_qty['+iteration+']"]').val();
		let cons=$('#budgetfabricconFrm input[name="cons['+iteration+']"]').val();
		let process_loss=$('#budgetfabricconFrm input[name="process_loss['+iteration+']"]').val();
		let rate=$('#budgetfabricconFrm input[name="rate['+iteration+']"]').val();
		let cad_cons=$('#budgetfabricconFrm input[name="cad_cons['+iteration+']"]').val();
		let unlayable_per=$('#budgetfabricconFrm input[name="unlayable_per['+iteration+']"]').val();
		let unlayableQty=(cad_cons*1)*((unlayable_per*1)/100);
		let cad_cons_with_per=(cad_cons*1)+unlayableQty;
		if(cons*1>cad_cons_with_per){
			alert('Practical Consumption Not Allowed over cad Consumption+Unlayable % '+unlayable_per);
			$('#budgetfabricconFrm input[name="cons['+iteration+']"]').val('')
			return;

		}




		var devided_val = 1-(process_loss/100);
		var req_cons=parseFloat(cons/devided_val);

		$('#budgetfabricconFrm input[name="req_cons['+iteration+']"]').val(req_cons);
		var fin_fab=(cons/costing_unit)*plun_cut_qty;
		//alert(fin_fab)
		var grey_fab=(req_cons/costing_unit)*plun_cut_qty;


		$('#budgetfabricconFrm input[name="fin_fab['+iteration+']"]').val(fin_fab)
		$('#budgetfabricconFrm input[name="req_cons['+iteration+']"]').val(req_cons)
		$('#budgetfabricconFrm input[name="grey_fab['+iteration+']"]').val(grey_fab)
		if(rate){
		let amount=msApp.multiply(grey_fab,rate);
		$('#budgetfabricconFrm input[name="amount['+iteration+']"]').val(amount)	
		}
		



		if($('#budgetfabricconFrm  #is_copy').is(":checked")){
			if(field==='cons'){
				this.copyCons(cons,iteration,count);
			}
			else if(field==='process_loss'){
				this.copyProcessLoss(process_loss,iteration,count);
			}
			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(cons,iteration,count)
	{
		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		for(var i=iteration;i<=count;i++)
		{
			let process_loss=$('#budgetfabricconFrm input[name="process_loss['+iteration+']"]').val();
			//let cons=$('#budgetfabricconFrm input[name="cons['+i+']"]').val();
			let plun_cut_qty=$('#budgetfabricconFrm input[name="plun_cut_qty['+i+']"]').val();

			let cad_cons=$('#budgetfabricconFrm input[name="cad_cons['+iteration+']"]').val();
			let unlayable_per=$('#budgetfabricconFrm input[name="unlayable_per['+iteration+']"]').val();
			let unlayableQty=(cad_cons*1)*((unlayable_per*1)/100);
			let cad_cons_with_per=(cad_cons*1)+unlayableQty;
			if(cons*1>cad_cons_with_per){
			alert('Practical Consumption Not Allowed over cad Consumption+Unlayable % '+unlayable_per);
			$('#budgetfabricconFrm input[name="cons['+iteration+']"]').val('')
			return;
			}

			let devided_val = 1-(process_loss/100);
		    let req_cons=parseFloat(cons/devided_val);
			let fin_fab=(cons/costing_unit)*plun_cut_qty;
			let grey_fab=msApp.multiply((req_cons/costing_unit),plun_cut_qty);

			
			$('#budgetfabricconFrm input[name="cons['+i+']"]').val(cons)
			$('#budgetfabricconFrm input[name="fin_fab['+i+']"]').val(fin_fab)
			$('#budgetfabricconFrm input[name="process_loss['+i+']"]').val(process_loss)
			$('#budgetfabricconFrm input[name="req_cons['+i+']"]').val(req_cons)
			$('#budgetfabricconFrm input[name="grey_fab['+i+']"]').val(grey_fab)

			let rate=$('#budgetfabricconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(grey_fab,rate);
			$('#budgetfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}

	copyProcessLoss(process_loss,iteration,count)
	{
		let costing_unit=$('#budgetFrm [name=costing_unit_id]').val();
		for(var i=iteration;i<=count;i++)
		{
			let cons=$('#budgetfabricconFrm input[name="cons['+i+']"]').val();
			let plun_cut_qty=$('#budgetfabricconFrm input[name="plun_cut_qty['+i+']"]').val();
			let devided_val = 1-(process_loss/100);
		    let req_cons=parseFloat(cons/devided_val);
			//var grey_fab=(req_cons/costing_unit)*plun_cut_qty;

			$('#budgetfabricconFrm input[name="process_loss['+i+']"]').val(process_loss)
			let grey_fab=msApp.multiply((req_cons/costing_unit),plun_cut_qty);
			$('#budgetfabricconFrm input[name="req_cons['+i+']"]').val(req_cons)
			$('#budgetfabricconFrm input[name="grey_fab['+i+']"]').val(grey_fab)
			
			let rate=$('#budgetfabricconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(grey_fab,rate);
			$('#budgetfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let req_cons=$('#budgetfabricconFrm input[name="req_cons['+i+']"]').val();
			let grey_fab=$('#budgetfabricconFrm input[name="grey_fab['+i+']"]').val();
			let amount=msApp.multiply(grey_fab,rate);
			$('#budgetfabricconFrm input[name="rate['+i+']"]').val(rate)
			$('#budgetfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	copyDia(dia,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#budgetfabricconFrm input[name="dia['+i+']"]').val(dia)
		}
	}
	
	copyMeasu(measurment,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#budgetfabricconFrm input[name="measurment['+i+']"]').val(measurment)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
		
	copyFabricColor(fabric_color,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#budgetfabricconFrm input[name="fabric_color['+i+']"]').val(fabric_color)
		}
	}
}
window.MsBudgetFabricCon=new MsBudgetFabricConController(new MsBudgetFabricConModel());
//MsBudgetFabricCon.showGrid();
