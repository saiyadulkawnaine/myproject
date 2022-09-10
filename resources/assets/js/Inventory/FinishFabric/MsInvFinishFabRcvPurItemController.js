let MsInvFinishFabRcvPurItemModel = require('./MsInvFinishFabRcvPurItemModel');

class MsInvFinishFabRcvPurItemController {
	constructor(MsInvFinishFabRcvPurItemModel)
	{
		this.MsInvFinishFabRcvPurItemModel = MsInvFinishFabRcvPurItemModel;
		this.formId='invfinishfabrcvpuritemFrm';	             
		this.dataTable='#invfinishfabrcvpuritemTbl';
		this.route=msApp.baseUrl()+"/invfinishfabrcvpuritem"
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
		let inv_rcv_id=$('#invfinishfabrcvpurFrm [name=id]').val()
		let inv_finish_fab_rcv_id=$('#invfinishfabrcvpurFrm [name=inv_finish_fab_rcv_id]').val();
		let inv_finish_fab_rcv_fabric_id=$('#invfinishfabrcvpurfabricFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;
		formObj.inv_finish_fab_rcv_fabric_id=inv_finish_fab_rcv_fabric_id;
		if(formObj.id){
			this.MsInvFinishFabRcvPurItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabRcvPurItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabRcvPurItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabRcvPurItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsInvFinishFabRcvPurItem.resetForm();
		MsInvFinishFabRcvPurItem.get(d.inv_finish_fab_rcv_fabric_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvFinishFabRcvPurItemModel.get(index,row);

	}
	get(inv_finish_fab_rcv_fabric_id){
		let params={};
		params.inv_finish_fab_rcv_fabric_id=inv_finish_fab_rcv_fabric_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invfinishfabrcvpuritemTbl').datagrid('loadData',response.data);
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
				$('#invfinishfabrcvpuritemTbl').datagrid('reloadFooter', [
				{ 
					qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabRcvPurItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	
}
window.MsInvFinishFabRcvPurItem=new MsInvFinishFabRcvPurItemController(new MsInvFinishFabRcvPurItemModel());
MsInvFinishFabRcvPurItem.showGrid([]);
