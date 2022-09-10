//require('./../jquery.easyui.min.js');
let MsEmployeeModel = require('./MsEmployeeModel');
require('./../datagrid-filter.js');
class MsEmployeeController {
	constructor(MsEmployeeModel)
	{
		this.MsEmployeeModel = MsEmployeeModel;
		this.formId='employeeFrm';
		this.dataTable='#employeeTbl';
		this.route=msApp.baseUrl()+"/employee"
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
            this.MsEmployeeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#employeeFrm [id="designation_id"]').combobox('setValue', '');
		$('#employeeFrm [id="department_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeeTbl').datagrid('reload');
		msApp.resetForm('employeeFrm');
		$('#employeeFrm [id="designation_id"]').combobox('setValue', '');
		$('#employeeFrm [id="department_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let emp=this.MsEmployeeModel.get(index,row);
		emp.then(function (response) {		
			$('#employeeFrm [id="designation_id"]').combobox('setValue', response.data.fromData.designation_id);
			$('#employeeFrm [id="department_id"]').combobox('setValue', response.data.fromData.department_id);
		})
		.catch(function (error) {
			console.log(error);
		});
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
		return '<a href="javascript:void(0)"  onClick="MsEmployeeModel.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsEmployee = new MsEmployeeController(new MsEmployeeModel());
MsEmployee.showGrid();

