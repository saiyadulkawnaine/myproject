let MsInvGeneralIsuItemModel = require('./MsInvGeneralIsuItemModel');

class MsInvGeneralIsuItemController {
	constructor(MsInvGeneralIsuItemModel)
	{
		this.MsInvGeneralIsuItemModel = MsInvGeneralIsuItemModel;
		this.formId='invgeneralisuitemFrm';	             
		this.dataTable='#invgeneralisuitemTbl';
		this.route=msApp.baseUrl()+"/invgeneralisuitem"
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
		let inv_isu_id=$('#invgeneralisuFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;

		if(formObj.id){
			this.MsInvGeneralIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invgeneralisuitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgeneralisuitemFrm [name=store_id]').val(store_id);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralIsuItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralIsuItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvGeneralIsuItem.resetForm();
		MsInvGeneralIsuItem.get(d.inv_isu_id)

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data= this.MsInvGeneralIsuItemModel.get(index,row);
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
			$('#invgeneralisuitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralIsuItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invgeneralisuitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invgeneralisuitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneralisuitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invgeneralisuitemFrm [name=item_id]').val(row.item_account_id);
				$('#invgeneralisuitemFrm [name=item_desc]').val(row.item_desc);
				$('#invgeneralisuitemFrm [name=uom_code]').val(row.uom_name);
				$('#invgeneralisuitemFrm [name=department_id]').val(row.department_id);
				$('#invgeneralisuitemFrm [name=purpose_id]').val(row.purpose_id);
				$('#invgeneralisuitemFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invgeneralisuitemFrm [name=qty]').val(row.qty);
				$('#invgeneralisuitemFrm [name=inv_general_isu_rq_item_id]').val(row.id);
				$('#invgeneralisuitemFrm [name=custom_no]').val(row.custom_no);
				$('#invgeneralisuitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_class=$('#invgeneralisuitemsearchFrm [name=item_class]').val();
		let item_desc=$('#invgeneralisuitemsearchFrm [name=item_desc]').val();
		let inv_isu_id=$('#invgeneralisuFrm [name=id]').val();

		let params={};
		params.item_class=item_class;
		params.item_desc=item_desc;
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgeneralisuitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	openorderWindow()
	{
		$('#invgeneralisuordersearchwindow').window('open');

	}

	orderSearchGrid(data){
		let self=this;
		$('#invgeneralisuordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneralisuitemFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invgeneralisuitemFrm [name=sale_order_id]').val(row.sale_order_id);
				$('#invgeneralisuordersearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachOrder(){
		let order_no=$('#invgeneralisuordersearchFrm [name=order_no]').val();
		let style_ref=$('#invgeneralisuordersearchFrm [name=style_ref]').val();
		let inv_isu_id=$('#invgeneralisuFrm [name=id]').val();
		let params={};
		params.order_no=order_no;
		params.style_ref=style_ref;
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route+'/getorder',{params})
		.then(function(response){
			$('#invgeneralisuordersearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
}
window.MsInvGeneralIsuItem=new MsInvGeneralIsuItemController(new MsInvGeneralIsuItemModel());
MsInvGeneralIsuItem.itemSearchGrid([]);
MsInvGeneralIsuItem.orderSearchGrid([]);
MsInvGeneralIsuItem.showGrid([]);