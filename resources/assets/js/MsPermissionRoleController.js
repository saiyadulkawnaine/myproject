let MsPermissionRoleModel = require('./MsPermissionRoleModel');
class MsPermissionRoleController {
	constructor(MsPermissionRoleModel)
	{
		this.MsPermissionRoleModel = MsPermissionRoleModel;
		this.formId='permissionroleFrm';
		this.dataTable='#permissionroleTbl';
		this.route=msApp.baseUrl()+"/permissionrole"
	}

	submit()
	{
		let formObj=msApp.get('permissionroleFrm');
		let i=1;
		$.each($('#permissionroleTbl').datagrid('getChecked'), function (idx, val) {
				formObj['permission_id['+i+']']=val.id
				
			i++;
		});
		this.MsPermissionRoleModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var role_id=$('#roleFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/permissionrole/create?role_id="+role_id);
				data.then(function (response) {
				$('#permissionroleTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Permission',width:280},
				{field:'slug',title:'Slug',width:120},
				]],
				}).datagrid('enableFilter');
				
				$('#permissionrolesavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Permission',width:280},
				{field:'slug',title:'Slug',width:120},
				{field:'action',title:'',width:60,formatter:MsPermissionRole.formatDetail},
				]],
				}).datagrid('enableFilter');
				})
				.catch(function (error) {
				console.log(error);
				});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPermissionRoleModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPermissionRoleModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
	
		MsPermissionRole.create()
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPermissionRoleModel.get(index,row);
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
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPermissionRole.delete(event,'+row.permission_role_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPermissionRole=new MsPermissionRoleController(new MsPermissionRoleModel());

