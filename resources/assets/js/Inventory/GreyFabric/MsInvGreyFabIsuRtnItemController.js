let MsInvGreyFabIsuRtnItemModel = require('./MsInvGreyFabIsuRtnItemModel');

class MsInvGreyFabIsuRtnItemController {
	constructor(MsInvGreyFabIsuRtnItemModel)
	{
		this.MsInvGreyFabIsuRtnItemModel = MsInvGreyFabIsuRtnItemModel;
		this.formId='invgreyfabisurtnitemFrm';	             
		this.dataTable='#invgreyfabisurtnitemTbl';
		this.route=msApp.baseUrl()+"/invgreyfabisurtnitem"
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
		let inv_rcv_id=$('#invgreyfabisurtnFrm [name=id]').val()
		let inv_grey_fab_rcv_id=$('#invgreyfabisurtnFrm [name=inv_grey_fab_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_grey_fab_rcv_id=inv_grey_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvGreyFabIsuRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabIsuRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invgreyfabisurtnFrm [name=id]').val()
		let inv_grey_fab_rcv_id=$('#invgreyfabisurtnFrm [name=inv_grey_fab_rcv_id]').val();
		let formObj=msApp.get('invgreyfabisurtnitemsearchFrm');
		formObj.inv_grey_fab_rcv_id=inv_grey_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvGreyFabIsuRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabIsuRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invgreyfabisurtnitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgreyfabisurtnitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabIsuRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabIsuRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let rowindex = $('#invgreyfabisurtnitemFrm [name=row_index]').val();
		MsInvGreyFabIsuRtnItem.resetForm();
		MsInvGreyFabIsuRtnItem.get(d.inv_grey_fab_rcv_id)
		$('#invgreyfabisurtnitemwindow').window('close');
		if(rowindex){
		$('#invgreyfabisurtnitemsearchTbl').datagrid('deleteRow',rowindex);
		}
	}

	edit(index,row)
	{
		$('#invgreyfabisurtnitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabisurtnitemFrm [name=custom_no]').val(row.custom_no);
		row.route=this.route;
		row.formId=this.formId;
		//this.MsInvGreyFabIsuRtnItemModel.get(index,row);
		let d=this.MsInvGreyFabIsuRtnItemModel.get(index,row)
		.then(function(response){
			$('#invgreyfabisurtnitemwindow').window('open');
		}).catch(function(error){
			console.log(error);
		})
		

	}
	get(inv_grey_fab_rcv_id){
		let params={};
		params.inv_grey_fab_rcv_id=inv_grey_fab_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgreyfabisurtnitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabIsuRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		$('#invgreyfabisurtnitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invgreyfabisurtnitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				/*$('#invgreyfabisurtnitemsearchFrm [name=inv_grey_fab_isu_item_id]').val(row.id);
				$('#invgreyfabisurtnitemsearchFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
				$('#invgreyfabisurtnitemsearchFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
				$('#invgreyfabisurtnitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invgreyfabisurtnitemsearchFrm [name=custom_no]').val(row.custom_no);
				$('#invgreyfabisurtnitemsearchFrm [name=qty]').val(row.rcv_qty);*/
				//$('#invgreyfabisurtnitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let challan_no=$('#invgreyfabisurtnitemsearchFrm [name=challan_no]').val();
		let inv_rcv_id=$('#invgreyfabisurtnFrm [name=id]').val();
		let params={};
		params.challan_no=challan_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgreyfabisurtnitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}



	
	formatsv(value,row,index){
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabIsuRtnItem.split(event,'+row.id+','+index+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Receive</span></a>';
	}

	save(event,id,index){
		MsInvGreyFabIsuRtnItem.resetForm();
		var row = $('#invgreyfabisurtnitemsearchTbl').datagrid('getRows')[index];
		$('#invgreyfabisurtnitemFrm [name=inv_grey_fab_isu_item_id]').val(row.id);
		$('#invgreyfabisurtnitemFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
		$('#invgreyfabisurtnitemFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
		$('#invgreyfabisurtnitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabisurtnitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabisurtnitemFrm [name=qty]').val(row.rcv_qty);
		$('#invgreyfabisurtnitemFrm [name=row_index]').val(index);
		MsInvGreyFabIsuRtnItem.submit();

	}
	split(event,id,index) {
		MsInvGreyFabIsuRtnItem.resetForm();
		var row = $('#invgreyfabisurtnitemsearchTbl').datagrid('getRows')[index];
		$('#invgreyfabisurtnitemFrm [name=inv_grey_fab_isu_item_id]').val(row.id);
		$('#invgreyfabisurtnitemFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
		$('#invgreyfabisurtnitemFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
		$('#invgreyfabisurtnitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabisurtnitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabisurtnitemFrm [name=qty]').val(row.rcv_qty);
		$('#invgreyfabisurtnitemFrm [name=row_index]').val(index);
		$('#invgreyfabisurtnitemwindow').window('open');
	}
}
window.MsInvGreyFabIsuRtnItem=new MsInvGreyFabIsuRtnItemController(new MsInvGreyFabIsuRtnItemModel());
MsInvGreyFabIsuRtnItem.itemSearchGrid([]);
MsInvGreyFabIsuRtnItem.showGrid([]);