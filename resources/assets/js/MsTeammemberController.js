//require('./jquery.easyui.min.js');
let MsTeammemberModel = require('./MsTeammemberModel');
require('./datagrid-filter.js');

class MsTeammemberController {
	constructor(MsTeammemberModel)
	{
		this.MsTeammemberModel = MsTeammemberModel;
		this.formId='teammemberFrm';
		this.dataTable='#teammemberTbl';
		this.route=msApp.baseUrl()+"/teammember"
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
			this.MsTeammemberModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsTeammemberModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsTeammemberModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTeammemberModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#teammemberTbl').datagrid('reload');
		//$('#TeammemberFrm  [name=id]').val(d.id);
		msApp.resetForm('teammemberFrm');
		$('#teammemberFrm  [name=team_id]').val($('#teamFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsTeammemberModel.get(index,row);
	}

	showGrid(id)
	{
		var data={};
		data.team_id=id;
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			queryParams:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsTeammember.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsTeammember=new MsTeammemberController(new MsTeammemberModel());
MsTeammember.showGrid();
