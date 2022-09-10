require('./../datagrid-filter.js');
let MsShortTrimPurchaseModel = require('./MsShortTrimPurchaseModel');
class MsShortTrimPurchaseController {
	constructor(MsShortTrimPurchaseModel)
	{
		this.MsShortTrimPurchaseModel = MsShortTrimPurchaseModel;
		this.formId='bulktrimpurchaseFrm';
		this.dataTable='#bulktrimpurchaseTbl';
		this.route=msApp.baseUrl()+"/shorttrimpurchase"
	}

	submit()
	{
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsShortTrimPurchaseModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsShortTrimPurchaseModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsShortTrimPurchaseModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsShortTrimPurchaseModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#bulktrimpurchaseTbl').datagrid('reload');
		//$('#BulkTrimPurchaseFrm  [name=id]').val(d.id);
		msApp.resetForm('bulktrimpurchaseFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsShortTrimPurchaseModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBulkTrimPurchase.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsShortTrimPurchase=new MsShortTrimPurchaseController(new MsShortTrimPurchaseModel());
MsShortTrimPurchase.showGrid();
