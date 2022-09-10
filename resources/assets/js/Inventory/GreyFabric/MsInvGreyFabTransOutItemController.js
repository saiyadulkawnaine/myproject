let MsInvGreyFabTransOutItemModel = require('./MsInvGreyFabTransOutItemModel');

class MsInvGreyFabTransOutItemController {
	constructor(MsInvGreyFabTransOutItemModel)
	{
		this.MsInvGreyFabTransOutItemModel = MsInvGreyFabTransOutItemModel;
		this.formId='invgreyfabtransoutitemFrm';	             
		this.dataTable='#invgreyfabtransoutitemTbl';
		this.route=msApp.baseUrl()+"/invgreyfabtransoutitem"
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
		let inv_isu_id=$('#invgreyfabtransoutFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;
		if(formObj.id){
			this.MsInvGreyFabTransOutItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabTransOutItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_isu_id=$('#invgreyfabtransoutFrm [name=id]').val()
		let formObj=msApp.get('invgreyfabtransoutitemsearchFrm');
		formObj.inv_isu_id=inv_isu_id;
		if(formObj.id){
			this.MsInvGreyFabTransOutItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabTransOutItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabTransOutItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabTransOutItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let rowindex = $('#invgreyfabtransoutitemFrm [name=row_index]').val();
		MsInvGreyFabTransOutItem.resetForm();
		MsInvGreyFabTransOutItem.get(d.inv_isu_id)
		$('#invgreyfabtransoutitemwindow').window('close');
		if(rowindex){
		$('#invgreyfabtransoutitemsearchTbl').datagrid('deleteRow',rowindex);
		}
	}

	edit(index,row)
	{
		$('#invgreyfabtransoutitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabtransoutitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabtransoutitemFrm [name=style_ref]').val(row.style_ref);
		$('#invgreyfabtransoutitemFrm [name=sale_order_no]').val(row.sale_order_no);
		row.route=this.route;
		row.formId=this.formId;
		//this.MsInvGreyFabTransOutItemModel.get(index,row);
		let d=this.MsInvGreyFabTransOutItemModel.get(index,row)
		.then(function(response){
			$('#invgreyfabtransoutitemwindow').window('open');
		}).catch(function(error){
			console.log(error);
		})
		

	}
	get(inv_isu_id){
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgreyfabtransoutitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabTransOutItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		$('#invgreyfabtransoutitemsearchTbl').datagrid('loadData',[]);
		$('#invgreyfabtransoutitemsearchwindow').window('open');
	}

	getItem()
	{
		
		let inv_isu_id=$('#invgreyfabtransoutFrm [name=id]').val();
		let buyer_id=$('#invgreyfabtransoutitemsearchFrm [name=buyer_id]').val();
		let style_ref=$('#invgreyfabtransoutitemsearchFrm [name=style_ref]').val();
		let sale_order_no=$('#invgreyfabtransoutitemsearchFrm [name=sale_order_no]').val();
		let params={};
		params.inv_isu_id=inv_isu_id;
		params.buyer_id=buyer_id;
		params.style_ref=style_ref;
		params.sale_order_no=sale_order_no;
		let d=axios.get(this.route+'/getgreyfabitem',{params})
		.then(function(response){
			$('#invgreyfabtransoutitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	itemSearchGrid(data){
		let self=this;
		$('#invgreyfabtransoutitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				/*$('#invgreyfabtransoutitemsearchFrm [name=style_ref]').val(row.style_ref);
				$('#invgreyfabtransoutitemsearchFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invgreyfabtransoutitemsearchFrm [name=inv_grey_fab_rcv_item_id]').val(row.id);
				$('#invgreyfabtransoutitemsearchFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
				$('#invgreyfabtransoutitemsearchFrm [name=store_id]').val(row.store_id);
				$('#invgreyfabtransoutitemsearchFrm [name=rcv_qty]').val(row.rcv_qty);
				$('#invgreyfabtransoutitemsearchFrm [name=isu_qty]').val(row.isu_qty);
				$('#invgreyfabtransoutitemsearchFrm [name=bal_qty]').val(row.bal_qty);
				$('#invgreyfabtransoutitemsearchFrm [name=qty]').val(row.bal_qty);
				$('#invgreyfabtransoutitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invgreyfabtransoutitemsearchFrm [name=custom_no]').val(row.custom_no);*/
			},
			onLoadSuccess: function(){
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatsv(value,row,index){
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabTransOutItem.save(event,'+row.id+','+index+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Save</span></a>   <a href="javascript:void(0)"  onClick="MsInvGreyFabTransOutItem.split(event,'+row.id+','+index+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Edit</span></a>';
	}
	
	save(event,id,index){
		MsInvGreyFabTransOutItem.resetForm();
		var row = $('#invgreyfabtransoutitemsearchTbl').datagrid('getRows')[index];
		$('#invgreyfabtransoutitemFrm [name=style_ref]').val(row.style_ref);
		$('#invgreyfabtransoutitemFrm [name=sale_order_no]').val(row.sale_order_no);
		$('#invgreyfabtransoutitemFrm [name=inv_grey_fab_rcv_item_id]').val(row.id);
		$('#invgreyfabtransoutitemFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
		$('#invgreyfabtransoutitemFrm [name=store_id]').val(row.store_id);
		$('#invgreyfabtransoutitemFrm [name=rcv_qty]').val(row.rcv_qty);
		$('#invgreyfabtransoutitemFrm [name=isu_qty]').val(row.isu_qty);
		$('#invgreyfabtransoutitemFrm [name=bal_qty]').val(row.bal_qty);
		$('#invgreyfabtransoutitemFrm [name=qty]').val(row.bal_qty);
		$('#invgreyfabtransoutitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabtransoutitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabtransoutitemFrm [name=row_index]').val(index);
		MsInvGreyFabTransOutItem.submit();

	}
	split(event,id,index) {
		MsInvGreyFabTransOutItem.resetForm();
		var row = $('#invgreyfabtransoutitemsearchTbl').datagrid('getRows')[index];
		$('#invgreyfabtransoutitemFrm [name=style_ref]').val(row.style_ref);
		$('#invgreyfabtransoutitemFrm [name=sale_order_no]').val(row.sale_order_no);
		$('#invgreyfabtransoutitemFrm [name=inv_grey_fab_rcv_item_id]').val(row.id);
		$('#invgreyfabtransoutitemFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
		$('#invgreyfabtransoutitemFrm [name=store_id]').val(row.store_id);
		$('#invgreyfabtransoutitemFrm [name=rcv_qty]').val(row.rcv_qty);
		$('#invgreyfabtransoutitemFrm [name=isu_qty]').val(row.isu_qty);
		$('#invgreyfabtransoutitemFrm [name=bal_qty]').val(row.bal_qty);
		$('#invgreyfabtransoutitemFrm [name=qty]').val(row.bal_qty);
		$('#invgreyfabtransoutitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabtransoutitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabtransoutitemFrm [name=row_index]').val(index);
		$('#invgreyfabtransoutitemwindow').window('open');
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
				$('#invgreyfabtransoutitemsearchFrm [name=style_ref]').val(row.style_ref);
				$('#invgreyfabtransoutitemsearchFrm [name=style_id]').val(row.id);
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
				$('#invgreyfabtransoutitemsearchFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invgreyfabtransoutitemsearchFrm [name=sales_order_id]').val(row.id);
				$('#salesorderWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
}
window.MsInvGreyFabTransOutItem=new MsInvGreyFabTransOutItemController(new MsInvGreyFabTransOutItemModel());
MsInvGreyFabTransOutItem.showGrid([]);
MsInvGreyFabTransOutItem.itemSearchGrid([]);
MsInvGreyFabTransOutItem.showStyleGrid([]);
MsInvGreyFabTransOutItem.showOrderGrid([]);