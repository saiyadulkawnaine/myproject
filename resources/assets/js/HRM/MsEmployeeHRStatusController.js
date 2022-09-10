let MsEmployeeHRStatusModel = require('./MsEmployeeHRStatusModel');
require('./../datagrid-filter.js');
class MsEmployeeHRStatusController {
	constructor(MsEmployeeHRStatusModel)
	{
		this.MsEmployeeHRStatusModel = MsEmployeeHRStatusModel;
		this.formId='employeehrstatusFrm';
		this.dataTable='#employeehrstatusTbl';
		this.route=msApp.baseUrl()+"/employeehrstatus"
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
            this.MsEmployeeHRStatusModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeHRStatusModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);	
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeHRStatusModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeHRStatusModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsEmployeeHRStatus.get();
		msApp.resetForm('employeehrstatusFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeHRStatusModel.get(index,row);
	}
	get(){
		let grid= axios.get(this.route)
		.then(function(response){
			$('#employeehrstatusTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	searchEmployeeStatus()
	{
		let params={};
		params.date_from=$('#date_from').val();
		params.date_to=$('#date_to').val();
		let data= axios.get(this.route+"/getallemployeestatus",{params});
		data.then(function (response) {
			$('#employeehrstatusTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeHRStatus.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	sendtoapi(){
		let formObj=msApp.get(this.formId);
		let params={};
		params.id=formObj.id;
		let grid= axios.get(this.route+"/sendtoaip",{params})
		.then(function(response){
			//$('#emphrsearchstatusTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	openEmpHrWindow(){
		$('#openemphrstatuswindow').window('open');
	}

	getparams(){
		let params={};
		params.company_id=$('#employhrsearchstatusFrm [name=company_id]').val();
		params.designation_id=$('#employhrsearchstatusFrm [name=designation_id]').val();
		params.department_id=$('#employhrsearchstatusFrm [name=department_id]').val();
		params.location_id=$('#employhrsearchstatusFrm [name=location_id]').val();
		return params;
	}

	searchEmployeeHr(){
		let params=this.getparams();
		let grid= axios.get(this.route+"/getemployeehr",{params})
		.then(function(response){
			$('#emphrsearchstatusTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	showEmpHrGrid(data){
		let self=this;
		var sg=$('#emphrsearchstatusTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeehrstatusFrm  [name=employee_h_r_id]').val(row.id);
				$('#employeehrstatusFrm  [name=employee_name]').val(row.employee_name);
				$('#employeehrstatusFrm  [name=code]').val(row.code);
				$('#employeehrstatusFrm  [name=designation_name]').val(row.designation_name);
				$('#employeehrstatusFrm  [name=grade]').val(row.grade);
				$('#employeehrstatusFrm  [name=company_name]').val(row.company_name);
				$('#employeehrstatusFrm  [name=location_name]').val(row.location_name);
				$('#employeehrstatusFrm  [name=division_name]').val(row.division_name);
				$('#employeehrstatusFrm  [name=department_name]').val(row.department_name);
				$('#employeehrstatusFrm  [name=section_name]').val(row.section_name);
				$('#employeehrstatusFrm  [name=subsection_name]').val(row.subsection_name);
                $('#employeehrstatusFrm  [name=report_to_name]').val(row.report_to_name);
                $('#employeehrstatusFrm  [name=status_name]').val(row.status_name);
                $('#employeehrstatusFrm  [name=status_id]').val(row.status_id);
				
				$('#emphrsearchstatusTbl').datagrid('loadData',[]);
				$('#openemphrstatuswindow').window('close')
			}
		});
		sg.datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsEmployeeHRStatus = new MsEmployeeHRStatusController(new MsEmployeeHRStatusModel());
MsEmployeeHRStatus.showGrid([]);
MsEmployeeHRStatus.showEmpHrGrid([]);
MsEmployeeHRStatus.get();