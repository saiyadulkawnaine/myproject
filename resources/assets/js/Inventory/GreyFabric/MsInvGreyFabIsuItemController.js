let MsInvGreyFabIsuItemModel = require('./MsInvGreyFabIsuItemModel');

class MsInvGreyFabIsuItemController {
	constructor(MsInvGreyFabIsuItemModel)
	{
		this.MsInvGreyFabIsuItemModel = MsInvGreyFabIsuItemModel;
		this.formId='invgreyfabisuitemFrm';	             
		this.dataTable='#invgreyfabisuitemTbl';
		this.route=msApp.baseUrl()+"/invgreyfabisuitem"
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
		let inv_isu_id=$('#invgreyfabisuFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_isu_id=inv_isu_id;
		if(formObj.id){
			this.MsInvGreyFabIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_isu_id=$('#invgreyfabisuFrm [name=id]').val()
		let formObj=msApp.get('invgreyfabisuitemeditFrm');
		formObj.inv_isu_id=inv_isu_id;
		if(formObj.id){
			this.MsInvGreyFabIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabIsuItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabIsuItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let rowindex = $('#invgreyfabisuitemFrm [name=row_index]').val();

		MsInvGreyFabIsuItem.resetForm();
		MsInvGreyFabIsuItem.get(d.inv_isu_id)
		$('#invgreyfabisuitemsearchFrmTotal').html(d.total);
		if(rowindex){
			//alert(rowindex)
			$('#invgreyfabisuitemsearchTbl').datagrid('deleteRow',rowindex*1);
		}
		$('#invgreyfabisuitemwindow').window('close');
	}

	edit(index,row)
	{
		$('#invgreyfabisuitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabisuitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabisuitemFrm [name=style_ref]').val(row.style_ref);
		$('#invgreyfabisuitemFrm [name=sale_order_no]').val(row.sale_order_no);
		row.route=this.route;
		row.formId=this.formId;
		let d=this.MsInvGreyFabIsuItemModel.get(index,row)
		.then(function(response){
			$('#invgreyfabisuitemwindow').window('open');
		}).catch(function(error){
			console.log(error);
		})
		

	}
	get(inv_isu_id){
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgreyfabisuitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabIsuItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		MsInvGreyFabIsuItem.resetForm();
		$('#invgreyfabisuitemsearchTbl').datagrid('loadData',[]);
		$('#invgreyfabisuitemsearchFrmTotal').html(0);
		$('#invgreyfabisuitemsearchwindow').window('open');
	}

	getItem()
	{
		
		let inv_isu_id=$('#invgreyfabisuFrm [name=id]').val();
		let buyer_id=$('#invgreyfabisuitemsearchFrm [name=buyer_id]').val();
		let style_ref=$('#invgreyfabisuitemsearchFrm [name=style_ref]').val();
		let sale_order_no=$('#invgreyfabisuitemsearchFrm [name=sale_order_no]').val();
		let params={};
		params.inv_isu_id=inv_isu_id;
		params.buyer_id=buyer_id;
		params.style_ref=style_ref;
		params.sale_order_no=sale_order_no;
		let d=axios.get(this.route+'/getgreyfabitem',{params})
		.then(function(response){
			$('#invgreyfabisuitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	itemSearchGrid(data){
		let self=this;
		$('#invgreyfabisuitemsearchTbl').datagrid({
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
				/*$('#invgreyfabisuitemsearchFrm [name=style_ref]').val(row.style_ref);
				$('#invgreyfabisuitemsearchFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invgreyfabisuitemsearchFrm [name=inv_grey_fab_rcv_item_id]').val(row.id);
				$('#invgreyfabisuitemsearchFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
				$('#invgreyfabisuitemsearchFrm [name=store_id]').val(row.store_id);
				$('#invgreyfabisuitemsearchFrm [name=rcv_qty]').val(row.rcv_qty);
				$('#invgreyfabisuitemsearchFrm [name=isu_qty]').val(row.isu_qty);
				$('#invgreyfabisuitemsearchFrm [name=bal_qty]').val(row.bal_qty);
				$('#invgreyfabisuitemsearchFrm [name=qty]').val(row.bal_qty);
				$('#invgreyfabisuitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invgreyfabisuitemsearchFrm [name=custom_no]').val(row.custom_no);*/
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatsv(value,row,index){
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabIsuItem.save(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Save</span></a>   <a href="javascript:void(0)"  onClick="MsInvGreyFabIsuItem.split(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Edit</span></a>';
	}
	
	save(event,id){
		let index=$('#invgreyfabisuitemsearchTbl').datagrid('getRowIndex',id);
		MsInvGreyFabIsuItem.resetForm();
		var row = $('#invgreyfabisuitemsearchTbl').datagrid('getRows')[index];
		$('#invgreyfabisuitemFrm [name=style_ref]').val(row.style_ref);
		$('#invgreyfabisuitemFrm [name=sale_order_no]').val(row.sale_order_no);
		$('#invgreyfabisuitemFrm [name=inv_grey_fab_rcv_item_id]').val(row.id);
		$('#invgreyfabisuitemFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
		$('#invgreyfabisuitemFrm [name=store_id]').val(row.store_id);
		$('#invgreyfabisuitemFrm [name=rcv_qty]').val(row.rcv_qty);
		$('#invgreyfabisuitemFrm [name=isu_qty]').val(row.isu_qty);
		$('#invgreyfabisuitemFrm [name=bal_qty]').val(row.bal_qty);
		$('#invgreyfabisuitemFrm [name=qty]').val(row.bal_qty);
		$('#invgreyfabisuitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabisuitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabisuitemFrm [name=row_index]').val(index);
		MsInvGreyFabIsuItem.submit();

	}
	split(event,id) {
		let index=$('#invgreyfabisuitemsearchTbl').datagrid('getRowIndex',id);
		MsInvGreyFabIsuItem.resetForm();
		var row = $('#invgreyfabisuitemsearchTbl').datagrid('getRows')[index];
		$('#invgreyfabisuitemFrm [name=style_ref]').val(row.style_ref);
		$('#invgreyfabisuitemFrm [name=sale_order_no]').val(row.sale_order_no);
		$('#invgreyfabisuitemFrm [name=inv_grey_fab_rcv_item_id]').val(row.id);
		$('#invgreyfabisuitemFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
		$('#invgreyfabisuitemFrm [name=store_id]').val(row.store_id);
		$('#invgreyfabisuitemFrm [name=rcv_qty]').val(row.rcv_qty);
		$('#invgreyfabisuitemFrm [name=isu_qty]').val(row.isu_qty);
		$('#invgreyfabisuitemFrm [name=bal_qty]').val(row.bal_qty);
		$('#invgreyfabisuitemFrm [name=qty]').val(row.bal_qty);
		$('#invgreyfabisuitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabisuitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabisuitemFrm [name=row_index]').val(index);
		$('#invgreyfabisuitemwindow').window('open');
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
				$('#invgreyfabisuitemsearchFrm [name=style_ref]').val(row.style_ref);
				$('#invgreyfabisuitemsearchFrm [name=style_id]').val(row.id);
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
				$('#invgreyfabisuitemsearchFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#invgreyfabisuitemsearchFrm [name=sales_order_id]').val(row.id);
				$('#salesorderWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
}
window.MsInvGreyFabIsuItem=new MsInvGreyFabIsuItemController(new MsInvGreyFabIsuItemModel());
MsInvGreyFabIsuItem.showGrid([]);
MsInvGreyFabIsuItem.itemSearchGrid([]);
MsInvGreyFabIsuItem.showStyleGrid([]);
MsInvGreyFabIsuItem.showOrderGrid([]);