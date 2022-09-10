let MsSmpCostFabricConModel = require('./MsSmpCostFabricConModel');
class MsSmpCostFabricConController {
	constructor(MsSmpCostFabricConModel)
	{
		this.MsSmpCostFabricConModel = MsSmpCostFabricConModel;
		this.formId='smpcostfabricconFrm';
		this.dataTable='#smpcostfabricconTbl';
		this.route=msApp.baseUrl()+"/smpcostfabriccon"
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
			this.MsSmpCostFabricConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmpCostFabricConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostFabricConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostFabricConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#smpcostfabricconTbl').datagrid('reload');
		
		$('#smpcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsSmpCostFabric.create(d.smp_cost_id);
		//MsSmpCostCommercial.showGrid(d.smp_cost_id);
		//MsSmpCostProfit.showGrid(d.smp_cost_id);
		//MsSmpCostCommission.showGrid(d.smp_cost_id);
		//MsSmpCost.reloadDetails(d.smp_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmpCostFabricConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSmpCostFabricCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculate(iteration,count,field){
		let costing_unit=$('#smpcostFrm [name=costing_unit_id]').val();
		let qty=$('#smpcostfabricconFrm input[name="qty['+iteration+']"]').val();
		let cons=parseFloat($('#smpcostfabricconFrm input[name="cons['+iteration+']"]').val());
		let cad_cons=$('#smpcostfabricconFrm input[name="cad_cons['+iteration+']"]').val();

		let unlayable_per=$('#smpcostfabricconFrm input[name="unlayable_per['+iteration+']"]').val();
		let unlayableQty=(cad_cons*1)*((unlayable_per*1)/100);
		let cad_cons_with_per=(cad_cons*1)+unlayableQty;

		/*if(cons*1>cad_cons_with_per){
		alert('Consumption not match with Unlayable %');
		$('#budgetfabricconFrm input[name="cons['+iteration+']"]').val('')
		return;
		}*/

		if(cons*1>cad_cons_with_per*1){
			//alert('Greater than cad cons not allowed');
			alert('Practical Consumption Not Allowed over cad Consumption+Unlayable % '+unlayable_per);
			$('#smpcostfabricconFrm input[name="cons['+iteration+']"]').val('')
			$('#smpcostfabricconFrm input[name="fin_fab['+iteration+']"]').val('')
			$('#smpcostfabricconFrm input[name="req_cons['+iteration+']"]').val('')
			$('#smpcostfabricconFrm input[name="process_loss['+iteration+']"]').val('')
			$('#smpcostfabricconFrm input[name="grey_fab['+iteration+']"]').val('')
			$('#smpcostfabricconFrm input[name="rate['+iteration+']"]').val('')
			$('#smpcostfabricconFrm input[name="amount['+iteration+']"]').val('')

			return;

		}
		let process_loss=$('#smpcostfabricconFrm input[name="process_loss['+iteration+']"]').val();
		let rate=$('#smpcostfabricconFrm input[name="rate['+iteration+']"]').val();
		var devided_val = 1-(process_loss/100);
		var req_cons=parseFloat(cons/devided_val);
		$('#smpcostfabricconFrm input[name="req_cons['+iteration+']"]').val(req_cons);

		var fin_fab=(cons/costing_unit)*qty;
		var grey_fab=(req_cons/costing_unit)*qty;

		//if($('#smpcostfabricconFrm  #is_copy').is(":checked")){
			if(field==='cons'){
				this.copyCons(cons,iteration,count);
			}
			else if(field==='process_loss'){
				this.copyProcessLoss(process_loss,iteration,count);
			}
			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		//}
	}
	copyCons(cons,iteration,count)
	{
		let costing_unit=$('#smpcostFrm [name=costing_unit_id]').val();
		for(var i=iteration;i<=count;i++)
		{
			let process_loss=$('#smpcostfabricconFrm input[name="process_loss['+i+']"]').val();
			let qty=$('#smpcostfabricconFrm input[name="qty['+i+']"]').val();

			let cad_cons=$('#smpcostfabricconFrm input[name="cad_cons['+i+']"]').val();
			let unlayable_per=$('#smpcostfabricconFrm input[name="unlayable_per['+i+']"]').val();
			let unlayableQty=(cad_cons*1)*((unlayable_per*1)/100);
			let cad_cons_with_per=(cad_cons*1)+unlayableQty;
			if(cons*1>cad_cons_with_per*1){
			//alert('Greater than cad cons not allowed');
			alert('Practical Consumption Not Allowed over cad Consumption+Unlayable % '+unlayable_per);
			$('#smpcostfabricconFrm input[name="cons['+i+']"]').val('')
			$('#smpcostfabricconFrm input[name="fin_fab['+i+']"]').val('')
			$('#smpcostfabricconFrm input[name="req_cons['+i+']"]').val('')
			$('#smpcostfabricconFrm input[name="process_loss['+i+']"]').val('')
			$('#smpcostfabricconFrm input[name="grey_fab['+i+']"]').val('')
			$('#smpcostfabricconFrm input[name="rate['+i+']"]').val('')
			$('#smpcostfabricconFrm input[name="amount['+i+']"]').val('')
			return;
			}

			let devided_val = 1-(process_loss/100);
		    let req_cons=parseFloat(cons/devided_val);
			let fin_fab=(cons/costing_unit)*qty;
			let grey_fab=msApp.multiply((req_cons/costing_unit),qty);
			
			$('#smpcostfabricconFrm input[name="cons['+i+']"]').val(cons)
			$('#smpcostfabricconFrm input[name="fin_fab['+i+']"]').val(fin_fab)
			$('#smpcostfabricconFrm input[name="process_loss['+i+']"]').val(process_loss)
			$('#smpcostfabricconFrm input[name="req_cons['+i+']"]').val(req_cons)
			$('#smpcostfabricconFrm input[name="grey_fab['+i+']"]').val(grey_fab)

			let rate=$('#smpcostfabricconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(grey_fab,rate);
			$('#smpcostfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}

	copyProcessLoss(process_loss,iteration,count)
	{
		let costing_unit=$('#smpcostFrm [name=costing_unit_id]').val();
		for(var i=iteration;i<=count;i++)
		{
			let cons=$('#smpcostfabricconFrm input[name="cons['+i+']"]').val();
			let qty=$('#smpcostfabricconFrm input[name="qty['+i+']"]').val();
			let devided_val = 1-(process_loss/100);
		    let req_cons=parseFloat(cons/devided_val);

			$('#smpcostfabricconFrm input[name="process_loss['+i+']"]').val(process_loss)
			let grey_fab=msApp.multiply((req_cons/costing_unit),qty);
			$('#smpcostfabricconFrm input[name="req_cons['+i+']"]').val(req_cons)
			$('#smpcostfabricconFrm input[name="grey_fab['+i+']"]').val(grey_fab)
			
			let rate=$('#smpcostfabricconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(grey_fab,rate);
			$('#smpcostfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let req_cons=$('#smpcostfabricconFrm input[name="req_cons['+i+']"]').val();
			let grey_fab=$('#smpcostfabricconFrm input[name="grey_fab['+i+']"]').val();
			let amount=msApp.multiply(grey_fab,rate);
			$('#smpcostfabricconFrm input[name="rate['+i+']"]').val(rate)
			$('#smpcostfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	copyDia(dia,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#smpcostfabricconFrm input[name="dia['+i+']"]').val(dia)
		}
	}



	/*calculate(iteration,count,field){
		let cons=$('#smpcostfabricconFrm input[name="cons['+iteration+']"]').val();
		let process_loss=$('#smpcostfabricconFrm input[name="process_loss['+iteration+']"]').val();
		let rate=$('#smpcostfabricconFrm input[name="rate['+iteration+']"]').val();
		var devided_val = 1-(process_loss/100);
		var req_cons=parseFloat(cons/devided_val);
		$('#smpcostfabricconFrm input[name="req_cons['+iteration+']"]').val(req_cons);

		if($('#smpcostfabricconFrm  #is_copy').is(":checked")){
			if(field==='cons'){
				this.copyCons(cons,process_loss,req_cons,iteration,count);
			}
			else if(field==='process_loss'){
				this.copyCons(cons,process_loss,req_cons,iteration,count);
			}
			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(cons,process_loss,req_cons,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{

			$('#smpcostfabricconFrm input[name="cons['+i+']"]').val(cons)
			$('#smpcostfabricconFrm input[name="process_loss['+i+']"]').val(process_loss)
			$('#smpcostfabricconFrm input[name="req_cons['+i+']"]').val(req_cons)

			let rate=$('#smpcostfabricconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(req_cons,rate);
			$('#smpcostfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let req_cons=$('#smpcostfabricconFrm input[name="req_cons['+i+']"]').val();
			let amount=msApp.multiply(req_cons,rate);
			$('#smpcostfabricconFrm input[name="rate['+i+']"]').val(rate)
			$('#smpcostfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	copyDia(dia,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#smpcostfabricconFrm input[name="dia['+i+']"]').val(dia)
		}
	}*/
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	measurement_top (rate,iteration,count){
		   
		    let gsm=$('#smpcostfabricconFrm  [name=gsm]').val();
			let body_lenght=$('#smpcostfabricconFrm input[name="body_lenght['+iteration+']"]').val();
			let body_sewing_margin=$('#smpcostfabricconFrm input[name="body_sewing_margin['+iteration+']"]').val();
			let body_hem_margin=$('#smpcostfabricconFrm input[name="body_hem_margin['+iteration+']"]').val();
			let sleeve_lenght=$('#smpcostfabricconFrm input[name="sleeve_lenght['+iteration+']"]').val();
			let sleeve_sewing_margin=$('#smpcostfabricconFrm input[name="sleeve_sewing_margin['+iteration+']"]').val();
			let sleeve_hem_margin=$('#smpcostfabricconFrm input[name="sleeve_hem_margin['+iteration+']"]').val();
			let chest_lenght=$('#smpcostfabricconFrm input[name="chest_lenght['+iteration+']"]').val();
			let chest_sewing_margin=$('#smpcostfabricconFrm input[name="chest_sewing_margin['+iteration+']"]').val();
			
			gsm=gsm*1;
			body_lenght=body_lenght*1;
			body_sewing_margin=body_sewing_margin*1;
			body_hem_margin=body_hem_margin*1;
			sleeve_lenght=sleeve_lenght*1;
			sleeve_sewing_margin=sleeve_sewing_margin*1;
			sleeve_hem_margin=sleeve_hem_margin*1;
			chest_lenght=chest_lenght*1;
			chest_sewing_margin=chest_sewing_margin*1;
			
			//[{(Body Lentg +Sleeve Lenth + Sewing Margin + Hem) x (Half Chest + Sewing Margin)} x 2] x 12 x GSM / 10000000
			let cons=(((body_lenght +sleeve_lenght+body_sewing_margin + sleeve_sewing_margin+body_hem_margin+sleeve_hem_margin) * (chest_lenght + chest_sewing_margin)) * 2) * 12 * gsm / 10000000;
			$('#smpcostfabricconFrm input[name="cons['+iteration+']"]').val(cons);
			$('#smpcostfabricconFrm input[name="req_cons['+iteration+']"]').val(cons);
	}
	measurement_bottom(rate,iteration,count){
		
		   
			let gsm=$('#smpcostfabricconFrm  [name=gsm]').val();
		
		    let frontraise_lenght=$('#smpcostfabricconFrm input[name="frontraise_lenght['+iteration+']"]').val();
			let frontraise_sewing_margin=$('#smpcostfabricconFrm input[name="frontraise_sewing_margin['+iteration+']"]').val();
			let westband_lenght=$('#smpcostfabricconFrm input[name="westband_lenght['+iteration+']"]').val();
			let westband_sewing_margin=$('#smpcostfabricconFrm input[name="westband_sewing_margin['+iteration+']"]').val();
			let inseam_lenght=$('#smpcostfabricconFrm input[name="inseam_lenght['+iteration+']"]').val();
			let inseam_sewing_margin=$('#smpcostfabricconFrm input[name="inseam_sewing_margin['+iteration+']"]').val();
			let inseam_hem_margin=$('#smpcostfabricconFrm input[name="inseam_hem_margin['+iteration+']"]').val();
			let thai_lenght=$('#smpcostfabricconFrm input[name="thai_lenght['+iteration+']"]').val();
			let thai_sewing_margin=$('#smpcostfabricconFrm input[name="thai_sewing_margin['+iteration+']"]').val();
			
			gsm=gsm*1;
			frontraise_lenght=frontraise_lenght*1;
			frontraise_sewing_margin=frontraise_sewing_margin*1;
			westband_lenght=westband_lenght*1;
			westband_sewing_margin=westband_sewing_margin*1;
			inseam_lenght=inseam_lenght*1;
			inseam_sewing_margin=inseam_sewing_margin*1;
			inseam_hem_margin=inseam_hem_margin*1;
			thai_lenght=thai_lenght*1;
			thai_sewing_margin=thai_sewing_margin*1;
			
			//[{(Front Rise + In Seam + West Band + Sewing Margin + Hem) x (Half Thai + Sewing Margin)} x 4] x 12  x GSM / 10000000
			let cons=(((frontraise_lenght + westband_lenght +inseam_lenght+frontraise_sewing_margin + westband_sewing_margin+inseam_sewing_margin+inseam_hem_margin) * (thai_lenght+thai_sewing_margin)) * 4) * 12  * gsm/ 10000000;
			$('#smpcostfabricconFrm input[name="cons['+iteration+']"]').val(cons);
			$('#smpcostfabricconFrm input[name="req_cons['+iteration+']"]').val(cons);
		
	}
}
window.MsSmpCostFabricCon=new MsSmpCostFabricConController(new MsSmpCostFabricConModel());
//MsSmpCostFabricCon.showGrid();
