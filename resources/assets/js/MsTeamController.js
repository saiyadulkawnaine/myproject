

require('./jquery.easyui.min.js');
let MsTeamModel = require('./MsTeamModel');
require('./datagrid-filter.js');

class MsTeamController {
	constructor(MsTeamModel)
	{
		this.MsTeamModel = MsTeamModel;
		this.formId='teamFrm';
		this.dataTable='#teamTbl';
		this.route=msApp.baseUrl()+"/team"
	}

	submit()
	{
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsTeamModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsTeamModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsTeamModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTeamModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#teamTbl').datagrid('reload');
		$('#teamFrm  [name=id]').val(d.id);
		$('#teammemberFrm  [name=team_id]').val(d.id);
		//msApp.resetForm('teamFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsTeamModel.get(index,row);
		$('#teammemberFrm  [name=team_id]').val(row.id);
		MsTeammember.showGrid(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsTeam.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsTeam=new MsTeamController(new MsTeamModel());
MsTeam.showGrid();
