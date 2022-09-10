let MsSmsSetupModel = require('./MsSmsSetupModel');
require('./datagrid-filter.js');
class MsSmsSetupController {
	constructor(MsSmsSetupModel)
	{
		this.MsSmsSetupModel = MsSmsSetupModel;
		this.formId='smssetupFrm';
		this.dataTable='#smssetupTbl';
		this.route=msApp.baseUrl()+"/smssetup";
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
			this.MsSmsSetupModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmsSetupModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#smssetupFrm [id="menu_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsSmsSetupModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmsSetupModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#smssetupTbl').datagrid('reload');
		msApp.resetForm('smssetupFrm');
		$('#smssetupFrm [id="menu_id"]').combobox('setValue','');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let sms=this.MsSmsSetupModel.get(index,row);
		sms.then(function(response){
			$('#smssetupFrm [id="menu_id"]').combobox('setValue',response.data.fromData.menu_id);
		})
		.catch(function(error){
			console.log(error);
		});
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
		return '<a href="javascript:void(0)"  onClick="MsSmsSetup.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSmsSetup=new MsSmsSetupController(new MsSmsSetupModel());
MsSmsSetup.showGrid();
$('#smssetuptabs').tabs({
	onSelect:function(title,index){
		let sms_setup_id = $('#smssetupFrm  [name=id]').val();
		var data={};
	    data.sms_setup_id=sms_setup_id;

		if(index==1){
			if(sms_setup_id===''){
				$('#smssetuptabs').tabs('select',0);
				msApp.showError('Select Sms Setup First',0);
				return;
			}
			msApp.resetForm('smssetupsmstoFrm');
			$('#smssetupsmstoFrm  [name=sms_setup_id]').val(sms_setup_id);
			MsSmsSetupSmsTo.showGrid(sms_setup_id);
		}

	}
});