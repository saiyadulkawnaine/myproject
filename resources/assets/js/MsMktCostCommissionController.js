let MsMktCostCommissionModel = require('./MsMktCostCommissionModel');
class MsMktCostCommissionController {
	constructor(MsMktCostCommissionModel)
	{
		this.MsMktCostCommissionModel = MsMktCostCommissionModel;
		this.formId='mktcostcommissionFrm';
		this.dataTable='#mktcostcommissionTbl';
		this.route=msApp.baseUrl()+"/mktcostcommission"
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
			this.MsMktCostCommissionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostCommissionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostCommissionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostCommissionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{

		//$('#mktcostcommissionFrm  [name=id]').val(d.id);
		msApp.resetForm('mktcostcommissionFrm');
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		$('#mktcostcommissionFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		$('#mktcostpriceaftercommissionFrm  [name=price_after_commission]').val(d.price_after_commission);
		MsMktCostCommission.get(mkt_cost_id);
		MsMktCost.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostCommissionModel.get(index,row);
	}
	 get(mkt_cost_id){
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostCommission.showGrid(response.data)
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
			showFooter:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			//queryParams:data,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostCommission.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculatemount(){
		let price_before_commission;
		let rate;
		price_before_commission= $('#mktcostpricebeforecommissionFrm  [name=price_before_commission]').val();
		rate=$('#mktcostcommissionFrm  [name=rate]').val();
		let margin_method=1-(rate/100);
		let amount=(price_before_commission/margin_method)-price_before_commission;
		//let amount=(rate/100)*price_before_commission;
		$('#mktcostcommissionFrm  [name=amount]').val(amount)

	}
}
window.MsMktCostCommission=new MsMktCostCommissionController(new MsMktCostCommissionModel());
