let MsInvCasReqPaidModel = require('./MsInvCasReqPaidModel');
class MsInvCasReqPaidController {
	constructor(MsInvCasReqPaidModel)
	{
		this.MsInvCasReqPaidModel = MsInvCasReqPaidModel;
		this.formId='invcasreqpaidFrm';
		this.dataTable='#invcasreqpaidTbl';
		this.route=msApp.baseUrl()+"/invcasreqpaid"
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
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsInvCasReqPaidModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvCasReqPaidModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invcasreqpaidFrm [id="user_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvCasReqPaidModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvCasReqPaidModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invcasreqpaidTbl').datagrid('reload');
		msApp.resetForm('invcasreqpaidFrm');
		$('#invcasreqpaidFrm [name=inv_pur_req_id]').val($('#invcasreqFrm [name=id]').val());
		$('#invcasreqpaidFrm [id="user_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let prItem=this.MsInvCasReqPaidModel.get(index,row);
		prItem.then(function(response){
			$('#invcasreqpaidFrm [id="user_id"]').combobox('setValue', response.data.fromData.user_id);
		}).catch(function(error){
			console.log(error);
		});
		
	}
	 showGrid(inv_pur_req_id)
	 {
		 let self=this;
		 var data={};
		 data.inv_pur_req_id=inv_pur_req_id;
		 $(this.dataTable).datagrid({
			 method:'get',
			 border:false,
			 singleSelect:true,
			 fit:true,
			 fitColumns:true,
			 queryParams:data,
			 url:this.route,
			 onClickRow: function(index,row){
				 self.edit(index,row);
			 }
		 }).datagrid('enableFilter');
	 }

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvCasReqPaid.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
}
window.MsInvCasReqPaid=new MsInvCasReqPaidController(new MsInvCasReqPaidModel());