let MsInvGeneralIsuRtnItemModel = require('./MsInvGeneralIsuRtnItemModel');

class MsInvGeneralIsuRtnItemController {
	constructor(MsInvGeneralIsuRtnItemModel)
	{
		this.MsInvGeneralIsuRtnItemModel = MsInvGeneralIsuRtnItemModel;
		this.formId='invgeneralisurtnitemFrm';	             
		this.dataTable='#invgeneralisurtnitemTbl';
		this.route=msApp.baseUrl()+"/invgeneralisurtnitem"
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
		let inv_rcv_id=$('#invgeneralisurtnFrm [name=id]').val()
		let inv_general_rcv_id=$('#invgeneralisurtnFrm [name=inv_general_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_general_rcv_id=inv_general_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvGeneralIsuRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralIsuRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invgeneralisurtnitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgeneralisurtnitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralIsuRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralIsuRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvGeneralIsuRtnItem.resetForm();
		MsInvGeneralIsuRtnItem.get(d.inv_general_rcv_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvGeneralIsuRtnItemModel.get(index,row);

	}
	get(inv_general_rcv_id){
		let params={};
		params.inv_general_rcv_id=inv_general_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgeneralisurtnitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralIsuRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invgeneralisurtnitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invgeneralisurtnitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				self.getRate(row.item_account_id);
				$('#invgeneralisurtnitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invgeneralisurtnitemFrm [name=item_id]').val(row.item_account_id);
				$('#invgeneralisurtnitemFrm [name=item_desc]').val(row.item_description);
				$('#invgeneralisurtnitemFrm [name=specification]').val(row.specification);
				$('#invgeneralisurtnitemFrm [name=item_category]').val(row.category_name);
				$('#invgeneralisurtnitemFrm [name=item_class]').val(row.class_name);
				$('#invgeneralisurtnitemFrm [name=uom_code]').val(row.uom_name);
				$('#invgeneralisurtnitemFrm [name=qty]').val(row.qty);
				$('#invgeneralisurtnitemFrm [name=rate]').val(row.rate);
				$('#invgeneralisurtnitemFrm [name=amount]').val(row.amount);
				$('#invgeneralisurtnitemFrm [name=transfer_no]').val(row.transfer_no);
				$('#invgeneralisurtnitemFrm [name=inv_general_isu_item_id]').val(row.inv_general_isu_item_id);
				$('#invgeneralisurtnitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let challan_no=$('#invgeneralisurtnitemsearchFrm [name=challan_no]').val();
		let inv_rcv_id=$('#invgeneralisurtnFrm [name=id]').val();
		let params={};
		params.challan_no=challan_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgeneralisurtnitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	getRate(item_account_id){
		let self=this;
		let params={};
		params.item_account_id=item_account_id
		let d = axios.get(this.route+"/getrate",{params})
		.then(function(response){
				$('#invgeneralisurtnitemFrm  [name=rate]').val(response.data.store_rate);
				self.calculate_qty_form();
		})
		.catch(function(error){
			console.log(error);
		});
	}


	

	
calculate_qty_form()
	{
		
		let qty=$('#invgeneralisurtnitemFrm input[name=qty]').val();
		let rate=$('#invgeneralisurtnitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invgeneralisurtnitemFrm input[name=amount]').val(amount);
	}

	openorderWindow()
	{
		$('#invgeneralisurtnordersearchwindow').window('open');

	}

	orderSearchGrid(data){
		let self=this;
		$('#invgeneralisurtnordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invgeneralisurtnitemFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invgeneralisurtnitemFrm [name=sales_order_id]').val(row.sale_order_id);
				$('#invgeneralisurtnordersearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachOrder(){
		let order_no=$('#invgeneralisurtnordersearchFrm [name=order_no]').val();
		let style_ref=$('#invgeneralisurtnordersearchFrm [name=style_ref]').val();
		let inv_general_rcv_id=$('#invgeneralisurtnFrm [name=id]').val();
		let params={};
		params.order_no=order_no;
		params.style_ref=style_ref;
		params.inv_general_rcv_id=inv_general_rcv_id;
		let d=axios.get(this.route+'/getorder',{params})
		.then(function(response){
			$('#invgeneralisurtnordersearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
	

}
window.MsInvGeneralIsuRtnItem=new MsInvGeneralIsuRtnItemController(new MsInvGeneralIsuRtnItemModel());
MsInvGeneralIsuRtnItem.itemSearchGrid([]);
MsInvGeneralIsuRtnItem.orderSearchGrid([]);
MsInvGeneralIsuRtnItem.showGrid([]);