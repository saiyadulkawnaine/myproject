require('./../datagrid-filter.js');
let MsDyesChemicalPurchaseOrderModel = require('./MsDyesChemicalPurchaseOrderModel');
class MsDyesChemicalPurchaseOrderController {
	constructor(MsDyesChemicalPurchaseOrderModel)
	{
		this.MsDyesChemicalPurchaseOrderModel = MsDyesChemicalPurchaseOrderModel;
		this.formId='bulkfabricpurchaseFrm';
		this.dataTable='#bulkfabricpurchaseTbl';
		this.route=msApp.baseUrl()+"/dyeschemicalpurchaseorder"
	}

	submit()
	{
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsDyesChemicalPurchaseOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsDyesChemicalPurchaseOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsDyesChemicalPurchaseOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsDyesChemicalPurchaseOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
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
		this.MsDyesChemicalPurchaseOrderModel.get(index,row);
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
window.MsDyesChemicalPurchaseOrder=new MsDyesChemicalPurchaseOrderController(new MsDyesChemicalPurchaseOrderModel());
MsDyesChemicalPurchaseOrder.showGrid();
