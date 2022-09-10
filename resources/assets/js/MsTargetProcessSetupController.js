let MsTargetProcessSetupModel = require('./MsTargetProcessSetupModel');
require('./datagrid-filter.js');

class MsTargetProcessSetupController {
	constructor(MsTargetProcessSetupModel)
	{
		this.MsTargetProcessSetupModel = MsTargetProcessSetupModel;
		this.formId='targetprocesssetupFrm';
		this.dataTable='#targetprocesssetupTbl';
		this.route=msApp.baseUrl()+"/targetprocesssetup"
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
			this.MsTargetProcessSetupModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsTargetProcessSetupModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#targetprocesssetupFrm [id=production_area_id]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsTargetProcessSetupModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTargetProcessSetupModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#targetprocesssetupTbl').datagrid('reload');
		$('#targetprocesssetupFrm [id=production_area_id]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let tgtprocess = this.MsTargetProcessSetupModel.get(index, row);
		tgtprocess.then(function (response) {
			$('#targetprocesssetupFrm [id=production_area_id]').combobox('setValue', response.data.fromData.production_area_id);
		})
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
		return '<a href="javascript:void(0)"  onClick="MsTargetProcessSetup.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsTargetProcessSetup=new MsTargetProcessSetupController(new MsTargetProcessSetupModel());
MsTargetProcessSetup.showGrid();
