//require('./jquery.easyui.min.js');
let MsAopBuyerChargeModel = require('./MsAopBuyerChargeModel');
require('./datagrid-filter.js');

class MsAopBuyerChargeController {
	constructor(MsAopBuyerChargeModel)
	{
		this.MsAopBuyerChargeModel = MsAopBuyerChargeModel;
		this.formId='aopbuyerchargeFrm';
		this.dataTable='#aopbuyerchargeTbl';
		this.route=msApp.baseUrl()+"/aopbuyercharge"
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
			this.MsAopBuyerChargeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAopBuyerChargeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAopBuyerChargeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAopBuyerChargeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#aopbuyerchargeTbl').datagrid('reload');
		//$('#BuyerAopChargeFrm  [name=id]').val(d.id);
		msApp.resetForm('aopbuyerchargeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAopBuyerChargeModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsAopBuyerCharge.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAopBuyerCharge=new MsAopBuyerChargeController(new MsAopBuyerChargeModel());
MsAopBuyerCharge.showGrid();
