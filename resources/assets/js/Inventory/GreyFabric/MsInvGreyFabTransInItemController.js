let MsInvGreyFabTransInItemModel = require('./MsInvGreyFabTransInItemModel');

class MsInvGreyFabTransInItemController {
	constructor(MsInvGreyFabTransInItemModel)
	{
		this.MsInvGreyFabTransInItemModel = MsInvGreyFabTransInItemModel;
		this.formId='invgreyfabtransinitemFrm';	             
		this.dataTable='#invgreyfabtransinitemTbl';
		this.route=msApp.baseUrl()+"/invgreyfabtransinitem"
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
		let inv_rcv_id=$('#invgreyfabtransinFrm [name=id]').val()
		let inv_grey_fab_rcv_id=$('#invgreyfabtransinFrm [name=inv_grey_fab_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_grey_fab_rcv_id=inv_grey_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvGreyFabTransInItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabTransInItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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

		let inv_rcv_id=$('#invgreyfabtransinFrm [name=id]').val()
		let inv_grey_fab_rcv_id=$('#invgreyfabtransinFrm [name=inv_grey_fab_rcv_id]').val();
		let formObj=msApp.get('invgreyfabtransinitemmultiFrm');

		formObj.inv_grey_fab_rcv_id=inv_grey_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;

		if(formObj.id){
			this.MsInvGreyFabTransInItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabTransInItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		let store_id=$('#invgreyfabtransinitemFrm [name=store_id]').val()
		msApp.resetForm(this.formId);
		$('#invgreyfabtransinitemFrm [name=store_id]').val(store_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabTransInItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabTransInItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let rowindex = $('#invgreyfabtransinitemFrm [name=row_index]').val();
		MsInvGreyFabTransInItem.resetForm();
		MsInvGreyFabTransInItem.get(d.inv_grey_fab_rcv_id)
		$('#invgreyfabtransinitemwindow').window('close');
		if(rowindex){
		$('#invgreyfabtransinitemsearchTbl').datagrid('deleteRow',rowindex);
		}
	}

	edit(index,row)
	{
		$('#invgreyfabtransinitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabtransinitemFrm [name=custom_no]').val(row.custom_no);
		row.route=this.route;
		row.formId=this.formId;
		//this.MsInvGreyFabTransInItemModel.get(index,row);
		let d=this.MsInvGreyFabTransInItemModel.get(index,row)
		.then(function(response){
			//$('#invgreyfabtransinitemwindow').window('open');
		}).catch(function(error){
			console.log(error);
		})
		

	}
	get(inv_grey_fab_rcv_id){
		let params={};
		params.inv_grey_fab_rcv_id=inv_grey_fab_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgreyfabtransinitemTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabTransInItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		$('#invgreyfabtransinitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invgreyfabtransinitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				/*$('#invgreyfabtransinitemsearchFrm [name=inv_grey_fab_isu_item_id]').val(row.id);
				$('#invgreyfabtransinitemsearchFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
				$('#invgreyfabtransinitemsearchFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
				$('#invgreyfabtransinitemsearchFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#invgreyfabtransinitemsearchFrm [name=custom_no]').val(row.custom_no);
				$('#invgreyfabtransinitemsearchFrm [name=qty]').val(row.rcv_qty);*/
				//$('#invgreyfabtransinitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let challan_no=$('#invgreyfabtransinitemsearchFrm [name=challan_no]').val();
		let inv_rcv_id=$('#invgreyfabtransinFrm [name=id]').val();
		let params={};
		params.challan_no=challan_no;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#invgreyfabtransinitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}


	getSelection(){
		let inv_grey_fab_isu_item_ids=[];
		let name=[];
		let checked=$('#invgreyfabtransinitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			inv_grey_fab_isu_item_ids.push(val.id)
		});
		inv_grey_fab_isu_item_id=inv_grey_fab_isu_item_ids.join(',');
		$('#invgreyfabtransinitemsearchTbl').datagrid('clearSelections');
		$('#invgreyfabtransinitemsearchwindow').window('close');
		return inv_grey_fab_isu_item_id;

	}

	closeinvgreyfabtransinitemsearchwindow()
	{
		let inv_rcv_id=$('#invgreyfabtransinFrm  [name=id]').val();
		let inv_grey_fab_rcv_id=$('#invgreyfabtransinFrm  [name=inv_grey_fab_rcv_id]').val();
		let inv_grey_fab_isu_item_id=this.getSelection();
		let data= axios.get(this.route+"/create"+"?inv_grey_fab_isu_item_id="+inv_grey_fab_isu_item_id+'&inv_grey_fab_rcv_id='+inv_grey_fab_rcv_id+'&inv_rcv_id='+inv_rcv_id)
		.then(function (response) {
			$('#invgreyfabtransinitemscs').html(response.data);
			$('#invgreyfabtransinitemwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	copyStore(store_id,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#invgreyfabtransinitemmultiFrm select[name="store_id['+i+']"]').val(store_id)
		}
	}

	copyRoom(room,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#invgreyfabtransinitemmultiFrm input[name="room['+i+']"]').val(room)
		}
	}
	copyRack(rack,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			$('#invgreyfabtransinitemmultiFrm input[name="rack['+i+']"]').val(rack)
		}
	}

	copyShelf(shelf,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
		$('#invgreyfabtransinitemmultiFrm input[name="shelf['+i+']"]').val(shelf)
		}
	}





	
	/*formatsv(value,row,index){
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabTransInItem.split(event,'+row.id+','+index+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Receive</span></a>';
	}

	save(event,id,index){
		MsInvGreyFabTransInItem.resetForm();
		var row = $('#invgreyfabtransinitemsearchTbl').datagrid('getRows')[index];
		$('#invgreyfabtransinitemFrm [name=inv_grey_fab_isu_item_id]').val(row.id);
		$('#invgreyfabtransinitemFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
		$('#invgreyfabtransinitemFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
		$('#invgreyfabtransinitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabtransinitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabtransinitemFrm [name=qty]').val(row.rcv_qty);
		$('#invgreyfabtransinitemFrm [name=row_index]').val(index);
		MsInvGreyFabTransInItem.submit();

	}
	split(event,id,index) {
		MsInvGreyFabTransInItem.resetForm();
		var row = $('#invgreyfabtransinitemsearchTbl').datagrid('getRows')[index];
		$('#invgreyfabtransinitemFrm [name=inv_grey_fab_isu_item_id]').val(row.id);
		$('#invgreyfabtransinitemFrm [name=inv_grey_fab_item_id]').val(row.inv_grey_fab_item_id);
		$('#invgreyfabtransinitemFrm [name=prod_knit_dlv_roll_id]').val(row.prod_knit_dlv_roll_id);
		$('#invgreyfabtransinitemFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
		$('#invgreyfabtransinitemFrm [name=custom_no]').val(row.custom_no);
		$('#invgreyfabtransinitemFrm [name=qty]').val(row.rcv_qty);
		$('#invgreyfabtransinitemFrm [name=row_index]').val(index);
		$('#invgreyfabtransinitemwindow1').window('open');
	}*/
}
window.MsInvGreyFabTransInItem=new MsInvGreyFabTransInItemController(new MsInvGreyFabTransInItemModel());
MsInvGreyFabTransInItem.itemSearchGrid([]);
MsInvGreyFabTransInItem.showGrid([]);