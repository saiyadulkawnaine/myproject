//require('../jquery.easyui.min.js');

let MsEmployeeBudgetModel = require('./MsEmployeeBudgetModel');
require('./../datagrid-filter.js');
class MsEmployeeBudgetController {
	constructor(MsEmployeeBudgetModel)
	{
		this.MsEmployeeBudgetModel = MsEmployeeBudgetModel;
		this.formId='employeebudgetFrm';
		this.dataTable='#employeebudgetTbl';
		this.route=msApp.baseUrl()+"/employeebudget"
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
            this.MsEmployeeBudgetModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeBudgetModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#employeebudgetFrm [id="department_id"]').combobox('setValue', '');
		$('#employeebudgetFrm [id="subsection_id"]').combobox('setValue', '');
		$('#employeebudgetFrm [id="section_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeBudgetModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeBudgetModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeebudgetTbl').datagrid('reload');
		msApp.resetForm('employeebudgetFrm');
		$('#employeebudgetFrm [id="department_id"]').combobox('setValue', '');
		$('#employeebudgetFrm [id="subsection_id"]').combobox('setValue', '');
		$('#employeebudgetFrm [id="section_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let empcombo=this.MsEmployeeBudgetModel.get(index,row);
		empcombo.then(function (response) {		
			$('#employeebudgetFrm [id="department_id"]').combobox('setValue', response.data.fromData.department_id);
			$('#employeebudgetFrm [id="subsection_id"]').combobox('setValue', response.data.fromData.subsection_id);
			$('#employeebudgetFrm [id="section_id"]').combobox('setValue', response.data.fromData.section_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid()
	{
		let self=this;
		var ac=$('#employeebudgetTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
		ac.datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeBudget.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsEmployeeBudget = new MsEmployeeBudgetController(new MsEmployeeBudgetModel());
MsEmployeeBudget.showGrid();

$('#employeeBudgettabs').tabs({
	onSelect:function(title,index){
	   let employee_budget_id = $('#employeebudgetFrm  [name=id]').val();

		var data={};
		data.employee_budget_id=employee_budget_id;

		if(index==1){
			if(employee_budget_id===''){
				$('#employeeBudgettabs').tabs('select',0);
				msApp.showError('Select An Start Up First',0);
				return;
			}
			msApp.resetForm('employeebudgetpositionFrm');
			$('#employeebudgetpositionFrm  [name=employee_budget_id]').val(employee_budget_id)
			MsEmployeeBudgetPosition.showGrid(employee_budget_id);
		}
	}
});