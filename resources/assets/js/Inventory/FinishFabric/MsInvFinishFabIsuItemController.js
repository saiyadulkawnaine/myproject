let MsInvFinishFabIsuItemModel = require('./MsInvFinishFabIsuItemModel');

class MsInvFinishFabIsuItemController {
	constructor(MsInvFinishFabIsuItemModel)
	{
		this.MsInvFinishFabIsuItemModel = MsInvFinishFabIsuItemModel;
		this.formId='invfinishfabisuitemFrm';	             
		this.dataTable='#invfinishfabisuitemTbl';
		this.route=msApp.baseUrl()+"/invfinishfabisuitem"
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
		let inv_isu_id=$('#invfinishfabisuFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;
		if(formObj.id){
			this.MsInvFinishFabIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_isu_id=$('#invfinishfabisuFrm [name=id]').val()
		let formObj=msApp.get('invfinishfabisuitemeditFrm');
		formObj.inv_isu_id=inv_isu_id;
		if(formObj.id){
			this.MsInvFinishFabIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabIsuItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabIsuItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let rowindex = $('#invfinishfabisuitemFrm [name=row_index]').val();

		MsInvFinishFabIsuItem.resetForm();
		MsInvFinishFabIsuItem.get(d.inv_isu_id)
		$('#invfinishfabisuitemsearchFrmTotal').html(d.total);
		if(rowindex){
			//alert(rowindex)
			$('#invfinishfabisuitemsearchTbl').datagrid('deleteRow',rowindex*1);
		}
		$('#invfinishfabisuitemwindow').window('close');
	}

	edit(index,row)
	{
		$('#invfinishfabisuitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabisuitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabisuitemFrm [name=style_ref]').val(row.style_ref);
		$('#invfinishfabisuitemFrm [name=sale_order_no]').val(row.sale_order_no);
		row.route=this.route;
		row.formId=this.formId;
		let d=this.MsInvFinishFabIsuItemModel.get(index,row)
		.then(function(response){
			$('#invfinishfabisuitemwindow').window('open');
		}).catch(function(error){
			console.log(error);
		})
		

	}
	get(inv_isu_id){
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invfinishfabisuitemTbl').datagrid('loadData',response.data);
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
			showFooter:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var rcv_qty=0;
				for(var i=0; i<data.rows.length; i++){
				rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				}

				$(this).datagrid('reloadFooter', [
				{ 
					rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			},

			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabIsuItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		MsInvFinishFabIsuItem.resetForm();
		$('#invfinishfabisuitemsearchTbl').datagrid('loadData',[]);
		$('#invfinishfabisuitemsearchFrmTotal').html(0);
		$('#invfinishfabisuitemsearchwindow').window('open');
	}

	getItem()
	{
		
		let inv_isu_id=$('#invfinishfabisuFrm [name=id]').val();
		let receive_against_id=$('#invfinishfabisuitemsearchFrm [name=receive_against_id]').val();
		let buyer_id=$('#invfinishfabisuitemsearchFrm [name=buyer_id]').val();
		let style_ref=$('#invfinishfabisuitemsearchFrm [name=style_ref]').val();
		let sale_order_no=$('#invfinishfabisuitemsearchFrm [name=sale_order_no]').val();
		let params={};
		params.inv_isu_id=inv_isu_id;
		params.receive_against_id=receive_against_id;
		params.buyer_id=buyer_id;
		params.style_ref=style_ref;
		params.sale_order_no=sale_order_no;
		if (!params.receive_against_id) {
			alert('Select Roll Type First');
			return;
		}
		if (!params.buyer_id) {
			alert('Select Buyer First');
			return;
		}
		let d=axios.get(this.route+'/getfinishfabitem',{params})
		.then(function(response){
			$('#invfinishfabisuitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	itemSearchGrid(data){
		let self=this;
		$('#invfinishfabisuitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			showFooter:true,
			idField:'id',
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var rcv_qty=0;
				var isu_qty=0;
				var bal_qty=0;
				for(var i=0; i<data.rows.length; i++){
					rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					isu_qty+=data.rows[i]['isu_qty'].replace(/,/g,'')*1;
					bal_qty+=data.rows[i]['bal_qty'].replace(/,/g,'')*1;
				}

				$(this).datagrid('reloadFooter', [
				{ 
					rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					isu_qty: isu_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bal_qty: bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			},
			onClickRow: function(index,row){
				/*$('#invfinishfabisuitemsearchFrm [name=style_ref]').val(row.style_ref);
				$('#invfinishfabisuitemsearchFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invfinishfabisuitemsearchFrm [name=inv_finish_fab_rcv_item_id]').val(row.id);
				$('#invfinishfabisuitemsearchFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
				$('#invfinishfabisuitemsearchFrm [name=store_id]').val(row.store_id);
				$('#invfinishfabisuitemsearchFrm [name=rcv_qty]').val(row.rcv_qty);
				$('#invfinishfabisuitemsearchFrm [name=isu_qty]').val(row.isu_qty);
				$('#invfinishfabisuitemsearchFrm [name=bal_qty]').val(row.bal_qty);
				$('#invfinishfabisuitemsearchFrm [name=qty]').val(row.bal_qty);
				$('#invfinishfabisuitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invfinishfabisuitemsearchFrm [name=custom_no]').val(row.custom_no);*/
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatsv(value,row,index){
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabIsuItem.save(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Save</span></a>   <a href="javascript:void(0)"  onClick="MsInvFinishFabIsuItem.split(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Edit</span></a>';
	}
	
	save(event,id){
		let index=$('#invfinishfabisuitemsearchTbl').datagrid('getRowIndex',id);
		MsInvFinishFabIsuItem.resetForm();
		var row = $('#invfinishfabisuitemsearchTbl').datagrid('getRows')[index];
		$('#invfinishfabisuitemFrm [name=style_ref]').val(row.style_ref);
		$('#invfinishfabisuitemFrm [name=sale_order_no]').val(row.sale_order_no);
		$('#invfinishfabisuitemFrm [name=inv_finish_fab_rcv_item_id]').val(row.id);
		$('#invfinishfabisuitemFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
		$('#invfinishfabisuitemFrm [name=store_id]').val(row.store_id);
		$('#invfinishfabisuitemFrm [name=rcv_qty]').val(row.rcv_qty);
		$('#invfinishfabisuitemFrm [name=isu_qty]').val(row.isu_qty);
		$('#invfinishfabisuitemFrm [name=bal_qty]').val(row.bal_qty);
		$('#invfinishfabisuitemFrm [name=qty]').val(row.bal_qty);
		$('#invfinishfabisuitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabisuitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabisuitemFrm [name=row_index]').val(index);
		MsInvFinishFabIsuItem.submit();

	}
	split(event,id) {
		let index=$('#invfinishfabisuitemsearchTbl').datagrid('getRowIndex',id);
		MsInvFinishFabIsuItem.resetForm();
		var row = $('#invfinishfabisuitemsearchTbl').datagrid('getRows')[index];
		$('#invfinishfabisuitemFrm [name=style_ref]').val(row.style_ref);
		$('#invfinishfabisuitemFrm [name=sale_order_no]').val(row.sale_order_no);
		$('#invfinishfabisuitemFrm [name=inv_finish_fab_rcv_item_id]').val(row.id);
		$('#invfinishfabisuitemFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
		$('#invfinishfabisuitemFrm [name=store_id]').val(row.store_id);
		$('#invfinishfabisuitemFrm [name=rcv_qty]').val(row.rcv_qty);
		$('#invfinishfabisuitemFrm [name=isu_qty]').val(row.isu_qty);
		$('#invfinishfabisuitemFrm [name=bal_qty]').val(row.bal_qty);
		$('#invfinishfabisuitemFrm [name=qty]').val(row.bal_qty);
		$('#invfinishfabisuitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabisuitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabisuitemFrm [name=row_index]').val(index);
		$('#invfinishfabisuitemwindow').window('open');
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
				$('#invfinishfabisuitemsearchFrm [name=style_ref]').val(row.style_ref);
				$('#invfinishfabisuitemsearchFrm [name=style_id]').val(row.id);
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
				$('#invfinishfabisuitemsearchFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invfinishfabisuitemsearchFrm [name=sales_order_id]').val(row.id);
				$('#salesorderWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
}
window.MsInvFinishFabIsuItem=new MsInvFinishFabIsuItemController(new MsInvFinishFabIsuItemModel());
MsInvFinishFabIsuItem.showGrid([]);
MsInvFinishFabIsuItem.itemSearchGrid([]);
MsInvFinishFabIsuItem.showStyleGrid([]);
MsInvFinishFabIsuItem.showOrderGrid([]);