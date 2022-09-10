let MsEmployeeBudgetPositionModel = require('./MsEmployeeBudgetPositionModel');
class MsEmployeeBudgetPositionController {
	constructor(MsEmployeeBudgetPositionModel)
	{
		this.MsEmployeeBudgetPositionModel = MsEmployeeBudgetPositionModel;
		this.formId='employeebudgetpositionFrm';
		this.dataTable='#employeebudgetpositionTbl';
		this.route=msApp.baseUrl()+"/employeebudgetposition"
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
            this.MsEmployeeBudgetPositionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeBudgetPositionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#employeebudgetpositionFrm [name=employee_budget_id]').val($('#employeebudgetFrm [name=id]').val());		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeBudgetPositionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeBudgetPositionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeebudgetpositionTbl').datagrid('reload');
        msApp.resetForm('employeebudgetpositionFrm');
		$('#employeebudgetpositionFrm [name=employee_budget_id]').val($('#employeebudgetFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeBudgetPositionModel.get(index,row);
	}

	showGrid(employee_budget_id)
	{
		let self=this;
		let data={}
		data.employee_budget_id=employee_budget_id
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeBudgetPosition.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	searchDesignation(){
		let usr= axios.get(this.route+'/getdesignation')
		.then(function(response){
			$('#designationsearchTbl').datagrid('loadData', response.data);
			$('#opendesignationwindow').window('open');
		}).catch(function (error) {
			console.log(error);
		});
		return usr;

	}
	showDesignationGrid(data){
		let self=this;
		var ff=$('#designationsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeebudgetpositionFrm  [name=designation_id]').val(row.id);
				$('#employeebudgetpositionFrm  [name=designation_name]').val(row.name);
				$('#employeebudgetpositionFrm  [name=designation_level_id]').val(row.designation_level_id);
				$('#employeebudgetpositionFrm  [name=grade]').val(row.grade);
				$('#opendesignationwindow').window('close')
			}
		});
		ff.datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsEmployeeBudgetPosition = new MsEmployeeBudgetPositionController(new MsEmployeeBudgetPositionModel());
MsEmployeeBudgetPosition.showDesignationGrid([]);