let MsUserModel = require('./MsUserModel');
require('./datagrid-filter.js');
class MsUserController {
	constructor(MsUserModel)
	{
		this.MsUserModel = MsUserModel;
		this.formId='userFrm';
		this.dataTable='#userTbl';
		this.route=msApp.baseUrl()+"/user"
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
			this.MsUserModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsUserModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsUserModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsUserModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#userTbl').datagrid('reload');
		msApp.resetForm('userFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsUserModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsUser.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsUser=new MsUserController(new MsUserModel());
MsUser.showGrid();

$('#utilusertabs').tabs({
        onSelect:function(title,index){
	let user_id = $('#userFrm  [name=id]').val();

	var data={};
	data.user_id=user_id;

	if(index==1){
	if(user_id===''){
	$('#utilusertabs').tabs('select',0);
	msApp.showError('Select User First',0);
	return;
	}
	$('#companyuserFrm  [name=user_id]').val(user_id)
	MsCompanyUser.create()
	}
	if(index==2){
	if(user_id===''){
	$('#utilusertabs').tabs('select',0);
	msApp.showError('Select User First',0);
	return;
	}
	$('#buyeruserFrm  [name=user_id]').val(user_id)
	MsBuyerUser.create()
	}
	if(index==3){
	if(user_id===''){
	$('#utilusertabs').tabs('select',0);
	msApp.showError('Select User First',0);
	return;
	}
	$('#supplieruserFrm  [name=user_id]').val(user_id)
	MsSupplierUser.create()
	}

	if(index==4){
	if(user_id===''){
	$('#utilusertabs').tabs('select',0);
	msApp.showError('Select User First',0);
	return;
	}
	$('#permissionuserFrm  [name=user_id]').val(user_id)
	MsPermissionUser.create()
	}
	if(index==5){
	if(user_id===''){
	$('#utilusertabs').tabs('select',0);
	msApp.showError('Select User First',0);
	return;
	}
	$('#itemcategoryuserFrm  [name=user_id]').val(user_id)
	MsItemcategoryUser.create()
	}
	if(index==6){
	if(user_id===''){
	 $('#utilusertabs').tabs('select',0);
	 msApp.showError('Select User First',0);
	 return;
	}
	$('#signatureuserFrm  [name=id]').val(user_id)

	let index=null;
	let row={};
	row.id=user_id;

	MsSignatureUser.edit(index,row);
	MsSignatureUser.showGrid(user_id);
	}
			
			
			
    }
 });
