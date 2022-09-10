let MsInvDyeChemRcvRtnItemModel = require('./MsInvDyeChemRcvRtnItemModel');

class MsInvDyeChemRcvRtnItemController {
	constructor(MsInvDyeChemRcvRtnItemModel)
	{
		this.MsInvDyeChemRcvRtnItemModel = MsInvDyeChemRcvRtnItemModel;
		this.formId='invdyechemrcvrtnitemFrm';	             
		this.dataTable='#invdyechemrcvrtnitemTbl';
		this.route=msApp.baseUrl()+"/invdyechemrcvrtnitem"
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
		let inv_isu_id=$('#invdyechemrcvrtnFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;

		if(formObj.id){
			this.MsInvDyeChemRcvRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemRcvRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invdyechemrcvrtnitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invdyechemrcvrtnitemFrm [name=store_id]').val(store_id);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemRcvRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemRcvRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemRcvRtnItem.resetForm();
		MsInvDyeChemRcvRtnItem.get(d.inv_isu_id)

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data= this.MsInvDyeChemRcvRtnItemModel.get(index,row);
		data.then(function(response){
		}).catch(function(error){
			console.log(error);
		})

	}
	get(inv_isu_id){
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemrcvrtnitemTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemRcvRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemrcvrtnitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemrcvrtnitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemrcvrtnitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemrcvrtnitemFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemrcvrtnitemFrm [name=specification]').val(row.specification);
				$('#invdyechemrcvrtnitemFrm [name=item_category]').val(row.category_name);
				$('#invdyechemrcvrtnitemFrm [name=item_class]').val(row.class_name);
				$('#invdyechemrcvrtnitemFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemrcvrtnitemFrm [name=rate]').val(row.rate);
				$('#invdyechemrcvrtnitemFrm [name=receive_rate]').val(row.receive_rate);
				$('#invdyechemrcvrtnitemFrm [name=inv_dye_chem_rcv_item_id]').val(row.id);
				$('#invdyechemrcvrtnitemFrm [name=inv_rcv_id]').val(row.inv_rcv_id);
				$('#invdyechemrcvrtnitemFrm [name=item_id]').val(row.item_account_id);
				$('#invdyechemrcvrtnitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_class=$('#invdyechemrcvrtnitemsearchFrm [name=item_class]').val();
		let item_desc=$('#invdyechemrcvrtnitemsearchFrm [name=item_desc]').val();
		let receive_no=$('#invdyechemrcvrtnitemsearchFrm [name=receive_no]').val();
		let challan_no=$('#invdyechemrcvrtnitemsearchFrm [name=challan_no]').val();
		let inv_isu_id=$('#invdyechemrcvrtnFrm [name=id]').val();

		let params={};
		params.item_class=item_class;
		params.item_desc=item_desc;
		params.receive_no=receive_no;
		params.challan_no=challan_no;
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemrcvrtnitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty_form()
	{
		
		let qty=$('#invdyechemrcvrtnitemFrm input[name=qty]').val();
		let rate=$('#invdyechemrcvrtnitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invdyechemrcvrtnitemFrm input[name=amount]').val(amount);
	}

	
}
window.MsInvDyeChemRcvRtnItem=new MsInvDyeChemRcvRtnItemController(new MsInvDyeChemRcvRtnItemModel());
MsInvDyeChemRcvRtnItem.itemSearchGrid([]);
MsInvDyeChemRcvRtnItem.showGrid([]);