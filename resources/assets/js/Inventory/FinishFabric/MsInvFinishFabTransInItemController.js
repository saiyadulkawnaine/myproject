let MsInvFinishFabTransInItemModel = require('./MsInvFinishFabTransInItemModel');

class MsInvFinishFabTransInItemController {
	constructor(MsInvFinishFabTransInItemModel)
	{
		this.MsInvFinishFabTransInItemModel = MsInvFinishFabTransInItemModel;
		this.formId='invfinishfabtransinitemFrm';	             
		this.dataTable='#invfinishfabtransinitemTbl';
		this.route=msApp.baseUrl()+"/invfinishfabtransinitem"
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
		let inv_rcv_id=$('#invfinishfabtransinFrm [name=id]').val()
		let inv_finish_fab_rcv_id=$('#invfinishfabtransinFrm [name=inv_finish_fab_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvFinishFabTransInItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabTransInItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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

		let inv_rcv_id=$('#invfinishfabtransinFrm [name=id]').val()
		let inv_finish_fab_rcv_id=$('#invfinishfabtransinFrm [name=inv_finish_fab_rcv_id]').val();
		let formObj=msApp.get('invfinishfabtransinitemmultiFrm');

		formObj.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvFinishFabTransInItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabTransInItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invfinishfabtransinitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invfinishfabtransinitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabTransInItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabTransInItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let rowindex = $('#invfinishfabtransinitemFrm [name=row_index]').val();
		MsInvFinishFabTransInItem.resetForm();
		MsInvFinishFabTransInItem.get(d.inv_finish_fab_rcv_id)
		$('#invfinishfabtransinitemwindow').window('close');
		if(rowindex){
		$('#invfinishfabtransinitemsearchTbl').datagrid('deleteRow',rowindex);
		}
	}

	edit(index,row)
	{
		$('#invfinishfabtransinitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabtransinitemFrm [name=custom_no]').val(row.custom_no);
		row.route=this.route;
		row.formId=this.formId;
		//this.MsInvFinishFabTransInItemModel.get(index,row);
		let d=this.MsInvFinishFabTransInItemModel.get(index,row)
		.then(function(response){
			//$('#invfinishfabtransinitemwindow').window('open');
		}).catch(function(error){
			console.log(error);
		})
		

	}
	get(inv_finish_fab_rcv_id){
		let params={};
		params.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invfinishfabtransinitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabTransInItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		$('#invfinishfabtransinitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invfinishfabtransinitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				/*$('#invfinishfabtransinitemsearchFrm [name=inv_finish_fab_isu_item_id]').val(row.id);
				$('#invfinishfabtransinitemsearchFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
				$('#invfinishfabtransinitemsearchFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
				$('#invfinishfabtransinitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invfinishfabtransinitemsearchFrm [name=custom_no]').val(row.custom_no);
				$('#invfinishfabtransinitemsearchFrm [name=qty]').val(row.rcv_qty);*/
				//$('#invfinishfabtransinitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let challan_no=$('#invfinishfabtransinitemsearchFrm [name=challan_no]').val();
		let inv_rcv_id=$('#invfinishfabtransinFrm [name=id]').val();
		let params={};
		params.challan_no=challan_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invfinishfabtransinitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}


	getSelection(){
		let inv_finish_fab_isu_item_ids=[];
		let name=[];
		let checked=$('#invfinishfabtransinitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			inv_finish_fab_isu_item_ids.push(val.id)
		});
		inv_finish_fab_isu_item_id=inv_finish_fab_isu_item_ids.join(',');
		$('#invfinishfabtransinitemsearchTbl').datagrid('clearSelections');
		$('#invfinishfabtransinitemsearchwindow').window('close');
		return inv_finish_fab_isu_item_id;

	}

	closeinvfinishfabtransinitemsearchwindow()
	{
		let inv_rcv_id=$('#invfinishfabtransinFrm  [name=id]').val();
		let inv_finish_fab_rcv_id=$('#invfinishfabtransinFrm  [name=inv_finish_fab_rcv_id]').val();
		let inv_finish_fab_isu_item_id=this.getSelection();
		let data= axios.get(this.route+"/create"+"?inv_finish_fab_isu_item_id="+inv_finish_fab_isu_item_id+'&inv_finish_fab_rcv_id='+inv_finish_fab_rcv_id+'&inv_rcv_id='+inv_rcv_id)
		.then(function (response) {
			$('#invfinishfabtransinitemscs').html(response.data);
			$('#invfinishfabtransinitemwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	copyStore(store_id,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#invfinishfabtransinitemmultiFrm select[name="store_id['+i+']"]').val(store_id)
		}
	}

	copyRoom(room,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#invfinishfabtransinitemmultiFrm input[name="room['+i+']"]').val(room)
		}
	}
	copyRack(rack,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#invfinishfabtransinitemmultiFrm input[name="rack['+i+']"]').val(rack)
		}
	}

	copyShelf(shelf,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
		$('#invfinishfabtransinitemmultiFrm input[name="shelf['+i+']"]').val(shelf)
		}
	}





	
	/*formatsv(value,row,index){
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabTransInItem.split(event,'+row.id+','+index+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Receive</span></a>';
	}

	save(event,id,index){
		MsInvFinishFabTransInItem.resetForm();
		var row = $('#invfinishfabtransinitemsearchTbl').datagrid('getRows')[index];
		$('#invfinishfabtransinitemFrm [name=inv_finish_fab_isu_item_id]').val(row.id);
		$('#invfinishfabtransinitemFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
		$('#invfinishfabtransinitemFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
		$('#invfinishfabtransinitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabtransinitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabtransinitemFrm [name=qty]').val(row.rcv_qty);
		$('#invfinishfabtransinitemFrm [name=row_index]').val(index);
		MsInvFinishFabTransInItem.submit();

	}
	split(event,id,index) {
		MsInvFinishFabTransInItem.resetForm();
		var row = $('#invfinishfabtransinitemsearchTbl').datagrid('getRows')[index];
		$('#invfinishfabtransinitemFrm [name=inv_finish_fab_isu_item_id]').val(row.id);
		$('#invfinishfabtransinitemFrm [name=inv_finish_fab_item_id]').val(row.inv_finish_fab_item_id);
		$('#invfinishfabtransinitemFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
		$('#invfinishfabtransinitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invfinishfabtransinitemFrm [name=custom_no]').val(row.custom_no);
		$('#invfinishfabtransinitemFrm [name=qty]').val(row.rcv_qty);
		$('#invfinishfabtransinitemFrm [name=row_index]').val(index);
		$('#invfinishfabtransinitemwindow1').window('open');
	}*/
}
window.MsInvFinishFabTransInItem=new MsInvFinishFabTransInItemController(new MsInvFinishFabTransInItemModel());
MsInvFinishFabTransInItem.itemSearchGrid([]);
MsInvFinishFabTransInItem.showGrid([]);