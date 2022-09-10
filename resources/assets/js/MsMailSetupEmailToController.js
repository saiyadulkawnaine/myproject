let MsMailSetupEmailToModel = require('./MsMailSetupEmailToModel');
class MsMailSetupEmailToController {
	constructor(MsMailSetupEmailToModel)
	{
		this.MsMailSetupEmailToModel = MsMailSetupEmailToModel;
		this.formId='mailsetupemailtoFrm';
		this.dataTable='#mailsetupemailtoTbl';
		this.route=msApp.baseUrl()+"/mailsetupemailto";
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
			this.MsMailSetupEmailToModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMailSetupEmailToModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#mailsetupemailtoFrm [name=mail_setup_id]').val($('#mailsetupFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsMailSetupEmailToModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMailSetupEmailToModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#mailsetupemailtoTbl').datagrid('reload');
		msApp.resetForm('mailsetupemailtoFrm');
		$('#mailsetupemailtoFrm [name=mail_setup_id]').val($('#mailsetupFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMailSetupEmailToModel.get(index,row);
	}

	showGrid(mail_setup_id)
	{
		let self=this;
		let data={};
		data.mail_setup_id=mail_setup_id;
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
		return '<a href="javascript:void(0)"  onClick="MsMailSetupEmailTo.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}

window.MsMailSetupEmailTo=new MsMailSetupEmailToController(new MsMailSetupEmailToModel());