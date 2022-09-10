let MsInvFinishFabTransOutItemModel = require('./MsInvFinishFabTransOutItemModel');

class MsInvFinishFabTransOutItemController {
	constructor(MsInvFinishFabTransOutItemModel)
	{
		this.MsInvFinishFabTransOutItemModel = MsInvFinishFabTransOutItemModel;
		this.formId='invfinishfabtransoutitemFrm';	             
		this.dataTable='#invfinishfabtransoutitemTbl';
		this.route=msApp.baseUrl()+"/invfinishfabtransoutitem"
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
		let inv_isu_id=$('#invfinishfabtransoutFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;
		if(formObj.id){
			this.MsInvFinishFabTransOutItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabTransOutItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	submitBatch()
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
		let inv_isu_id=$('#invfinishfabtransoutFrm [name=id]').val()
		let formObj=msApp.get('invfinishfabtransoutitemsearchFrm');
		formObj.inv_isu_id=inv_isu_id;
		if(formObj.id){
			this.MsInvFinishFabTransOutItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabTransOutItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabTransOutItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabTransOutItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let rowindex = $('#invfinishfabtransoutitemFrm [name=row_index]').val();
		MsInvFinishFabTransOutItem.resetForm();
		MsInvFinishFabTransOutItem.get(d.inv_isu_id)
		$('#invfinishfabtransoutitemwindow').window('close');
		if(rowindex){
		$('#invfinishfabtransoutitemsearchTbl').datagrid('deleteRow',rowindex);
		}
	}

	edit(index,row)
	{
		$('#invfinishfabtransoutitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabtransoutitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabtransoutitemFrm [name=style_ref]').val(row.style_ref);
		$('#invfinishfabtransoutitemFrm [name=sale_order_no]').val(row.sale_order_no);
		row.route=this.route;
		row.formId=this.formId;
		//this.MsInvFinishFabTransOutItemModel.get(index,row);
		let d=this.MsInvFinishFabTransOutItemModel.get(index,row)
		.then(function(response){
			$('#invfinishfabtransoutitemwindow').window('open');
		}).catch(function(error){
			console.log(error);
		})
		

	}
	get(inv_isu_id){
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invfinishfabtransoutitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabTransOutItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		$('#invfinishfabtransoutitemsearchTbl').datagrid('loadData',[]);
		$('#invfinishfabtransoutitemsearchwindow').window('open');
	}

	getItem()
	{
		
		let inv_isu_id=$('#invfinishfabtransoutFrm [name=id]').val();
		let buyer_id=$('#invfinishfabtransoutitemsearchFrm [name=buyer_id]').val();
		let style_ref=$('#invfinishfabtransoutitemsearchFrm [name=style_ref]').val();
		let sale_order_no=$('#invfinishfabtransoutitemsearchFrm [name=sale_order_no]').val();
		let params={};
		params.inv_isu_id=inv_isu_id;
		params.buyer_id=buyer_id;
		params.style_ref=style_ref;
		params.sale_order_no=sale_order_no;
		let d=axios.get(this.route+'/getfinishfabitem',{params})
		.then(function(response){
			$('#invfinishfabtransoutitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	itemSearchGrid(data){
		let self=this;
		$('#invfinishfabtransoutitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				/*$('#invfinishfabtransoutitemsearchFrm [name=style_ref]').val(row.style_ref);
				$('#invfinishfabtransoutitemsearchFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invfinishfabtransoutitemsearchFrm [name=inv_finish_fab_rcv_item_id]').val(row.id);
				$('#invfinishfabtransoutitemsearchFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
				$('#invfinishfabtransoutitemsearchFrm [name=store_id]').val(row.store_id);
				$('#invfinishfabtransoutitemsearchFrm [name=rcv_qty]').val(row.rcv_qty);
				$('#invfinishfabtransoutitemsearchFrm [name=isu_qty]').val(row.isu_qty);
				$('#invfinishfabtransoutitemsearchFrm [name=bal_qty]').val(row.bal_qty);
				$('#invfinishfabtransoutitemsearchFrm [name=qty]').val(row.bal_qty);
				$('#invfinishfabtransoutitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invfinishfabtransoutitemsearchFrm [name=custom_no]').val(row.custom_no);*/
			},
			onLoadSuccess: function(){
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatsv(value,row,index){
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabTransOutItem.save(event,'+row.id+','+index+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Save</span></a>   <a href="javascript:void(0)"  onClick="MsInvFinishFabTransOutItem.split(event,'+row.id+','+index+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Edit</span></a>';
	}
	
	save(event,id,index){
		MsInvFinishFabTransOutItem.resetForm();
		var row = $('#invfinishfabtransoutitemsearchTbl').datagrid('getRows')[index];
		$('#invfinishfabtransoutitemFrm [name=style_ref]').val(row.style_ref);
		$('#invfinishfabtransoutitemFrm [name=sale_order_no]').val(row.sale_order_no);
		$('#invfinishfabtransoutitemFrm [name=inv_finish_fab_rcv_item_id]').val(row.id);
		$('#invfinishfabtransoutitemFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
		$('#invfinishfabtransoutitemFrm [name=store_id]').val(row.store_id);
		$('#invfinishfabtransoutitemFrm [name=rcv_qty]').val(row.rcv_qty);
		$('#invfinishfabtransoutitemFrm [name=isu_qty]').val(row.isu_qty);
		$('#invfinishfabtransoutitemFrm [name=bal_qty]').val(row.bal_qty);
		$('#invfinishfabtransoutitemFrm [name=qty]').val(row.bal_qty);
		$('#invfinishfabtransoutitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabtransoutitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabtransoutitemFrm [name=row_index]').val(index);
		MsInvFinishFabTransOutItem.submit();

	}
	split(event,id,index) {
		MsInvFinishFabTransOutItem.resetForm();
		var row = $('#invfinishfabtransoutitemsearchTbl').datagrid('getRows')[index];
		$('#invfinishfabtransoutitemFrm [name=style_ref]').val(row.style_ref);
		$('#invfinishfabtransoutitemFrm [name=sale_order_no]').val(row.sale_order_no);
		$('#invfinishfabtransoutitemFrm [name=inv_finish_fab_rcv_item_id]').val(row.id);
		$('#invfinishfabtransoutitemFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
		$('#invfinishfabtransoutitemFrm [name=store_id]').val(row.store_id);
		$('#invfinishfabtransoutitemFrm [name=rcv_qty]').val(row.rcv_qty);
		$('#invfinishfabtransoutitemFrm [name=isu_qty]').val(row.isu_qty);
		$('#invfinishfabtransoutitemFrm [name=bal_qty]').val(row.bal_qty);
		$('#invfinishfabtransoutitemFrm [name=qty]').val(row.bal_qty);
		$('#invfinishfabtransoutitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabtransoutitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabtransoutitemFrm [name=row_index]').val(index);
		$('#invfinishfabtransoutitemwindow').window('open');
	}

	openStyleWindow(){
		$('#styleWindow').window('open');
	}
	getStyleParams(){
		let params={};
		params.buyer_id = $('#stylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#stylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#stylesearchFrm  [name=style_description]').val();
		return params;
	}
	searchStyleGrid(){
		let params=this.getStyleParams();
		let d= axios.get(this.route+'/getstyle',{params})
		.then(function(response){
			$('#stylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showStyleGrid(data){
		let self=this;
		$('#stylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#invfinishfabtransoutitemsearchFrm [name=style_ref]').val(row.style_ref);
				$('#invfinishfabtransoutitemsearchFrm [name=style_id]').val(row.id);
				$('#styleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	openOrderWindow(){
		$('#salesorderWindow').window('open');
	}
	getOrderParams(){
		let params={};
		params.sale_order_no = $('#salesordersearchFrm  [name=sale_order_no]').val();
		params.style_ref = $('#salesordersearchFrm  [name=style_ref]').val();
		params.job_no = $('#salesordersearchFrm  [name=job_no]').val();
		return params;
	}
	searchOrderGrid(){
		let params=this.getOrderParams();
		let d= axios.get(this.route+'/getorder',{params})
		.then(function(response){
			$('#ordersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showOrderGrid(data){
		let self=this;
		$('#ordersearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#invfinishfabtransoutitemsearchFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invfinishfabtransoutitemsearchFrm [name=sales_order_id]').val(row.id);
				$('#salesorderWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
}
window.MsInvFinishFabTransOutItem=new MsInvFinishFabTransOutItemController(new MsInvFinishFabTransOutItemModel());
MsInvFinishFabTransOutItem.showGrid([]);
MsInvFinishFabTransOutItem.itemSearchGrid([]);
MsInvFinishFabTransOutItem.showStyleGrid([]);
MsInvFinishFabTransOutItem.showOrderGrid([]);