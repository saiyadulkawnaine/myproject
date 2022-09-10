let MsMktCostQuoteTargetPriceModel = require('./MsMktCostQuoteTargetPriceModel');
class MsMktCostQuoteTargetPriceController {
	constructor(MsMktCostQuoteTargetPriceModel)
	{
		this.MsMktCostQuoteTargetPriceModel = MsMktCostQuoteTargetPriceModel;
		this.formId='mktcostquotetargetpriceFrm';
		this.route=msApp.baseUrl()+"/mktcost"
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

		let formObj=msApp.get('mktcostFrm');
		formObj['quote_price']=$('#quote_price').val()
		formObj['target_price']=$('#target_price').val()
		if(formObj.id){
			this.MsMktCostQuoteTargetPriceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostQuoteTargetPriceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostQuoteTargetPriceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostQuoteTargetPriceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#mktcostquotetargetpriceTbl').datagrid('reload');
		//msApp.resetForm('mktcostquotetargetpriceFrm');
	}


	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostQuoteTargetPrice.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}




}
window.MsMktCostQuoteTargetPrice=new MsMktCostQuoteTargetPriceController(new MsMktCostQuoteTargetPriceModel());
