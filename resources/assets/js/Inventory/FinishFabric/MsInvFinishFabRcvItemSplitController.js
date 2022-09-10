let MsInvFinishFabRcvItemSplitModel = require('./MsInvFinishFabRcvItemSplitModel');

class MsInvFinishFabRcvItemSplitController {
	constructor(MsInvFinishFabRcvItemSplitModel)
	{
		this.MsInvFinishFabRcvItemSplitModel = MsInvFinishFabRcvItemSplitModel;
		this.formId='invgreyfabrcvitemsplitFrm';	             
		this.dataTable='#invgreyfabrcvitemsplitTbl';
		this.route=msApp.baseUrl()+"/invgreyfabrcvitemsplit"
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
		let inv_grey_fab_rcv_item_id=$('#invgreyfabrcvitemFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_item_id;
		if(formObj.id){
			this.MsInvFinishFabRcvItemSplitModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabRcvItemSplitModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabRcvItemSplitModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabRcvItemSplitModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsInvFinishFabRcvItemSplit.resetForm();
		MsInvFinishFabRcvItemSplit.get(d.inv_grey_fab_rcv_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvFinishFabRcvItemSplitModel.get(index,row);

	}
	get(inv_grey_fab_rcv_item_id){
		let params={};
		params.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_item_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgreyfabrcvitemsplitTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabRcvItemSplit.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsInvFinishFabRcvItemSplit=new MsInvFinishFabRcvItemSplitController(new MsInvFinishFabRcvItemSplitModel());
MsInvFinishFabRcvItemSplit.showGrid([]);