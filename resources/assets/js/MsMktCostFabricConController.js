let MsMktCostFabricConModel = require('./MsMktCostFabricConModel');
class MsMktCostFabricConController {
	constructor(MsMktCostFabricConModel)
	{
		this.MsMktCostFabricConModel = MsMktCostFabricConModel;
		this.formId='mktcostfabricconFrm';
		this.dataTable='#mktcostfabricconTbl';
		this.route=msApp.baseUrl()+"/mktcostfabriccon"
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
		let mkt_cost_id=$('#mktcostFrm  [name=id]').val();
		formObj.mkt_cost_id=mkt_cost_id;
		if(formObj.id){
			this.MsMktCostFabricConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostFabricConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostFabricConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostFabricConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#mktcostfabricconTbl').datagrid('reload');
		//$('#MktCostFabricConFrm  [name=id]').val(d.id);
		//msApp.resetForm('mktcostfabricconFrm');
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsMktCostFabric.LoadView(d.mkt_cost_id);
		MsMktCostCommercial.showGrid(d.mkt_cost_id);
		MsMktCostProfit.showGrid(d.mkt_cost_id);
		MsMktCostCommission.showGrid(d.mkt_cost_id);
		MsMktCost.reloadDetails(d.mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostFabricConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsMktCostFabricCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	calculate(iteration,count,field){
		let cons=$('#mktcostfabricconFrm input[name="cons['+iteration+']"]').val();
		let process_loss=$('#mktcostfabricconFrm input[name="process_loss['+iteration+']"]').val();
		let rate=$('#mktcostfabricconFrm input[name="rate['+iteration+']"]').val();
		var devided_val = 1-(process_loss/100);
		var req_cons=parseFloat(cons/devided_val);
		$('#mktcostfabricconFrm input[name="req_cons['+iteration+']"]').val(req_cons);

		if($('#mktcostfabricconFrm  #is_copy').is(":checked")){
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

			$('#mktcostfabricconFrm input[name="cons['+i+']"]').val(cons)
			$('#mktcostfabricconFrm input[name="process_loss['+i+']"]').val(process_loss)
			$('#mktcostfabricconFrm input[name="req_cons['+i+']"]').val(req_cons)

			let rate=$('#mktcostfabricconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(req_cons,rate);
			$('#mktcostfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let req_cons=$('#mktcostfabricconFrm input[name="req_cons['+i+']"]').val();
			let amount=msApp.multiply(req_cons,rate);
			$('#mktcostfabricconFrm input[name="rate['+i+']"]').val(rate)
			$('#mktcostfabricconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	copyDia(dia,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#mktcostfabricconFrm input[name="dia['+i+']"]').val(dia)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	measurement_top (rate,iteration,count){
		   
		    let gsm=$('#mktcostfabricconFrm  [name=gsm]').val();
			let body_lenght=$('#mktcostfabricconFrm input[name="body_lenght['+iteration+']"]').val();
			let body_sewing_margin=$('#mktcostfabricconFrm input[name="body_sewing_margin['+iteration+']"]').val();
			let body_hem_margin=$('#mktcostfabricconFrm input[name="body_hem_margin['+iteration+']"]').val();
			let sleeve_lenght=$('#mktcostfabricconFrm input[name="sleeve_lenght['+iteration+']"]').val();
			let sleeve_sewing_margin=$('#mktcostfabricconFrm input[name="sleeve_sewing_margin['+iteration+']"]').val();
			let sleeve_hem_margin=$('#mktcostfabricconFrm input[name="sleeve_hem_margin['+iteration+']"]').val();
			let chest_lenght=$('#mktcostfabricconFrm input[name="chest_lenght['+iteration+']"]').val();
			let chest_sewing_margin=$('#mktcostfabricconFrm input[name="chest_sewing_margin['+iteration+']"]').val();
			
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
			$('#mktcostfabricconFrm input[name="cons['+iteration+']"]').val(cons);
			$('#mktcostfabricconFrm input[name="req_cons['+iteration+']"]').val(cons);
	}
	measurement_bottom(rate,iteration,count){
		
		   
			let gsm=$('#mktcostfabricconFrm  [name=gsm]').val();
		
		    let frontraise_lenght=$('#mktcostfabricconFrm input[name="frontraise_lenght['+iteration+']"]').val();
			let frontraise_sewing_margin=$('#mktcostfabricconFrm input[name="frontraise_sewing_margin['+iteration+']"]').val();
			let westband_lenght=$('#mktcostfabricconFrm input[name="westband_lenght['+iteration+']"]').val();
			let westband_sewing_margin=$('#mktcostfabricconFrm input[name="westband_sewing_margin['+iteration+']"]').val();
			let inseam_lenght=$('#mktcostfabricconFrm input[name="inseam_lenght['+iteration+']"]').val();
			let inseam_sewing_margin=$('#mktcostfabricconFrm input[name="inseam_sewing_margin['+iteration+']"]').val();
			let inseam_hem_margin=$('#mktcostfabricconFrm input[name="inseam_hem_margin['+iteration+']"]').val();
			let thai_lenght=$('#mktcostfabricconFrm input[name="thai_lenght['+iteration+']"]').val();
			let thai_sewing_margin=$('#mktcostfabricconFrm input[name="thai_sewing_margin['+iteration+']"]').val();
			
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
			$('#mktcostfabricconFrm input[name="cons['+iteration+']"]').val(cons);
			$('#mktcostfabricconFrm input[name="req_cons['+iteration+']"]').val(cons);
		
	}
}
window.MsMktCostFabricCon=new MsMktCostFabricConController(new MsMktCostFabricConModel());
//MsMktCostFabricCon.showGrid();
