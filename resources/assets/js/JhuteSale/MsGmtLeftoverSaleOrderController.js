let MsGmtLeftoverSaleOrderModel = require('./MsGmtLeftoverSaleOrderModel');
require('./../datagrid-filter.js');
class MsGmtLeftoverSaleOrderController {
	constructor(MsGmtLeftoverSaleOrderModel)
	{
		this.MsGmtLeftoverSaleOrderModel = MsGmtLeftoverSaleOrderModel;
		this.formId='gmtleftoversaleorderFrm';
		this.dataTable='#gmtleftoversaleorderTbl';
		this.route=msApp.baseUrl()+"/gmtleftoversaleorder"
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
			this.MsGmtLeftoverSaleOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGmtLeftoverSaleOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#gmtleftoversaleorderFrm [id="buyer_id"]').combobox('setValue', '');
		$('#gmtleftoversaleorderFrm [id="advised_by_id"]').combobox('setValue', '');
		$('#gmtleftoversaleorderFrm [id="price_verified_by_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsGmtLeftoverSaleOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGmtLeftoverSaleOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#gmtleftoversaleorderTbl').datagrid('reload');
		msApp.resetForm('gmtleftoversaleorderFrm');
		$('#gmtleftoversaleorderFrm [id="buyer_id"]').combobox('setValue', '');
		$('#gmtleftoversaleorderFrm [id="advised_by_id"]').combobox('setValue', '');
		$('#gmtleftoversaleorderFrm [id="price_verified_by_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let gmtdlvorder = this.MsGmtLeftoverSaleOrderModel.get(index,row);
		gmtdlvorder.then(function (response) {
			$('#gmtleftoversaleorderFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#gmtleftoversaleorderFrm [id="advised_by_id"]').combobox('setValue', response.data.fromData.advised_by_id);
			$('#gmtleftoversaleorderFrm [id="price_verified_by_id"]').combobox('setValue', response.data.fromData.price_verified_by_id);
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
		return '<a href="javascript:void(0)"  onClick="MsGmtLeftoverSaleOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf()
	{
		var id = $('#gmtleftoversaleorderFrm [name=id]').val();
		if(id==""){
			alert("Select an Order First");
			return;
		}
		window.open(this.route+"/getpdf?id="+id);
	}

}
window.MsGmtLeftoverSaleOrder=new MsGmtLeftoverSaleOrderController(new MsGmtLeftoverSaleOrderModel());
MsGmtLeftoverSaleOrder.showGrid();
$('#gmtleftoversaleordertabs').tabs({
    onSelect:function(title,index){
		let jhute_sale_dlv_order_id = $('#gmtleftoversaleorderFrm [name=id]').val();
  		var data={};
		data.jhute_sale_dlv_order_id = jhute_sale_dlv_order_id;
  		if(index==1){
			if(jhute_sale_dlv_order_id ===''){
				$('#gmtleftoversaleordertabs').tabs('select',0);
				msApp.showError('Select Leftover Delivery Order First',0);
				return;
		 	}
			$('#gmtleftoversaleorderstyledtlFrm  [name=jhute_sale_dlv_order_id]').val(jhute_sale_dlv_order_id);
			MsGmtLeftoverSaleOrderStyleDtl.showGrid(jhute_sale_dlv_order_id);
   		}
    	if(index==2){
			if(jhute_sale_dlv_order_id===''){
				$('#gmtleftoversaleordertabs').tabs('select',0);
				msApp.showError('Select Leftover Delivery Order Style Details First',0);
				return;
		    }
			$('#gmtleftoversaleorderpaymentFrm  [name=jhute_sale_dlv_order_id]').val(jhute_sale_dlv_order_id);
			MsGmtLeftoverSaleOrderPayment.showGrid(jhute_sale_dlv_order_id);
        }
    }
});