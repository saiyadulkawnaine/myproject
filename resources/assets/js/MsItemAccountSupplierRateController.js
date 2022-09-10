require('./datagrid-filter.js');
let MsItemAccountSupplierRateModel = require('./MsItemAccountSupplierRateModel');
class MsItemAccountSupplierRateController {
	constructor(MsItemAccountSupplierRateModel)
	{
		this.MsItemAccountSupplierRateModel = MsItemAccountSupplierRateModel;
		this.formId='itemaccountsupplierrateFrm';
		this.dataTable='#itemaccountsupplierrateTbl';
		this.route=msApp.baseUrl()+"/itemaccountsupplierrate"
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
			this.MsItemAccountSupplierRateModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsItemAccountSupplierRateModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
        msApp.resetForm(this.formId);
        $('#itemaccountsupplierrateFrm  [name=item_description]').val($('#itemaccountFrm  [name=item_description]').val());
		$('#itemaccountsupplierrateFrm  [name=specification]').val($('#itemaccountFrm  [name=specification]').val());
        $('#itemaccountsupplierrateFrm  [name=item_account_supplier_id]').val($('#itemaccountsupplierFrm  [name=id]').val());
        $('#itemaccountsupplierrateFrm  [name=supplier_id]').val($('#itemaccountsupplierFrm  [name=supplier_id]').val());
        $('#itemaccountsupplierrateFrm  [name=custom_name]').val($('#itemaccountsupplierFrm  [name=custom_name]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsItemAccountSupplierRateModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsItemAccountSupplierRateModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#itemaccountsupplierrateTbl').datagrid('reload');
		msApp.resetForm('itemaccountsupplierrateFrm');
		$('#itemaccountsupplierrateFrm  [name=item_account_supplier_id]').val($('#itemaccountsupplierFrm  [name=id]').val());
		$('#itemaccountsupplierrateFrm  [name=item_description]').val($('#itemaccountFrm  [name=item_description]').val());
		$('#itemaccountsupplierrateFrm  [name=specification]').val($('#itemaccountFrm  [name=specification]').val());
		$('#itemaccountsupplierrateFrm  [name=supplier_id]').val($('#itemaccountsupplierFrm  [name=supplier_id]').val());
        $('#itemaccountsupplierrateFrm  [name=custom_name]').val($('#itemaccountsupplierFrm  [name=custom_name]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsItemAccountSupplierRateModel.get(index,row);
		/* let supplyRate=this.MsItemAccountSupplierRateModel.get(index,row);
		supplyRate.then(function (response) {
			let Presponse=response
			self.supplierDropDown (response.data.fromData.item_account_id)
			.then(function(){
				msApp.set(index,row,Presponse.data)
			})
		})
		.catch(function (error) {
			console.log(error);
		}); */
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
		return '<a href="javascript:void(0)"  onClick="MsItemAccountSupplierRate.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	/* supplierDropDown(item_account_id)
	{
		let data= axios.get(this.route+"/getitemsupplier?item_account_id="+item_account_id);
		let g=data.then(function (response) {
			$('select[name="supplier_id"]').empty();
			$('select[name="supplier_id"]').append('<option value="">-Select-</option>');
			//alert(response.data)
			$.each(response.data, function(key, value) {
			$('select[name="supplier_id"]').append('<option value="'+ value.supplier_id +'">'+ value.name +'</option>');
			});
		})
		.catch(function (error) {
		 	console.log(error);
		});
		return g;
	}

	supplierCustomName(supplier_id)
	{
		let data= axios.get(this.route+"/custom?supplier_id="+supplier_id);
		let cn=data.then(function (response) {
			$.each(response.data, function(key, value) {
				$('#itemaccountsupplierrateFrm [name="custom_name"]').val(value.custom_name);
			});
		})
		.catch(function (error) {
			console.log(error);
		});
		return cn;
	} */

}
window.MsItemAccountSupplierRate=new MsItemAccountSupplierRateController(new MsItemAccountSupplierRateModel());
