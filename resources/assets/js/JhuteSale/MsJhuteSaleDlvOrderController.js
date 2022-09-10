let MsJhuteSaleDlvOrderModel = require('./MsJhuteSaleDlvOrderModel');
require('./../datagrid-filter.js');
class MsJhuteSaleDlvOrderController {
	constructor(MsJhuteSaleDlvOrderModel)
	{
		this.MsJhuteSaleDlvOrderModel = MsJhuteSaleDlvOrderModel;
		this.formId='jhutesaledlvorderFrm';
		this.dataTable='#jhutesaledlvorderTbl';
		this.route=msApp.baseUrl()+"/jhutesaledlvorder"
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
			this.MsJhuteSaleDlvOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsJhuteSaleDlvOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#jhutesaledlvorderFrm [id="buyer_id"]').combobox('setValue', '');
		$('#jhutesaledlvorderFrm [id="advised_by_id"]').combobox('setValue', '');
		$('#jhutesaledlvorderFrm [id="price_verified_by_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsJhuteSaleDlvOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsJhuteSaleDlvOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#jhutesaledlvorderTbl').datagrid('reload');
		msApp.resetForm('jhutesaledlvorderFrm');
		$('#jhutesaledlvorderFrm [id="buyer_id"]').combobox('setValue', '');
		$('#jhutesaledlvorderFrm [id="advised_by_id"]').combobox('setValue', '');
		$('#jhutesaledlvorderFrm [id="price_verified_by_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let dlvorder = this.MsJhuteSaleDlvOrderModel.get(index,row);
		dlvorder.then(function (response) {
			$('#jhutesaledlvorderFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#jhutesaledlvorderFrm [id="advised_by_id"]').combobox('setValue', response.data.fromData.advised_by_id);
			$('#jhutesaledlvorderFrm [id="price_verified_by_id"]').combobox('setValue', response.data.fromData.price_verified_by_id);
		}).catch(function (error) {
			console.log(error);
		})
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
		return '<a href="javascript:void(0)"  onClick="MsJhuteSaleDlvOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf()
	{
		var id = $('#jhutesaledlvorderFrm [name=id]').val();
		if(id==""){
			alert("Select an Order First");
			return;
		}
		window.open(this.route+"/getdlvorderpdf?id="+id);
	}

}
window.MsJhuteSaleDlvOrder=new MsJhuteSaleDlvOrderController(new MsJhuteSaleDlvOrderModel());
MsJhuteSaleDlvOrder.showGrid();
$('#jhutesaledlvordertabs').tabs({
    onSelect:function(title,index){
		let jhute_sale_dlv_order_id = $('#jhutesaledlvorderFrm [name=id]').val();
  		var data={};
		data.jhute_sale_dlv_order_id = jhute_sale_dlv_order_id;
  		if(index==1){
			if(jhute_sale_dlv_order_id ===''){
				$('#jhutesaledlvordertabs').tabs('select',0);
				msApp.showError('Select Jhute Sale Reference First',0);
				return;
		 	}
			msApp.resetForm('jhutesaledlvorderitemFrm');
			$('#jhutesaledlvorderitemFrm  [name=jhute_sale_dlv_order_id]').val(jhute_sale_dlv_order_id);
			MsJhuteSaleDlvOrderItem.showGrid(jhute_sale_dlv_order_id);
   		}
    	if(index==2){
			if(jhute_sale_dlv_order_id===''){
				$('#jhutesaledlvordertabs').tabs('select',0);
				msApp.showError('Select Jhute Sale Item First',0);
				return;
		    }
			msApp.resetForm('jhutesaledlvorderpaymentFrm');
			$('#jhutesaledlvorderpaymentFrm  [name=jhute_sale_dlv_order_id]').val(jhute_sale_dlv_order_id);
			MsJhuteSaleDlvOrderPayment.showGrid(jhute_sale_dlv_order_id);
        }
    }
});