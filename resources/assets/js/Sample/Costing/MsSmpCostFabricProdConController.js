let MsSmpCostFabricProdConModel = require('./MsSmpCostFabricProdConModel');
class MsSmpCostFabricProdConController {
	constructor(MsSmpCostFabricProdConModel)
	{
		this.MsSmpCostFabricProdConModel = MsSmpCostFabricProdConModel;
		this.formId='smpcostfabricprodconFrm';
		this.dataTable='#smpcostfabricprodconTbl';
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
		msApp.resetForm('smpcostfabricprodFrm');
		$('#smpcostfabricprodFrm  [name=smpcost_id]').val(d.smpcost_id);
		$('#smpcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsSmpCostFabricProd.get(d.smpcost_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSmpCostFabricProdCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	calculate(iteration,count,field){
		let bom_qty=$('#smpcostfabricprodconFrm input[name="bom_qty['+iteration+']"]').val();
		let rate=$('#smpcostfabricprodconFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(bom_qty,rate);
		$('#smpcostfabricprodconFrm input[name="amount['+iteration+']"]').val(amount)

		if($('#smpcostfabricprodconFrm  #is_copy').is(":checked")){
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

			$('#smpcostfabricprodconFrm input[name="bom_qty['+i+']"]').val(bom_qty)

			let rate=$('#smpcostfabricprodconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(bom_qty,rate);
			$('#smpcostfabricprodconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let bom_qty=$('#smpcostfabricprodconFrm input[name="bom_qty['+i+']"]').val();
			let amount=msApp.multiply(bom_qty,rate);
			$('#smpcostfabricprodconFrm input[name="rate['+i+']"]').val(rate)
			$('#smpcostfabricprodconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	

}
window.MsSmpCostFabricProdCon=new MsSmpCostFabricProdConController(new MsSmpCostFabricProdConModel());
//MsSmpCostFabricProdCon.showGrid();
