//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsItemAccountModel = require('./MsItemAccountModel');
class MsItemAccountController {
	constructor(MsItemAccountModel)
	{
		this.MsItemAccountModel = MsItemAccountModel;
		this.formId='itemaccountFrm';
		this.dataTable='#itemaccountTbl';
		this.route=msApp.baseUrl()+"/itemaccount"
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
			this.MsItemAccountModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsItemAccountModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsItemAccountModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsItemAccountModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#itemaccountTbl').datagrid('reload');
		$('#itemaccountFrm  [name=id]').val(d.id);
		msApp.resetForm('itemaccountratioFrm');
	    $('#itemaccountratioFrm  [name=item_account_id]').val(d.id);
		MsItemAccountRatio.showGrid(d.id);
		//msApp.resetForm('itemaccountFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsItemAccountModel.get(index,row);
		msApp.resetForm('itemaccountratioFrm');
		$('#itemaccountratioFrm  [name=item_account_id]').val(row.id);
		MsItemAccountRatio.showGrid(row.id);
	}

	getParams(){
		let params={};
		params.itemcategory_id = $('#itemsearchFrm  [name=itemcategory_id]').val();
		params.itemclass_id = $('#itemsearchFrm  [name=itemclass_id]').val();
		params.item_nature_id = $('#itemsearchFrm  [name=item_nature_id]').val();
		params.yarncount_id = $('#itemsearchFrm  [name=yarncount_id]').val();
		params.sub_class_name = $('#itemsearchFrm  [name=sub_class_name]').val();
		return params;
	}

	getItem(){
		let params=this.getParams();
		let st= axios.get(this.route,{params})
		.then(function(response){
			$('#itemaccountTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		$('#itemaccountTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsItemAccount.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	getCategoryData(id){
		let data= axios.get(msApp.baseUrl()+"/itemcategory/"+id+"/edit");
			let g=data.then(function (response) {
				//alert(response.data.fromData.identity)
				$('#itemaccountFrm  [name=identity]').val(response.data.fromData.identity);


		})
		.catch(function (error) {
		 	console.log(error);
		});
	}
}
window.MsItemAccount=new MsItemAccountController(new MsItemAccountModel());
MsItemAccount.showGrid([]);
  $('#itemaccountstabs').tabs({
		onSelect:function(title,index){
			let item_account_id=$('#itemaccountFrm  [name=id]').val();
			let item_account_supplier_id=$('#itemaccountsupplierFrm  [name=id]').val();
			var data={};
			data.item_account_id=item_account_id
			data.item_account_supplier_id=item_account_supplier_id

			var identity = $('#itemaccountFrm  [name=identity]').val()
			if(index==1){
				if(identity !=1){
					$('#itemaccountstabs').tabs('select',0);
					msApp.showError('No Need Yarn Ratio',0);
					return;
				}
			}
			if(index==2){
				if(item_account_id ===''){
					$('#itemaccountstabs').tabs('select',0);
					msApp.showError('Select an Item Account First',0);
					return;
				}
				$('#itemaccountsupplierFrm [name=item_account_id]').val(item_account_id);
				let item_description = $('#itemaccountFrm  [name=item_description]').val();
				let specification = $('#itemaccountFrm  [name=specification]').val();
				//alert(item_description)
				$('#itemaccountsupplierFrm  [name=item_description]').val(item_description);
				$('#itemaccountsupplierFrm  [name=specification]').val(specification);
				MsItemAccountSupplier.showGrid(item_account_id);
			}
			if(index==3){
				if(item_account_supplier_id ===''){
					$('#itemaccountstabs').tabs('select',0);
					msApp.showError('Select a Tagged Supplier First',0);
					return;
				}
				$('#itemaccountsupplierrateFrm [name=item_account_supplier_id]').val(item_account_supplier_id);
				let item_description = $('#itemaccountFrm  [name=item_description]').val();
				let specification = $('#itemaccountFrm  [name=specification]').val();
				let custom_name = $('#itemaccountsupplierFrm  [name=custom_name]').val();
				let supplier_id = $('#itemaccountsupplierFrm  [name=supplier_id]').val();
				//alert(item_description)
				$('#itemaccountsupplierrateFrm  [name=item_description]').val(item_description);
				$('#itemaccountsupplierrateFrm  [name=specification]').val(specification);
				$('#itemaccountsupplierrateFrm  [name=custom_name]').val(custom_name);
				$('#itemaccountsupplierrateFrm  [name=supplier_name]').val(supplier_id);
				//MsItemAccountSupplierRate.supplierDropDown(item_account_id);
				//MsItemAccountSupplierRate.supplierCustomName(supplier_id);
				MsItemAccountSupplierRate.showGrid(item_account_supplier_id);
			}
			if(index==4){
				if(item_account_supplier_id ===''){
					$('#itemaccountstabs').tabs('select',0);
					msApp.showError('Select a Tagged Supplier First',0);
					return;
				}
				$('#itemaccountsupplierfeatFrm [name=item_account_supplier_id]').val(item_account_supplier_id);
				let item_description = $('#itemaccountFrm  [name=item_description]').val();
				let specification = $('#itemaccountFrm  [name=specification]').val();
				let custom_name = $('#itemaccountsupplierFrm  [name=custom_name]').val();
				let supplier_id = $('#itemaccountsupplierFrm  [name=supplier_id]').val();
				//alert(item_description)
				$('#itemaccountsupplierfeatFrm  [name=item_description]').val(item_description);
				$('#itemaccountsupplierfeatFrm  [name=specification]').val(specification);
				$('#itemaccountsupplierfeatFrm  [name=custom_name]').val(custom_name);
				$('#itemaccountsupplierfeatFrm  [name=supplier_name]').val(supplier_id);
				//MsItemAccountSupplierRate.supplierDropDown(item_account_id);
				//MsItemAccountSupplierRate.supplierCustomName(supplier_id);
				MsItemAccountSupplierFeat.showGrid(item_account_supplier_id);
			}
		}
  });
