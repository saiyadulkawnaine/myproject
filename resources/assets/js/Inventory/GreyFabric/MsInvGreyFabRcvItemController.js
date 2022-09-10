let MsInvGreyFabRcvItemModel = require('./MsInvGreyFabRcvItemModel');

class MsInvGreyFabRcvItemController {
	constructor(MsInvGreyFabRcvItemModel)
	{
		this.MsInvGreyFabRcvItemModel = MsInvGreyFabRcvItemModel;
		this.formId='invgreyfabrcvitemFrm';	             
		this.dataTable='#invgreyfabrcvitemTbl';
		this.route=msApp.baseUrl()+"/invgreyfabrcvitem"
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
		let inv_rcv_id=$('#invgreyfabrcvFrm [name=id]').val()
		let inv_greyfab_rcv_id=$('#invgreyfabrcvFrm [name=inv_greyfab_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_greyfab_rcv_id=inv_greyfab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;
		if(formObj.id){
			this.MsInvGreyFabRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invgreyfabrcvFrm [name=id]').val()
		let inv_greyfab_rcv_id=$('#invgreyfabrcvFrm [name=inv_greyfab_rcv_id]').val()
		let formObj=msApp.get('invgreyfabrcvitemmatrixFrm');

		formObj.inv_rcv_id=inv_rcv_id;
		formObj.inv_greyfab_rcv_id=inv_greyfab_rcv_id;
		if(formObj.id){
			this.MsInvGreyFabRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsInvGreyFabRcvItem.resetForm();
		MsInvGreyFabRcvItem.get(d.inv_greyfab_rcv_id)
		$('#invgreyfabrcvitemWindow').window('close');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvGreyFabRcvItemModel.get(index,row);

	}
	get(inv_greyfab_rcv_id){
		let params={};
		params.inv_greyfab_rcv_id=inv_greyfab_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgreyfabrcvitemTbl').datagrid('loadData',response.data);
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
				$('#invgreyfabrcvitemTbl').datagrid('reloadFooter', [
				{ 
					qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		MsInvGreyFabRcvItem.itemSearchGrid([]);
		let inv_greyfab_rcv_id=$('#invgreyfabrcvFrm [name=inv_greyfab_rcv_id]').val();
		let params={};
		params.inv_greyfab_rcv_id=inv_greyfab_rcv_id;

		let d=axios.get(this.route+'/getgreyfabitem',{params})
		.then(function(response){
			$('#invgreyfabrcvitemsearchTbl').datagrid('loadData',response.data);
			$('#invgreyfabrcvitemsearchwindow').window('open');
		}).catch(function(error){
			console.log(error);
		});
	}

	itemSearchGrid(data){
		let self=this;
		$('#invgreyfabrcvitemsearchTbl').datagrid({
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

	

	closeinvgreyfabrcvitemsearchwindow()
	{
		let inv_rcv_id=$('#invgreyfabrcvFrm  [name=id]').val();
		let inv_greyfab_rcv_id=$('#invgreyfabrcvFrm  [name=inv_greyfab_rcv_id]').val();
		
		let prod_knit_dlv_roll_id=this.getSelection();

		let data= axios.get(this.route+"/create"+"?prod_knit_dlv_roll_id="+prod_knit_dlv_roll_id+'&inv_greyfab_rcv_id='+inv_greyfab_rcv_id)
		.then(function (response) {
			$('#invgreyfabrcvitemscs').html(response.data);
			$('#invgreyfabrcvitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	getSelection(){
		let prod_knit_dlv_roll_ids=[];
		let name=[];
		let checked=$('#invgreyfabrcvitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			prod_knit_dlv_roll_ids.push(val.id)
		});
		prod_knit_dlv_roll_id=prod_knit_dlv_roll_ids.join(',');
		$('#invgreyfabrcvitemsearchTbl').datagrid('clearSelections');
		$('#invgreyfabrcvitemsearchwindow').window('close');
		return prod_knit_dlv_roll_id;

	}
	

	
	copyRoom(room,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#invgreyfabrcvitemmatrixFrm input[name="room['+i+']"]').val(room)
	}
	}
	copyRack(rack,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#invgreyfabrcvitemmatrixFrm input[name="rack['+i+']"]').val(rack)
	}
	}

	copyShelf(shelf,iteration,count)
	{
	for(var i=iteration;i<=count;i++)
	{
	$('#invgreyfabrcvitemmatrixFrm input[name="shelf['+i+']"]').val(shelf)
	}
	}
	

	

	

	

	

}
window.MsInvGreyFabRcvItem=new MsInvGreyFabRcvItemController(new MsInvGreyFabRcvItemModel());
MsInvGreyFabRcvItem.showGrid([]);
MsInvGreyFabRcvItem.itemSearchGrid([]);
