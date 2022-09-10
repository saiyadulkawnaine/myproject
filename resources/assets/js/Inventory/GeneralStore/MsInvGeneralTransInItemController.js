let MsInvGeneralTransInItemModel = require('./MsInvGeneralTransInItemModel');

class MsInvGeneralTransInItemController {
	constructor(MsInvGeneralTransInItemModel)
	{
		this.MsInvGeneralTransInItemModel = MsInvGeneralTransInItemModel;
		this.formId='invgeneraltransinitemFrm';	             
		this.dataTable='#invgeneraltransinitemTbl';
		this.route=msApp.baseUrl()+"/invgeneraltransinitem"
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
		let inv_rcv_id=$('#invgeneraltransinFrm [name=id]').val()
		let inv_general_rcv_id=$('#invgeneraltransinFrm [name=inv_general_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_general_rcv_id=inv_general_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvGeneralTransInItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralTransInItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invgeneraltransinitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgeneraltransinitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralTransInItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralTransInItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvGeneralTransInItem.resetForm();
		MsInvGeneralTransInItem.get(d.inv_general_rcv_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvGeneralTransInItemModel.get(index,row);

	}
	get(inv_general_rcv_id){
		let params={};
		params.inv_general_rcv_id=inv_general_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgeneraltransinitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralTransInItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invgeneraltransinitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invgeneraltransinitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneraltransinitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invgeneraltransinitemFrm [name=item_id]').val(row.item_account_id);
				$('#invgeneraltransinitemFrm [name=item_desc]').val(row.item_description);
				$('#invgeneraltransinitemFrm [name=specification]').val(row.specification);
				$('#invgeneraltransinitemFrm [name=item_category]').val(row.category_name);
				$('#invgeneraltransinitemFrm [name=item_class]').val(row.class_name);
				$('#invgeneraltransinitemFrm [name=uom_code]').val(row.uom_name);
				$('#invgeneraltransinitemFrm [name=qty]').val(row.qty);
				$('#invgeneraltransinitemFrm [name=rate]').val(row.rate);
				$('#invgeneraltransinitemFrm [name=amount]').val(row.amount);
				$('#invgeneraltransinitemFrm [name=transfer_no]').val(row.transfer_no);
				$('#invgeneraltransinitemFrm [name=inv_general_isu_item_id]').val(row.inv_general_isu_item_id);
				$('#invgeneraltransinitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let challan_no=$('#invgeneraltransinitemsearchFrm [name=challan_no]').val();
		let inv_rcv_id=$('#invgeneraltransinFrm [name=id]').val();
		let params={};
		params.challan_no=challan_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgeneraltransinitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	
calculate_qty_form()
	{
		
		let qty=$('#invgeneraltransinitemFrm input[name=qty]').val();
		let rate=$('#invgeneraltransinitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invgeneraltransinitemFrm input[name=amount]').val(amount);
	}
	

}
window.MsInvGeneralTransInItem=new MsInvGeneralTransInItemController(new MsInvGeneralTransInItemModel());
MsInvGeneralTransInItem.itemSearchGrid([]);
MsInvGeneralTransInItem.showGrid([]);