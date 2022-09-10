let MsSmpCostFabricProdConModel = require('./MsSmpCostFabricProdConModel');
class MsSmpCostDyeingConController {
	constructor(MsSmpCostFabricProdConModel)
	{
		this.MsSmpCostFabricProdConModel = MsSmpCostFabricProdConModel;
		this.formId='smpcostdyeingconFrm';
		this.dataTable='#smpcostdyeingconTbl';
		this.route=msApp.baseUrl()+"/smpcostfabricprodcon"
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
			this.MsSmpCostFabricProdConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmpCostFabricProdConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostFabricProdConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostFabricProdConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('smpcostdyeingFrm');
		$('#smpcostdyeingFrm  [name=smp_cost_id]').val(d.smp_cost_id);
		//$('#smpcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsSmpCostDyeing.get(d.smp_cost_id);
		//MsBudgetCommercial.get(d.smpcost_id);
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmpCostFabricProdConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSmpCostDyeingCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	calculate(iteration,count,field){
		let bom_qty=$('#smpcostdyeingconFrm input[name="bom_qty['+iteration+']"]').val();
		let rate=$('#smpcostdyeingconFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(bom_qty,rate);
		$('#smpcostdyeingconFrm input[name="amount['+iteration+']"]').val(amount)

		if($('#smpcostdyeingconFrm  #is_copy').is(":checked")){
			if(field==='bom_qty'){
				this.copyCons(bom_qty,iteration,count);
			}

			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(bom_qty,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{

			$('#smpcostdyeingconFrm input[name="bom_qty['+i+']"]').val(bom_qty)

			let rate=$('#smpcostdyeingconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(bom_qty,rate);
			$('#smpcostdyeingconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let bom_qty=$('#smpcostdyeingconFrm input[name="bom_qty['+i+']"]').val();
			let amount=msApp.multiply(bom_qty,rate);
			$('#smpcostdyeingconFrm input[name="rate['+i+']"]').val(rate)
			$('#smpcostdyeingconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	

}
window.MsSmpCostDyeingCon=new MsSmpCostDyeingConController(new MsSmpCostFabricProdConModel());
//MsSmpCostDyeingCon.showGrid();
