let MsMktCostCommercialModel = require('./MsMktCostCommercialModel');
class MsMktCostCommercialController {
	constructor(MsMktCostCommercialModel)
	{
		this.MsMktCostCommercialModel = MsMktCostCommercialModel;
		this.formId='mktcostcommercialFrm';
		this.dataTable='#mktcostcommercialTbl';
		this.route=msApp.baseUrl()+"/mktcostcommercial"
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
			this.MsMktCostCommercialModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostCommercialModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostCommercialModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostCommercialModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#mktcostcommercialTbl').datagrid('reload');
		//$('#mktcostcommercialFrm  [name=id]').val(d.id);
		msApp.resetForm('mktcostcommercialFrm');
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		$('#mktcostcommercialFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsMktCostCommercial.get(mkt_cost_id);
		MsMktCostProfit.get(mkt_cost_id);
		MsMktCostCommission.get(mkt_cost_id);
		MsMktCost.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostCommercialModel.get(index,row);
	}

	get(mkt_cost_id){
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostCommercial.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		//var data={};
		//data.mkt_cost_id=mkt_cost_id;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			//queryParams:data,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostCommercial.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculatemount(){
		return;
		let total_cost;
		let rate;
		total_cost= $('#mktcosttotalFrm  [name=total_cost]').val();
		rate=$('#mktcostcommercialFrm  [name=rate]').val();
		let amount=(rate/100)*total_cost;
		$('#mktcostcommercialFrm  [name=amount]').val(amount)

	}
}
window.MsMktCostCommercial=new MsMktCostCommercialController(new MsMktCostCommercialModel());
