let MsInvGeneralRcvRtnItemModel = require('./MsInvGeneralRcvRtnItemModel');

class MsInvGeneralRcvRtnItemController {
	constructor(MsInvGeneralRcvRtnItemModel)
	{
		this.MsInvGeneralRcvRtnItemModel = MsInvGeneralRcvRtnItemModel;
		this.formId='invgeneralrcvrtnitemFrm';	             
		this.dataTable='#invgeneralrcvrtnitemTbl';
		this.route=msApp.baseUrl()+"/invgeneralrcvrtnitem"
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
		let inv_isu_id=$('#invgeneralrcvrtnFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;

		if(formObj.id){
			this.MsInvGeneralRcvRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralRcvRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invgeneralrcvrtnitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgeneralrcvrtnitemFrm [name=store_id]').val(store_id);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralRcvRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralRcvRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvGeneralRcvRtnItem.resetForm();
		MsInvGeneralRcvRtnItem.get(d.inv_isu_id)

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data= this.MsInvGeneralRcvRtnItemModel.get(index,row);
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
			$('#invgeneralrcvrtnitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralRcvRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invgeneralrcvrtnitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invgeneralrcvrtnitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneralrcvrtnitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invgeneralrcvrtnitemFrm [name=item_desc]').val(row.item_description);
				$('#invgeneralrcvrtnitemFrm [name=specification]').val(row.specification);
				$('#invgeneralrcvrtnitemFrm [name=item_category]').val(row.category_name);
				$('#invgeneralrcvrtnitemFrm [name=item_class]').val(row.class_name);
				$('#invgeneralrcvrtnitemFrm [name=uom_code]').val(row.uom_name);
				$('#invgeneralrcvrtnitemFrm [name=rate]').val(row.rate);
				$('#invgeneralrcvrtnitemFrm [name=receive_rate]').val(row.receive_rate);
				$('#invgeneralrcvrtnitemFrm [name=inv_general_rcv_item_id]').val(row.id);
				$('#invgeneralrcvrtnitemFrm [name=inv_rcv_id]').val(row.inv_rcv_id);
				$('#invgeneralrcvrtnitemFrm [name=item_id]').val(row.item_account_id);
				$('#invgeneralrcvrtnitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_class=$('#invgeneralrcvrtnitemsearchFrm [name=item_class]').val();
		let item_desc=$('#invgeneralrcvrtnitemsearchFrm [name=item_desc]').val();
		let receive_no=$('#invgeneralrcvrtnitemsearchFrm [name=receive_no]').val();
		let challan_no=$('#invgeneralrcvrtnitemsearchFrm [name=challan_no]').val();
		let inv_isu_id=$('#invgeneralrcvrtnFrm [name=id]').val();

		let params={};
		params.item_class=item_class;
		params.item_desc=item_desc;
		params.receive_no=receive_no;
		params.challan_no=challan_no;
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgeneralrcvrtnitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty_form()
	{
		
		let qty=$('#invgeneralrcvrtnitemFrm input[name=qty]').val();
		let rate=$('#invgeneralrcvrtnitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invgeneralrcvrtnitemFrm input[name=amount]').val(amount);
	}

	
}
window.MsInvGeneralRcvRtnItem=new MsInvGeneralRcvRtnItemController(new MsInvGeneralRcvRtnItemModel());
MsInvGeneralRcvRtnItem.itemSearchGrid([]);
MsInvGeneralRcvRtnItem.showGrid([]);