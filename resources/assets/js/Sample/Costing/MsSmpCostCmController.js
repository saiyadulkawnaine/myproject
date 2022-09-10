let MsSmpCostCmModel = require('./MsSmpCostCmModel');
class MsSmpCostCmController {
	constructor(MsSmpCostCmModel)
	{
		this.MsSmpCostCmModel = MsSmpCostCmModel;
		this.formId='smpcostcmFrm';
		this.dataTable='#smpcostcmTbl';
		this.route=msApp.baseUrl()+"/smpcostcm"
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

        var qty=$('#smpcostFrm  [name=qty]').val();
		let formObj=msApp.get(this.formId);
		formObj.qty=qty;
		if(formObj.id){
			this.MsSmpCostCmModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmpCostCmModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostCmModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostCmModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
        var method_id=$('#smpcostcmFrm  [name=method_id]').val();
		msApp.resetForm('smpcostcmFrm');
		$('#smpcostcmFrm  [name=method_id]').val(method_id);
		let smp_cost_id = $('#smpcostFrm  [name=id]').val();
		$('#smpcostcmFrm  [name=smp_cost_id]').val(smp_cost_id);
		//$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsSmpCostCm.get(smp_cost_id);
		//MsMktCostProfit.get(mkt_cost_id);
		//MsMktCostCommission.get(mkt_cost_id);
		//MsMktCost.reloadDetails(smp_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmpCostCmModel.get(index,row);
	}

	get(smp_cost_id){
		let data= axios.get(this.route+"?smp_cost_id="+smp_cost_id)
		.then(function (response) {
			MsSmpCostCm.showGrid(response.data)
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
		return '<a href="javascript:void(0)"  onClick="MsSmpCostCm.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate()
	{
		let costing_unit=$('#smpcostFrm [name=costing_unit_id]').val();
		let qty=$('#smpcostFrm  [name=qty]').val();
		let amount=$('#smpcostcmFrm  [name=amount]').val();
		let bom_amount=msApp.multiply((qty/costing_unit),amount);
		$('#smpcostcmFrm  [name=bom_amount]').val(bom_amount);
	}
}
window.MsSmpCostCm=new MsSmpCostCmController(new MsSmpCostCmModel());
