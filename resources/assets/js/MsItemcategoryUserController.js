let MsItemcategoryUserModel = require('./MsItemcategoryUserModel');
class MsItemcategoryUserController {
	constructor(MsItemcategoryUserModel)
	{
		this.MsItemcategoryUserModel = MsItemcategoryUserModel;
		this.formId='itemcategoryuserFrm';
		this.dataTable='#itemcategoryuserTbl';
		this.route=msApp.baseUrl()+"/itemcategoryuser"
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

		let formObj=msApp.get('itemcategoryuserFrm');
		let i=1;
		$.each($('#itemcategoryuserTbl').datagrid('getChecked'), function (idx, val) {
				formObj['itemcategory_id['+i+']']=val.id
				
			i++;
		});
		this.MsItemcategoryUserModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var user_id=$('#userFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/itemcategoryuser/create?user_id="+user_id);
				data.then(function (response) {
				$('#itemcategoryuserTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Itemcategory',width:100},
				]],
				});
				
				$('#itemcategoryusersavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Itemcategory',width:100},
				{field:'action',title:'',width:60,formatter:MsItemcategoryUser.formatDetail},
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
		this.MsItemcategoryUserModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsItemcategoryUserModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsItemcategoryUser.create()
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsItemcategoryUserModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsItemcategoryUser.delete(event,'+row.itemcategory_user_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsItemcategoryUser=new MsItemcategoryUserController(new MsItemcategoryUserModel());

