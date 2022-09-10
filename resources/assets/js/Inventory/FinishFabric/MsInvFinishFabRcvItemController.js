let MsInvFinishFabRcvItemModel = require('./MsInvFinishFabRcvItemModel');

class MsInvFinishFabRcvItemController {
	constructor(MsInvFinishFabRcvItemModel)
	{
		this.MsInvFinishFabRcvItemModel = MsInvFinishFabRcvItemModel;
		this.formId='invfinishfabrcvitemFrm';	             
		this.dataTable='#invfinishfabrcvitemTbl';
		this.route=msApp.baseUrl()+"/invfinishfabrcvitem"
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
		let inv_rcv_id=$('#invfinishfabrcvFrm [name=id]').val()
		let inv_finish_fab_rcv_id=$('#invfinishfabrcvFrm [name=inv_finish_fab_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;
		if(formObj.id){
			this.MsInvFinishFabRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invfinishfabrcvFrm [name=id]').val()
		let inv_finish_fab_rcv_id=$('#invfinishfabrcvFrm [name=inv_finish_fab_rcv_id]').val()
		let formObj=msApp.get('invfinishfabrcvitemmatrixFrm');

		formObj.inv_rcv_id=inv_rcv_id;
		formObj.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		if(formObj.id){
			this.MsInvFinishFabRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsInvFinishFabRcvItem.resetForm();
		MsInvFinishFabRcvItem.get(d.inv_finish_fab_rcv_id)
		$('#invfinishfabrcvitemWindow').window('close');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvFinishFabRcvItemModel.get(index,row);

	}
	get(inv_finish_fab_rcv_id){
		let params={};
		params.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invfinishfabrcvitemTbl').datagrid('loadData',response.data);
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
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var Qty=0;
				for(var i=0; i<data.rows.length; i++){
					Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				}
				$('#invfinishfabrcvitemTbl').datagrid('reloadFooter', [
				{ 
					qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		MsInvFinishFabRcvItem.itemSearchGrid([]);
		let inv_finish_fab_rcv_id=$('#invfinishfabrcvFrm [name=inv_finish_fab_rcv_id]').val();
		let params={};
		params.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;

		let d=axios.get(this.route+'/getfinishfabitem',{params})
		.then(function(response){
			$('#invfinishfabrcvitemsearchTbl').datagrid('loadData',response.data);
			$('#invfinishfabrcvitemsearchwindow').window('open');
		}).catch(function(error){
			console.log(error);
		});
	}

	itemSearchGrid(data){
		let self=this;
		$('#invfinishfabrcvitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
			},
			onLoadSuccess: function(){
			$(this).datagrid('selectAll');
			},
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	

	closeinvfinishfabrcvitemsearchwindow()
	{
		let inv_rcv_id=$('#invfinishfabrcvFrm  [name=id]').val();
		let inv_finish_fab_rcv_id=$('#invfinishfabrcvFrm  [name=inv_finish_fab_rcv_id]').val();
		
		let prod_finish_dlv_roll_id=this.getSelection();

		let data= axios.get(this.route+"/create"+"?prod_finish_dlv_roll_id="+prod_finish_dlv_roll_id+'&inv_finish_fab_rcv_id='+inv_finish_fab_rcv_id)
		.then(function (response) {
			$('#invfinishfabrcvitemscs').html(response.data);
			$('#invfinishfabrcvitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	getSelection(){
		let prod_finish_dlv_roll_ids=[];
		let name=[];
		let checked=$('#invfinishfabrcvitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			prod_finish_dlv_roll_ids.push(val.id)
		});
		prod_finish_dlv_roll_id=prod_finish_dlv_roll_ids.join(',');
		$('#invfinishfabrcvitemsearchTbl').datagrid('clearSelections');
		$('#invfinishfabrcvitemsearchwindow').window('close');
		return prod_finish_dlv_roll_id;

	}
	

	
	copyRoom(room,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#invfinishfabrcvitemmatrixFrm input[name="room['+i+']"]').val(room)
	}
	}
	copyRack(rack,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#invfinishfabrcvitemmatrixFrm input[name="rack['+i+']"]').val(rack)
	}
	}

	copyShelf(shelf,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#invfinishfabrcvitemmatrixFrm input[name="shelf['+i+']"]').val(shelf)
	}
	}
	

	

	

	

	

}
window.MsInvFinishFabRcvItem=new MsInvFinishFabRcvItemController(new MsInvFinishFabRcvItemModel());
MsInvFinishFabRcvItem.showGrid([]);
MsInvFinishFabRcvItem.itemSearchGrid([]);
