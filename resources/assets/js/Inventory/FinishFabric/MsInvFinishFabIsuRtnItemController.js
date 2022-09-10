let MsInvFinishFabIsuRtnItemModel = require('./MsInvFinishFabIsuRtnItemModel');

class MsInvFinishFabIsuRtnItemController {
	constructor(MsInvFinishFabIsuRtnItemModel)
	{
		this.MsInvFinishFabIsuRtnItemModel = MsInvFinishFabIsuRtnItemModel;
		this.formId='invfinishfabisurtnitemFrm';	             
		this.dataTable='#invfinishfabisurtnitemTbl';
		this.route=msApp.baseUrl()+"/invfinishfabisurtnitem"
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
		let inv_rcv_id=$('#invfinishfabisurtnFrm [name=id]').val()
		let inv_finish_fab_rcv_id=$('#invfinishfabisurtnFrm [name=inv_finish_fab_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvFinishFabIsuRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabIsuRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invfinishfabisurtnFrm [name=id]').val()
		let inv_finish_fab_rcv_id=$('#invfinishfabisurtnFrm [name=inv_finish_fab_rcv_id]').val();
		let formObj=msApp.get('invfinishfabisurtnitemsearchFrm');
		formObj.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvFinishFabIsuRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabIsuRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invfinishfabisurtnitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invfinishfabisurtnitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabIsuRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabIsuRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let rowindex = $('#invfinishfabisurtnitemFrm [name=row_index]').val();
		MsInvFinishFabIsuRtnItem.resetForm();
		MsInvFinishFabIsuRtnItem.get(d.inv_finish_fab_rcv_id)
		$('#invfinishfabisurtnitemwindow').window('close');
		if(rowindex){
		$('#invfinishfabisurtnitemsearchTbl').datagrid('deleteRow',rowindex);
		}
	}

	edit(index,row)
	{
		$('#invfinishfabisurtnitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabisurtnitemFrm [name=custom_no]').val(row.custom_no);
		row.route=this.route;
		row.formId=this.formId;
		//this.MsInvFinishFabIsuRtnItemModel.get(index,row);
		let d=this.MsInvFinishFabIsuRtnItemModel.get(index,row)
		.then(function(response){
			$('#invfinishfabisurtnitemwindow').window('open');
		}).catch(function(error){
			console.log(error);
		})
		

	}
	get(inv_finish_fab_rcv_id){
		let params={};
		params.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invfinishfabisurtnitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabIsuRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		$('#invfinishfabisurtnitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invfinishfabisurtnitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				/*$('#invfinishfabisurtnitemsearchFrm [name=inv_finish_fab_isu_item_id]').val(row.id);
				$('#invfinishfabisurtnitemsearchFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
				$('#invfinishfabisurtnitemsearchFrm [name=prod_finish_dlv_roll_id]').val(row.prod_finish_dlv_roll_id);
				$('#invfinishfabisurtnitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invfinishfabisurtnitemsearchFrm [name=custom_no]').val(row.custom_no);
				$('#invfinishfabisurtnitemsearchFrm [name=qty]').val(row.rcv_qty);*/
				//$('#invfinishfabisurtnitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let challan_no=$('#invfinishfabisurtnitemsearchFrm [name=challan_no]').val();
		let inv_rcv_id=$('#invfinishfabisurtnFrm [name=id]').val();
		let params={};
		params.challan_no=challan_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invfinishfabisurtnitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}



	
	formatsv(value,row,index){
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabIsuRtnItem.split(event,'+row.id+','+index+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Receive</span></a>';
	}

	save(event,id,index){
		MsInvFinishFabIsuRtnItem.resetForm();
		var row = $('#invfinishfabisurtnitemsearchTbl').datagrid('getRows')[index];
		$('#invfinishfabisurtnitemFrm [name=inv_finish_fab_isu_item_id]').val(row.id);
		$('#invfinishfabisurtnitemFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
		$('#invfinishfabisurtnitemFrm [name=prod_finish_dlv_roll_id]').val(row.prod_finish_dlv_roll_id);
		$('#invfinishfabisurtnitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabisurtnitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabisurtnitemFrm [name=qty]').val(row.rcv_qty);
		$('#invfinishfabisurtnitemFrm [name=row_index]').val(index);
		MsInvFinishFabIsuRtnItem.submit();

	}
	split(event,id,index) {
		MsInvFinishFabIsuRtnItem.resetForm();
		var row = $('#invfinishfabisurtnitemsearchTbl').datagrid('getRows')[index];
		$('#invfinishfabisurtnitemFrm [name=inv_finish_fab_isu_item_id]').val(row.id);
		$('#invfinishfabisurtnitemFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
		$('#invfinishfabisurtnitemFrm [name=prod_finish_dlv_roll_id]').val(row.prod_finish_dlv_roll_id);
		$('#invfinishfabisurtnitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabisurtnitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabisurtnitemFrm [name=qty]').val(row.rcv_qty);
		$('#invfinishfabisurtnitemFrm [name=row_index]').val(index);
		$('#invfinishfabisurtnitemwindow').window('open');
	}
}
window.MsInvFinishFabIsuRtnItem=new MsInvFinishFabIsuRtnItemController(new MsInvFinishFabIsuRtnItemModel());
MsInvFinishFabIsuRtnItem.itemSearchGrid([]);
MsInvFinishFabIsuRtnItem.showGrid([]);