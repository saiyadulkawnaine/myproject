//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsCapacityDistBuyerTeamModel = require('./MsCapacityDistBuyerTeamModel');
class MsCapacityDistBuyerTeamController {
	constructor(MsCapacityDistBuyerTeamModel)
	{
		this.MsCapacityDistBuyerTeamModel = MsCapacityDistBuyerTeamModel;
		this.formId='capacitydistbuyerteamFrm';
		this.dataTable='#capacitydistbuyerteamTbl';
		this.route=msApp.baseUrl()+"/capacitydistbuyerteam"
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
			this.MsCapacityDistBuyerTeamModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCapacityDistBuyerTeamModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCapacityDistBuyerTeamModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCapacityDistBuyerTeamModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#capacitydistbuyerteamTbl').datagrid('reload');
		//$('#CapacityDistBuyerTeamFrm  [name=id]').val(d.id);
		msApp.resetForm('capacitydistbuyerteamFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCapacityDistBuyerTeamModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsCapacityDistBuyerTeam.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCapacityDistBuyerTeam=new MsCapacityDistBuyerTeamController(new MsCapacityDistBuyerTeamModel());
MsCapacityDistBuyerTeam.showGrid();
