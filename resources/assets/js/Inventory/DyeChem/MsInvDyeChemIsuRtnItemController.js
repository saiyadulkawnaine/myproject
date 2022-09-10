let MsInvDyeChemIsuRtnItemModel = require('./MsInvDyeChemIsuRtnItemModel');

class MsInvDyeChemIsuRtnItemController {
	constructor(MsInvDyeChemIsuRtnItemModel)
	{
		this.MsInvDyeChemIsuRtnItemModel = MsInvDyeChemIsuRtnItemModel;
		this.formId='invdyechemisurtnitemFrm';	             
		this.dataTable='#invdyechemisurtnitemTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurtnitem"
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
		let inv_rcv_id=$('#invdyechemisurtnFrm [name=id]').val()
		let inv_dye_chem_rcv_id=$('#invdyechemisurtnFrm [name=inv_dye_chem_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvDyeChemIsuRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invdyechemisurtnitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invdyechemisurtnitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvDyeChemIsuRtnItem.resetForm();
		MsInvDyeChemIsuRtnItem.get(d.inv_dye_chem_rcv_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvDyeChemIsuRtnItemModel.get(index,row);

	}
	get(inv_dye_chem_rcv_id){
		let params={};
		params.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invdyechemisurtnitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#invdyechemisurtnitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invdyechemisurtnitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				self.getRate(row.item_account_id);
				$('#invdyechemisurtnitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#invdyechemisurtnitemFrm [name=item_id]').val(row.item_account_id);
				$('#invdyechemisurtnitemFrm [name=item_desc]').val(row.item_description);
				$('#invdyechemisurtnitemFrm [name=specification]').val(row.specification);
				$('#invdyechemisurtnitemFrm [name=item_category]').val(row.category_name);
				$('#invdyechemisurtnitemFrm [name=item_class]').val(row.class_name);
				$('#invdyechemisurtnitemFrm [name=uom_code]').val(row.uom_name);
				$('#invdyechemisurtnitemFrm [name=qty]').val(row.qty);
				$('#invdyechemisurtnitemFrm [name=rate]').val(row.rate);
				$('#invdyechemisurtnitemFrm [name=amount]').val(row.amount);
				$('#invdyechemisurtnitemFrm [name=transfer_no]').val(row.transfer_no);
				$('#invdyechemisurtnitemFrm [name=inv_dye_chem_isu_item_id]').val(row.inv_dye_chem_isu_item_id);
				$('#invdyechemisurtnitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let challan_no=$('#invdyechemisurtnitemsearchFrm [name=challan_no]').val();
		let inv_rcv_id=$('#invdyechemisurtnFrm [name=id]').val();
		let params={};
		params.challan_no=challan_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invdyechemisurtnitemsearchTbl').datagrid('loadData',response.data);
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
				$('#invdyechemisurtnitemFrm  [name=rate]').val(response.data.store_rate);
				self.calculate_qty_form();
		})
		.catch(function(error){
			console.log(error);
		});
	}


	

	
calculate_qty_form()
	{
		
		let qty=$('#invdyechemisurtnitemFrm input[name=qty]').val();
		let rate=$('#invdyechemisurtnitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invdyechemisurtnitemFrm input[name=amount]').val(amount);
	}

	openorderWindow()
	{
		$('#invdyechemisurtnordersearchwindow').window('open');

	}

	orderSearchGrid(data){
		let self=this;
		$('#invdyechemisurtnordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurtnitemFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invdyechemisurtnitemFrm [name=sales_order_id]').val(row.sale_order_id);
				$('#invdyechemisurtnordersearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachOrder(){
		let order_no=$('#invdyechemisurtnordersearchFrm [name=order_no]').val();
		let style_ref=$('#invdyechemisurtnordersearchFrm [name=style_ref]').val();
		let inv_dye_chem_rcv_id=$('#invdyechemisurtnFrm [name=id]').val();
		let params={};
		params.order_no=order_no;
		params.style_ref=style_ref;
		params.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
		let d=axios.get(this.route+'/getorder',{params})
		.then(function(response){
			$('#invdyechemisurtnordersearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
	

}
window.MsInvDyeChemIsuRtnItem=new MsInvDyeChemIsuRtnItemController(new MsInvDyeChemIsuRtnItemModel());
MsInvDyeChemIsuRtnItem.itemSearchGrid([]);
MsInvDyeChemIsuRtnItem.orderSearchGrid([]);
MsInvDyeChemIsuRtnItem.showGrid([]);