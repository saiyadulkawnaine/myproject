//require('./jquery.easyui.min.js');
let MsRoleModel = require('./MsRoleModel');
require('./datagrid-filter.js');

class MsRoleController {
	constructor(MsRoleModel)
	{
		this.MsRoleModel = MsRoleModel;
		this.formId='roleFrm';
		this.dataTable='#roleTbl';
		this.route=msApp.baseUrl()+"/role"
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

		let permissionId= new Array();
		$('#lstBox2 option').map(function(i, el) {
			permissionId.push($(el).val());
		});
		$('#permission_id').val( permissionId.join());
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsRoleModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsRoleModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsRoleModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsRoleModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#roleTbl').datagrid('reload');
		msApp.resetForm('roleFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsRoleModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsRole.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsRole=new MsRoleController(new MsRoleModel());
MsRole.showGrid();
