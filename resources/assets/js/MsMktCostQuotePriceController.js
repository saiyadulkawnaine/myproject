let MsMktCostQuotePriceModel = require('./MsMktCostQuotePriceModel');
class MsMktCostQuotePriceController {
	constructor(MsMktCostQuotePriceModel)
	{
		this.MsMktCostQuotePriceModel = MsMktCostQuotePriceModel;
		this.formId='mktcostquotepriceFrm';
		this.dataTable='#mktcostquotepriceTbl';
		this.route=msApp.baseUrl()+"/mktcostquoteprice"
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

		let formObj=msApp.get('mktcostquotepriceFrm');
		if(formObj.id){
			this.MsMktCostQuotePriceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostQuotePriceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostQuotePriceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostQuotePriceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{

		msApp.resetForm('mktcostquotepriceFrm');
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		$('#mktcostquotepriceFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		MsMktCostQuotePrice.get(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostQuotePriceModel.get(index,row);
	}

	get(mkt_cost_id){
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostQuotePrice.showGrid(response.data)
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
			//url:this.route,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
	}
	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostQuotePrice.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#mktcostquotepriceFrm  [name=is_confirm]').val(0);
	}
}
window.MsMktCostQuotePrice=new MsMktCostQuotePriceController(new MsMktCostQuotePriceModel());
