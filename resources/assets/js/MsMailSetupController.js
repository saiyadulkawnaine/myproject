let MsMailSetupModel = require('./MsMailSetupModel');
require('./datagrid-filter.js');
class MsMailSetupController {
	constructor(MsMailSetupModel)
	{
		this.MsMailSetupModel = MsMailSetupModel;
		this.formId='mailsetupFrm';
		this.dataTable='#mailsetupTbl';
		this.route=msApp.baseUrl()+"/mailsetup";
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
			this.MsMailSetupModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMailSetupModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsMailSetupModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMailSetupModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#mailsetupTbl').datagrid('reload');
		msApp.resetForm('mailsetupFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMailSetupModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMailSetup.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsMailSetup=new MsMailSetupController(new MsMailSetupModel());
MsMailSetup.showGrid();

$('#mailsetuptabs').tabs({
	onSelect:function(title,index){
		let mail_setup_id = $('#mailsetupFrm  [name=id]').val();

		var data={};
	    data.mail_setup_id=mail_setup_id;

		if(index==1){
			if(mail_setup_id===''){
				$('#mailsetuptabs').tabs('select',0);
				msApp.showError('Select Mail Setup First',0);
				return;
			}
			msApp.resetForm('mailsetupemailtoFrm');
			$('#mailsetupemailtoFrm  [name=mail_setup_id]').val(mail_setup_id);
			MsMailSetupEmailTo.showGrid(mail_setup_id);
		}

	}
});