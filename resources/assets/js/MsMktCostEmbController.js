let MsMktCostEmbModel = require('./MsMktCostEmbModel');
class MsMktCostEmbController {
	constructor(MsMktCostEmbModel)
	{
		this.MsMktCostEmbModel = MsMktCostEmbModel;
		this.formId='mktcostembFrm';
		this.dataTable='#mktcostembTbl';
		this.route=msApp.baseUrl()+"/mktcostemb"
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
			this.MsMktCostEmbModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostEmbModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostEmbModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostEmbModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#mktcostembTbl').datagrid('reload');
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		//MsMktCostCommercial.showGrid(d.mkt_cost_id);
		//MsMktCostProfit.showGrid(d.mkt_cost_id);
		//MsMktCostCommission.showGrid(d.mkt_cost_id);
		//MsMktCost.reloadDetails(d.mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostEmbModel.get(index,row);
	}

	showGrid(mkt_cost_id)
	{
		let self=this;
		var data={};
		data.mkt_cost_id=mkt_cost_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			queryParams:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostEmb.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate(iteration,count,field){
		let costing_unit=$('#mktcostFrm [name=costing_unit_id]').val();
		let cons=$('#mktcostembFrm input[name="cons['+iteration+']"]').val();
		let rate=$('#mktcostembFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(cons,(rate/costing_unit));
		$('#mktcostembFrm input[name="amount['+iteration+']"]').val(amount);
		if($('#mktcostembFrm #is_copy').is(":checked")){
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
		let costing_unit=$('#mktcostFrm [name=costing_unit_id]').val();
		for(var i=iteration;i<=count;i++)
		{
			let rate=$('#mktcostembFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(cons,(rate/costing_unit));
			$('#mktcostembFrm input[name="cons['+i+']"]').val(cons)
			$('#mktcostembFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	
	copyRate(rate,iteration,count)
	{
		let costing_unit=$('#mktcostFrm [name=costing_unit_id]').val();
		for(var i=iteration;i<=count;i++)
		{
			let cons=$('#mktcostembFrm input[name="cons['+i+']"]').val();
			let amount=msApp.multiply(cons,(rate/costing_unit));
			$('#mktcostembFrm input[name="rate['+i+']"]').val(rate)
			$('#mktcostembFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	
	getRate(iteration)
	{
		let costing_unit=$('#mktcostFrm [name=costing_unit_id]').val();
		let cons=$('#mktcostembFrm input[name="cons['+iteration+']"]').val();
		let style_embelishment_id=$('#mktcostembFrm input[name="style_embelishment_id['+iteration+']"]').val();
		let embelishment_id=$('#mktcostembFrm input[name="embelishment_id['+iteration+']"]').val();
		let embelishment_type_id=$('#mktcostembFrm input[name="embelishment_type_id['+iteration+']"]').val();
		let embelishment_size_id=$('#mktcostembFrm input[name="embelishment_size_id['+iteration+']"]').val();
		let data= axios.get(this.route+"/getrate?style_embelishment_id="+style_embelishment_id+"&embelishment_id="+embelishment_id+'&embelishment_type_id='+embelishment_type_id+'&embelishment_size_id='+embelishment_size_id)
		.then(function (response) {
			if(response.data)
			{
				$('#mktcostembFrm input[name="rate['+iteration+']"]').val(response.data.rate);
				$('#mktcostembFrm input[name="amount['+iteration+']"]').val((response.data.rate/costing_unit)*cons);
			}
			else
			{
				alert("Rate Not Found")
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsMktCostEmb=new MsMktCostEmbController(new MsMktCostEmbModel());
MsMktCostEmb.showGrid();
