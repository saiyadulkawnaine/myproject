let MsAccChartDepartmentModel = require('./MsAccChartDepartmentModel');
class MsAccChartDepartmentController {
	constructor(MsAccChartDepartmentModel)
	{
		this.MsAccChartDepartmentModel = MsAccChartDepartmentModel;
		this.formId='accchartdepartmentFrm';
		this.dataTable='#accchartdepartmentTbl';
		this.route=msApp.baseUrl()+"/accchartdepartment"
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

		let formObj=msApp.get('accchartdepartmentFrm');
		let i=1;
		$.each($('#accchartdepartmentTbl').datagrid('getChecked'), function (idx, val) {
				formObj['department_id['+i+']']=val.id
				
			i++;
		});
		this.MsAccChartDepartmentModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var acc_chart_ctrl_head_id=$('#accchartctrlheadFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/accchartdepartment/create?acc_chart_ctrl_head_id="+acc_chart_ctrl_head_id);
				data.then(function (response) {
				$('#accchartdepartmentTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Department',width:100},
				]],
				});
				
				$('#accchartdepartmentsavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Department',width:100},
				{field:'action',title:'',width:60,formatter:MsAccChartDepartment.formatDetail},
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
		this.MsAccChartDepartmentModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccChartDepartmentModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		 MsAccChartDepartment.create();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccChartDepartmentModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick=" MsAccChartDepartment.delete(event,'+row.acc_chart_department_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAccChartDepartment=new MsAccChartDepartmentController(new MsAccChartDepartmentModel());



