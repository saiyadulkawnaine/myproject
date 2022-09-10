let MsDepartmentFloorModel = require('./MsDepartmentFloorModel');
class MsDepartmentFloorController {
	constructor(MsDepartmentFloorModel)
	{
		this.MsDepartmentFloorModel = MsDepartmentFloorModel;
		this.formId='departmentfloorFrm';
		this.dataTable='#departmentfloorTbl';
		this.route=msApp.baseUrl()+"/departmentfloor"
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

		let formObj=msApp.get('departmentfloorFrm');
		let i=1;
		$.each($('#departmentfloorTbl').datagrid('getChecked'), function (idx, val) {
				formObj['floor_id['+i+']']=val.id
				
			i++;
		});
		this.MsDepartmentFloorModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var department_id=$('#departmentFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/departmentfloor/create?department_id="+department_id);
		data.then(function (response) {
		$('#departmentfloorTbl').datagrid({
		checkbox:true,
		rownumbers:true,
		data: response.data.unsaved,
		
		columns:[[
		{field:'ck',checkbox:true,width:40},
		{field:'name',title:'Floor',width:100},
		]],
		});
		
		$('#departmentfloorsavedTbl').datagrid({
		rownumbers:true,
		data: response.data.saved,
		columns:[[
		{field:'name',title:'Floor',width:100},
		{field:'action',title:'',width:60,formatter:MsDepartmentFloor.formatDetail},
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
		this.MsDepartmentFloorModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		alert(id)
		this.MsDepartmentFloorModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#departmentfloorTbl').datagrid('reload');
		MsDepartmentFloor.create()
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsDepartmentFloorModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsDepartmentFloor.delete(event,'+row.department_floor_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsDepartmentFloor=new MsDepartmentFloorController(new MsDepartmentFloorModel());