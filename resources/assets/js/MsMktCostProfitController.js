let MsMktCostProfitModel = require('./MsMktCostProfitModel');
class MsMktCostProfitController {
	constructor(MsMktCostProfitModel)
	{
		this.MsMktCostProfitModel = MsMktCostProfitModel;
		this.formId='mktcostprofitFrm';
		this.dataTable='#mktcostprofitTbl';
		this.route=msApp.baseUrl()+"/mktcostprofit"
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
			this.MsMktCostProfitModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostProfitModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostProfitModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostProfitModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#mktcostprofitTbl').datagrid('reload');
		msApp.resetForm('mktcostprofitFrm');
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		$('#mktcostprofitFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		$('#mktcostpricebeforecommissionFrm  [name=price_before_commission]').val(d.price_before_commission);
		MsMktCostProfit.get(mkt_cost_id);
		MsMktCost.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostProfitModel.get(index,row);
	}
    get(mkt_cost_id){
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostProfit.showGrid(response.data)
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
			//showFooter:true,
			//queryParams:data,
			data:data,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostProfit.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculatemount(){
		let total_cost;
		let rate;
		total_cost= $('#mktcosttotalFrm  [name=total_cost]').val();
		rate=$('#mktcostprofitFrm  [name=rate]').val();
		let margin_method=1-(rate/100);
		let amount=(total_cost/margin_method)-total_cost;
		//let amount=(rate/100)*total_cost;
		$('#mktcostprofitFrm  [name=amount]').val(amount)

	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsMktCostProfit=new MsMktCostProfitController(new MsMktCostProfitModel());
