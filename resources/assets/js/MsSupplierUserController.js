let MsSupplierUserModel = require('./MsSupplierUserModel');
class MsSupplierUserController {
	constructor(MsSupplierUserModel)
	{
		this.MsSupplierUserModel = MsSupplierUserModel;
		this.formId='supplieruserFrm';
		this.dataTable='#supplieruserTbl';
		this.route=msApp.baseUrl()+"/supplieruser"
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

		let formObj=msApp.get('supplieruserFrm');
		let i=1;
		$.each($('#supplieruserTbl').datagrid('getChecked'), function (idx, val) {
				formObj['supplier_id['+i+']']=val.id
				
			i++;
		});
		this.MsSupplierUserModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var user_id=$('#userFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/supplieruser/create?user_id="+user_id);
				data.then(function (response) {
				$('#supplieruserTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Supplier',width:100},
				]],
				});
				
				$('#supplierusersavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Supplier',width:100},
				{field:'action',title:'',width:60,formatter:MsSupplierUser.formatDetail},
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
		this.MsSupplierUserModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSupplierUserModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#buyernatureTbl').datagrid('reload');
		MsSupplierUser.create()
		//msApp.resetForm('buyernatureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSupplierUserModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSupplierUser.delete(event,'+row.supplier_user_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSupplierUser=new MsSupplierUserController(new MsSupplierUserModel());

