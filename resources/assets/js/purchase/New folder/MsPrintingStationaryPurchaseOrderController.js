require('./../datagrid-filter.js');
let MsPrintingStationaryPurchaseOrderModel = require('./MsPrintingStationaryPurchaseOrderModel');
class MsPrintingStationaryPurchaseOrderController {
	constructor(MsPrintingStationaryPurchaseOrderModel)
	{
		this.MsPrintingStationaryPurchaseOrderModel = MsPrintingStationaryPurchaseOrderModel;
		this.formId='bulkfabricpurchaseFrm';
		this.dataTable='#bulkfabricpurchaseTbl';
		this.route=msApp.baseUrl()+"/printingstationarypurchaseorder"
	}

	submit()
	{
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsPrintingStationaryPurchaseOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPrintingStationaryPurchaseOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPrintingStationaryPurchaseOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPrintingStationaryPurchaseOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#bulkfabricpurchaseTbl').datagrid('reload');
		//$('#BulkFabricPurchaseFrm  [name=id]').val(d.id);
		msApp.resetForm('bulkfabricpurchaseFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPrintingStationaryPurchaseOrderModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBulkFabricPurchase.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPrintingStationaryPurchaseOrder=new MsPrintingStationaryPurchaseOrderController(new MsPrintingStationaryPurchaseOrderModel());
MsPrintingStationaryPurchaseOrder.showGrid();
