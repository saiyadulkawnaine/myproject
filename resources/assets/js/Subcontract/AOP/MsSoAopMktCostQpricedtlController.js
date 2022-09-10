let MsSoAopMktCostQpricedtlModel = require('./MsSoAopMktCostQpricedtlModel');

class MsSoAopMktCostQpricedtlController {
	constructor(MsSoAopMktCostQpricedtlModel)
	{
		this.MsSoAopMktCostQpricedtlModel = MsSoAopMktCostQpricedtlModel;
		this.formId='soaopmktcostqpricedtlFrm';	             
		this.dataTable='#soaopmktcostqpricedtlTbl';
		this.route=msApp.baseUrl()+"/soaopmktcostqpricedtl"
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
		let so_aop_mkt_cost_qprice_id=$('#soaopmktcostqpriceFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.so_aop_mkt_cost_qprice_id=so_aop_mkt_cost_qprice_id;
		if(formObj.id){
			this.MsSoAopMktCostQpricedtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoAopMktCostQpricedtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsSoAopMktCostQprice.resetForm();
		$('#soaopmktcostqpricedtlcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopMktCostQpricedtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopMktCostQpricedtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoAopMktCostQpricedtl.resetForm()
		$('#soaopmktcostqpricedtlcosi').html('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoAopMktCostQpricedtlModel.get(index,row);
	}

	showGrid(so_aop_mkt_cost_qprice_id){
		let self=this;
		let data = {};
		data.so_aop_mkt_cost_qprice_id=so_aop_mkt_cost_qprice_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCostQpricedtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateProfitQuotePrice(i){
		let exch_rate=($('#soaopmktcostqpricedtlFrm input[name="exch_rate['+i+']"]').val())*1;
		let cost_per_kg_bdt=($('#soaopmktcostqpricedtlFrm input[name="cost_per_kg_bdt['+i+']"]').val())*1;
		let quoted_price_bdt=($('#soaopmktcostqpricedtlFrm input[name="quoted_price_bdt['+i+']"]').val())*1;
		let cost_per_kg=($('#soaopmktcostqpricedtlFrm input[name="cost_per_kg['+i+']"]').val())*1;
	    let profit_amount_bdt=(quoted_price_bdt-cost_per_kg_bdt).toFixed(2);
		let profit_per=((profit_amount_bdt/quoted_price_bdt)*100).toFixed(2);
		let quoted_price=(quoted_price_bdt/exch_rate).toFixed(4);
		let profit_amount=(quoted_price-cost_per_kg).toFixed(4);
		$('#soaopmktcostqpricedtlFrm input[name="profit_amount_bdt['+i+']"]').val(profit_amount_bdt);
		$('#soaopmktcostqpricedtlFrm input[name="profit_per['+i+']"]').val(profit_per);
		$('#soaopmktcostqpricedtlFrm input[name="quoted_price['+i+']"]').val(quoted_price);
		$('#soaopmktcostqpricedtlFrm input[name="profit_amount['+i+']"]').val(profit_amount);
	}

}
window.MsSoAopMktCostQpricedtl=new MsSoAopMktCostQpricedtlController(new MsSoAopMktCostQpricedtlModel());