require('./datagrid-filter.js');
let MsItemAccountSupplierModel = require('./MsItemAccountSupplierModel');
class MsItemAccountSupplierController {
	constructor(MsItemAccountSupplierModel)
	{
		this.MsItemAccountSupplierModel = MsItemAccountSupplierModel;
		this.formId='itemaccountsupplierFrm';
		this.dataTable='#itemaccountsupplierTbl';
		this.route=msApp.baseUrl()+"/itemaccountsupplier"
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

		let item_account_id=$('#itemaccountFrm  [name=id]').val()
		if(item_account_id==""){
			alert("Select Item Account")
			return;
		}
		let formObj=msApp.get(this.formId);
		formObj['item_account_id']=item_account_id;
		if(formObj.id){
			this.MsItemAccountSupplierModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsItemAccountSupplierModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
        msApp.resetForm(this.formId);
		$('#itemaccountsupplierFrm  [name=item_account_id]').val($('#itemaccountFrm  [name=id]').val());
		$('#itemaccountsupplierFrm  [name=item_description]').val($('#itemaccountFrm  [name=item_description]').val());
		$('#itemaccountsupplierFrm  [name=specification]').val($('#itemaccountFrm  [name=specification]').val());
		$('#itemaccountsupplierFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsItemAccountSupplierModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsItemAccountSupplierModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#itemaccountsupplierTbl').datagrid('reload');
		//$('#itemaccountsupplierFrm  [name=id]').val('');
		//$('#itemaccountsupplierFrm  [name=supplier_id]').val('');
		//$('#itemaccountsupplierFrm  [name=custom_name]').val('');
		msApp.resetForm('itemaccountsupplierFrm');
		$('#itemaccountsupplierFrm  [name=item_account_id]').val($('#itemaccountFrm  [name=id]').val());
		$('#itemaccountsupplierFrm  [name=item_description]').val($('#itemaccountFrm  [name=item_description]').val());
		$('#itemaccountsupplierFrm  [name=specification]').val($('#itemaccountFrm  [name=specification]').val());
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let tagSupplier=this.MsItemAccountSupplierModel.get(index,row);
		tagSupplier.then(function(response){
			$('#itemaccountsupplierFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function(error){
			console.log(error);
		})
	}

	showGrid(item_account_id)
	{
        //alert(item_account_id)
		let self=this;
		var data={};
		data.item_account_id=item_account_id;
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
		return '<a href="javascript:void(0)"  onClick="MsItemAccountSupplier.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }
    

}
window.MsItemAccountSupplier=new MsItemAccountSupplierController(new MsItemAccountSupplierModel());
