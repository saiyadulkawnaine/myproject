let MsGmtspartMenuModel = require('./MsGmtspartMenuModel');
class MsGmtspartMenuController {
	constructor(MsGmtspartMenuModel)
	{
		this.MsGmtspartMenuModel = MsGmtspartMenuModel;
		this.formId='gmtspartmenuFrm';
		this.dataTable='#gmtspartmenuTbl';
		this.route=msApp.baseUrl()+"/gmtspartmenu"
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

		let formObj=msApp.get('gmtspartmenuFrm');
		let i=1;
		$.each($('#gmtspartmenuTbl').datagrid('getChecked'), function (idx, val) {
				formObj['menu_id['+i+']']=val.id
				
			i++;
		});
		this.MsGmtspartMenuModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var gmtspart_id=$('#gmtspartFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/gmtspartmenu/create?gmtspart_id="+gmtspart_id);
				data.then(function (response) {
				$('#gmtspartmenuTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Available',width:100},
				]],
				}).datagrid('enableFilter');
				
				$('#gmtspartmenusavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Saved',width:100},
				{field:'action',title:'',width:60,formatter:MsGmtspartMenu.formatDetail},
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
		this.MsGmtspartMenuModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		//alert(id)
		this.MsGmtspartMenuModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsGmtspartMenu.create();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsGmtspartMenuModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsGmtspartMenu.delete(event,'+row.gmtspart_menu_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsGmtspartMenu=new MsGmtspartMenuController(new MsGmtspartMenuModel());

