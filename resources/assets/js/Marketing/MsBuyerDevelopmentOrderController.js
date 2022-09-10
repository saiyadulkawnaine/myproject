require('../datagrid-filter.js');
let MsBuyerDevelopmentOrderModel = require('./MsBuyerDevelopmentOrderModel');
class MsBuyerDevelopmentOrderController {
	constructor(MsBuyerDevelopmentOrderModel)
	{
		this.MsBuyerDevelopmentOrderModel = MsBuyerDevelopmentOrderModel;
		this.formId='buyerdevelopmentorderFrm';
		this.dataTable='#buyerdevelopmentorderTbl';
		this.route=msApp.baseUrl()+"/buyerdevelopmentorder"
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
			this.MsBuyerDevelopmentOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBuyerDevelopmentOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj) ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#buyerdevelopmentordercosi').html('');
		$('#buyerdevelopmentorderFrm  [name=buyer_development_intm_id]').val($('#buyerdevelopmentintmFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBuyerDevelopmentOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerDevelopmentOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		//$('#buyerdevelopmentorderTbl').datagrid('reload');
		MsBuyerDevelopmentOrder.resetForm();
		$('#buyerdevelopmentorderFrm  [name=id]').val(d.id);
		$('#buyerdevelopmentorderFrm  [name=buyer_development_intm_id]').val($('#buyerdevelopmentintmFrm  [name=id]').val());
		MsBuyerDevelopmentOrder.get($('#buyerdevelopmentintmFrm  [name=id]').val());
		$('#buyerdevelopmentorderqtyFrm  [name=buyer_development_order_id]').val($('#buyerdevelopmentorderFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerDevelopmentOrderModel.get(index,row);
		msApp.resetForm('buyerdevelopmentorderqtyFrm');
		$('#buyerdevelopmentorderqtyFrm  [name=buyer_development_order_id]').val(row.id);
		MsBuyerDevelopmentOrderQty.get(row.id);
		
	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsBuyerDevelopmentOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	get(buyer_development_intm_id){
		let data= axios.get(this.route+"?buyer_development_intm_id="+buyer_development_intm_id);
		data.then(function (response) {
			$('#buyerdevelopmentorderTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}
}


window.MsBuyerDevelopmentOrder=new MsBuyerDevelopmentOrderController(new MsBuyerDevelopmentOrderModel());
MsBuyerDevelopmentOrder.showGrid([]);