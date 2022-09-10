let MsSoDyeingMktCostQpricedtlModel = require('./MsSoDyeingMktCostQpricedtlModel');

class MsSoDyeingMktCostQpricedtlController {
	constructor(MsSoDyeingMktCostQpricedtlModel)
	{
		this.MsSoDyeingMktCostQpricedtlModel = MsSoDyeingMktCostQpricedtlModel;
		this.formId='sodyeingmktcostqpricedtlFrm';	             
		this.dataTable='#sodyeingmktcostqpricedtlTbl';
		this.route=msApp.baseUrl()+"/sodyeingmktcostqpricedtl"
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
		let so_dyeing_mkt_cost_qprice_id=$('#sodyeingmktcostqpriceFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.so_dyeing_mkt_cost_qprice_id=so_dyeing_mkt_cost_qprice_id;
		if(formObj.id){
			this.MsSoDyeingMktCostQpricedtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingMktCostQpricedtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsSoDyeingMktCostQprice.resetForm();
		$('#sodyeingmktcostqpricedtlcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingMktCostQpricedtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingMktCostQpricedtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoDyeingMktCostQpricedtl.resetForm()
		$('#sodyeingmktcostqpricedtlcosi').html('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoDyeingMktCostQpricedtlModel.get(index,row);

	}

	showGrid(so_dyeing_mkt_cost_qprice_id){
		let self=this;
		let data = {};
		data.so_dyeing_mkt_cost_qprice_id=so_dyeing_mkt_cost_qprice_id;
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingMktCostQpricedtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateProfitQuotePrice(i){
		let exch_rate=($('#sodyeingmktcostqpricedtlFrm input[name="exch_rate['+i+']"]').val())*1;
		let cost_per_kg_bdt=($('#sodyeingmktcostqpricedtlFrm input[name="cost_per_kg_bdt['+i+']"]').val())*1;
		let quoted_price_bdt=($('#sodyeingmktcostqpricedtlFrm input[name="quoted_price_bdt['+i+']"]').val())*1;
		let cost_per_kg=($('#sodyeingmktcostqpricedtlFrm input[name="cost_per_kg['+i+']"]').val())*1;
	    let profit_amount_bdt=(quoted_price_bdt-cost_per_kg_bdt).toFixed(2);
		let profit_per=((profit_amount_bdt/quoted_price_bdt)*100).toFixed(2);
		let quoted_price=(quoted_price_bdt/exch_rate).toFixed(4);
		let profit_amount=(quoted_price-cost_per_kg).toFixed(4);
		$('#sodyeingmktcostqpricedtlFrm input[name="profit_amount_bdt['+i+']"]').val(profit_amount_bdt);
		$('#sodyeingmktcostqpricedtlFrm input[name="profit_per['+i+']"]').val(profit_per);
		$('#sodyeingmktcostqpricedtlFrm input[name="quoted_price['+i+']"]').val(quoted_price);
		$('#sodyeingmktcostqpricedtlFrm input[name="profit_amount['+i+']"]').val(profit_amount);
	}
	
	// calculateProfitForeignCurrency(i){
	// 	let cost_per_kg=($('#sodyeingmktcostqpricedtlFrm input[name="cost_per_kg['+i+']"]').val())*1;
	// 	let quoted_price=($('#sodyeingmktcostqpricedtlFrm input[name="quoted_price['+i+']"]').val())*1;
	//     let profit_amount=quoted_price-cost_per_kg;
	// 	$('#sodyeingmktcostqpricedtlFrm input[name="profit_amount['+i+']"]').val(profit_amount);
	// }

	// calculate(i){
	// 	let rate=$('#sodyeingmktcostqpricedtlFrm input[name="rate['+i+']"]').val();
	// 	let qty=$('#sodyeingmktcostqpricedtlFrm input[name="qty['+i+']"]').val();
	//     let amount=msApp.multiply(qty,rate);
	// 	$('#sodyeingmktcostqpricedtlFrm input[name="amount['+i+']"]').val(amount)
	// }
	

	// calculateAmount(iteration,count,field){
	// 	let rate=($('#sodyeingmktcostqpricedtlFrm input[name="rate['+iteration+']"]').val())*1;
	// 	let qty=($('#sodyeingmktcostqpricedtlFrm input[name="qty['+iteration+']"]').val())*1;
	//     let amount=msApp.multiply(qty,rate);
	// 	$('#sodyeingmktcostqpricedtlFrm input[name="amount['+iteration+']"]').val(amount)
	// 	if(field==='qty'){
	// 	this.copyQty(qty,iteration,count);
	// 	}
	// 	else if(field==='rate'){
	// 	this.copyRate(rate,iteration,count);
	// 	}
	// }
	
	// copyQty(qty,iteration,count)
	// {
	// 	for(var i=iteration;i<=count;i++)
	// 	{
	// 		let rate=($('#sodyeingmktcostqpricedtlFrm input[name="rate['+i+']"]').val())*1;
	// 		let amount=msApp.multiply(qty,rate);
	// 		$('#sodyeingmktcostqpricedtlFrm input[name="qty['+i+']"]').val(qty)
	// 		$('#sodyeingmktcostqpricedtlFrm input[name="amount['+i+']"]').val(amount)
	// 	}
	// }


	// copyRate(rate,iteration,count)
	// {
	// 	for(var i=iteration;i<=count;i++)
	// 	{
	// 		let qty=($('#sodyeingmktcostqpricedtlFrm input[name="qty['+i+']"]').val())*1;
	// 		let amount=msApp.multiply(qty,rate);
	// 		$('#sodyeingmktcostqpricedtlFrm input[name="rate['+i+']"]').val(rate)
	// 		$('#sodyeingmktcostqpricedtlFrm input[name="amount['+i+']"]').val(amount)
	// 	}
	// }

}
window.MsSoDyeingMktCostQpricedtl=new MsSoDyeingMktCostQpricedtlController(new MsSoDyeingMktCostQpricedtlModel());