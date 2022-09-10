require('./datagrid-filter.js');
let MsItemAccountSupplierFeatModel = require('./MsItemAccountSupplierFeatModel');
class MsItemAccountSupplierFeatController {
	constructor(MsItemAccountSupplierFeatModel)
	{
		this.MsItemAccountSupplierFeatModel = MsItemAccountSupplierFeatModel;
		this.formId='itemaccountsupplierfeatFrm';
		this.dataTable='#itemaccountsupplierfeatTbl';
		this.route=msApp.baseUrl()+"/itemaccountsupplierfeat"
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
			this.MsItemAccountSupplierFeatModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsItemAccountSupplierFeatModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
        msApp.resetForm(this.formId);
        $('#itemaccountsupplierfeatFrm  [name=item_description]').val($('#itemaccountFrm  [name=item_description]').val());
		$('#itemaccountsupplierfeatFrm  [name=specification]').val($('#itemaccountFrm  [name=specification]').val());
        $('#itemaccountsupplierfeatFrm  [name=item_account_supplier_id]').val($('#itemaccountsupplierFrm  [name=id]').val());
        $('#itemaccountsupplierfeatFrm  [name=supplier_id]').val($('#itemaccountsupplierFrm  [name=supplier_id]').val());
        $('#itemaccountsupplierfeatFrm  [name=custom_name]').val($('#itemaccountsupplierFrm  [name=custom_name]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsItemAccountSupplierFeatModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsItemAccountSupplierFeatModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#itemaccountsupplierfeatTbl').datagrid('reload');
		msApp.resetForm('itemaccountsupplierfeatFrm');
		$('#itemaccountsupplierfeatFrm  [name=item_account_supplier_id]').val($('#itemaccountsupplierFrm  [name=id]').val());
		$('#itemaccountsupplierfeatFrm  [name=item_description]').val($('#itemaccountFrm  [name=item_description]').val());
		$('#itemaccountsupplierfeatFrm  [name=specification]').val($('#itemaccountFrm  [name=specification]').val());
		$('#itemaccountsupplierfeatFrm  [name=supplier_id]').val($('#itemaccountsupplierFrm  [name=supplier_id]').val());
        $('#itemaccountsupplierfeatFrm  [name=custom_name]').val($('#itemaccountsupplierFrm  [name=custom_name]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsItemAccountSupplierFeatModel.get(index,row);
	}

	showGrid(item_account_supplier_id)
	{
		let self=this;
		var data={};
		data.item_account_supplier_id=item_account_supplier_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsItemAccountSupplierFeat.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsItemAccountSupplierFeat=new MsItemAccountSupplierFeatController(new MsItemAccountSupplierFeatModel());