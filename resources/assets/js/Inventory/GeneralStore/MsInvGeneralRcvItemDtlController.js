let MsInvGeneralRcvItemDtlModel = require('./MsInvGeneralRcvItemDtlModel');

class MsInvGeneralRcvItemDtlController {
	constructor(MsInvGeneralRcvItemDtlModel)
	{
		this.MsInvGeneralRcvItemDtlModel = MsInvGeneralRcvItemDtlModel;
		this.formId='invgeneralrcvitemdtlFrm';	             
		this.dataTable='#invgeneralrcvitemdtlTbl';
		this.route=msApp.baseUrl()+"/invgeneralrcvitemdtl"
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
         let inv_general_rcv_item_id = $('#invgeneralrcvitemFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_general_rcv_item_id=inv_general_rcv_item_id;

		if(formObj.id){
			this.MsInvGeneralRcvItemDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralRcvItemDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralRcvItemDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralRcvItemDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvGeneralRcvItemDtl.resetForm();
		MsInvGeneralRcvItemDtl.get(d.inv_general_rcv_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvGeneralRcvItemDtlModel.get(index,row);

	}
	get(inv_general_rcv_item_id){
		let params={};
		params.inv_general_rcv_item_id=inv_general_rcv_item_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invgeneralrcvitemdtlTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralRcvItemDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	

	

}
window.MsInvGeneralRcvItemDtl=new MsInvGeneralRcvItemDtlController(new MsInvGeneralRcvItemDtlModel());
MsInvGeneralRcvItemDtl.showGrid([]);