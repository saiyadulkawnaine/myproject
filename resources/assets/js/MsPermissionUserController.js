let MsPermissionUserModel = require('./MsPermissionUserModel');
class MsPermissionUserController {
	constructor(MsPermissionUserModel)
	{
		this.MsPermissionUserModel = MsPermissionUserModel;
		this.formId='permissionuserFrm';
		this.dataTable='#permissionuserTbl';
		this.route=msApp.baseUrl()+"/permissionuser"
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
		
		let formObj=msApp.get('permissionuserFrm');
		let i=1;
		$.each($('#permissionuserTbl').datagrid('getChecked'), function (idx, val) {
				formObj['permission_id['+i+']']=val.id
				
			i++;
		});
		this.MsPermissionUserModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var user_id=$('#userFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/permissionuser/create?user_id="+user_id);
				data.then(function (response) {
				$('#permissionuserTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Permission',width:300},
				]],
				});
				
				$('#permissionusersavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Permission',width:300},
				{field:'action',title:'',width:60,formatter:MsPermissionUser.formatDetail},
				]],
				});
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
		this.MsPermissionUserModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPermissionUserModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#buyernatureTbl').datagrid('reload');
		MsPermissionUser.create()
		//msApp.resetForm('buyernatureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPermissionUserModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsPermissionUser.delete(event,'+row.permission_user_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPermissionUser=new MsPermissionUserController(new MsPermissionUserModel());

