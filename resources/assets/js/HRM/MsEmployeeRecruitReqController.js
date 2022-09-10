//require('../jquery.easyui.min.js');
let MsEmployeeRecruitReqModel = require('./MsEmployeeRecruitReqModel');
require('./../datagrid-filter.js');
class MsEmployeeRecruitReqController {
	constructor(MsEmployeeRecruitReqModel)
	{
		this.MsEmployeeRecruitReqModel = MsEmployeeRecruitReqModel;
		this.formId='employeerecruitreqFrm';
		this.dataTable='#employeerecruitreqTbl';
		this.route=msApp.baseUrl()+"/employeerecruitreq"
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
            this.MsEmployeeRecruitReqModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeRecruitReqModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeRecruitReqModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeRecruitReqModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeerecruitreqTbl').datagrid('reload');
		msApp.resetForm('employeerecruitreqFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeRecruitReqModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		var ac=$('#employeerecruitreqTbl').datagrid({
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
		return '<a href="javascript:void(0)" onClick="MsEmployeeRecruitReq.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openEmployeeBudgetWindow(){
		$('#employeebudgetsearchwindow').window('open');
	}

	getEmployeeBudgetParams(){
		let params={};
		params.company_id=$('#employeebudgetsearchFrm [name=company_id]').val();
		params.designation_id=$('#employeebudgetsearchFrm [name=designation_id]').val();
		params.department_id=$('#employeebudgetsearchFrm [name=department_id]').val();
		return params;
	}

	searchEmployeeBudget(){
		let params=this.getEmployeeBudgetParams();
		let rpt = axios.get(this.route+"/getemployeebudget",{params})
		.then(function(response){
			$('#employeebudgetsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmployeeBudgetGrid(data){
		let self=this;
		var pr=$('#employeebudgetsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeerecruitreqFrm  [name=employee_budget_position_id]').val(row.id);
				$('#employeerecruitreqFrm  [name=designation_name]').val(row.designation_name);
				$('#employeerecruitreqFrm  [name=budgeted_position]').val(row.no_of_post);
				$('#employeerecruitreqFrm  [name=designation_level_id]').val(row.designation_level_id);
				$('#employeerecruitreqFrm  [name=grade]').val(row.grade);
				$('#employeerecruitreqFrm  [name=company_name]').val(row.company_name);
				$('#employeerecruitreqFrm  [name=location_name]').val(row.location_name);
				$('#employeerecruitreqFrm  [name=division_name]').val(row.division_name);
				$('#employeerecruitreqFrm  [name=department_name]').val(row.department_name);
				$('#employeerecruitreqFrm  [name=section_name]').val(row.section_name);
				$('#employeerecruitreqFrm  [name=subsection_name]').val(row.subsection_name);
				$('#employeerecruitreqFrm  [name=vacancy_available]').val(row.vacancy_available);
				$('#employeebudgetsearchwindow').window('close');
				$('#employeebudgetsearchTbl').datagrid('loadData',[]);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}

	openEmpHrWindow(){
		$('#employeehrsearchwindow').window('open');
	}

	getEmployeeParams(){
		let params={};
		params.company_id=$('#employeehrsearchFrm [name=company_id]').val();
		params.designation_id=$('#employeehrsearchFrm [name=designation_id]').val();
		params.department_id=$('#employeehrsearchFrm [name=department_id]').val();
		return params;
	}

	searchEmployeeHr(){
		let params=this.getEmployeeParams();
		let rpt = axios.get(this.route+"/getreportemployee",{params})
		.then(function(response){
			$('#employeehrsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmployeeGrid(data){
		let self=this;
		var pr=$('#employeehrsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeerecruitreqFrm  [name=employee_h_r_id]').val(row.id);
				$('#employeerecruitreqFrm  [name=employee_name]').val(row.name);
				$('#employeehrsearchwindow').window('close');
				$('#employeehrsearchTbl').datagrid('loadData',[]);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}

	// calculateVacancy(){
	// 	let self=this;
	// 	let no_of_post=$('#employeerecruitreqFrm [name=budgeted_position]').val();
	// 	let no_of_required_position=$('#employeerecruitreqFrm [name=no_of_required_position]').val();
	// 	let available_vacancy=(no_of_post-no_of_required_position)*1;
	// 	$('#employeerecruitreqFrm [name=vacancy_available]').val(available_vacancy);
	// }
	pdf(){
		var id= $('#employeerecruitreqFrm  [name=id]').val();
		if(id==""){
			alert("Select an Employee Requisition");
			return;
		}
		window.open(this.route+"/requisitionform?id="+id);
	}

}
window.MsEmployeeRecruitReq = new MsEmployeeRecruitReqController(new MsEmployeeRecruitReqModel());
MsEmployeeRecruitReq.showGrid();
MsEmployeeRecruitReq.showEmployeeBudgetGrid([]);
MsEmployeeRecruitReq.showEmployeeGrid([]);

$('#employeeRecruittabs').tabs({
	onSelect:function(title,index){
	   let employee_recruit_req_id = $('#employeerecruitreqFrm  [name=id]').val();

		var data={};
		data.employee_recruit_req_id=employee_recruit_req_id;

		if(index==1){
			if(employee_recruit_req_id===''){
				$('#employeeRecruittabs').tabs('select',0);
				msApp.showError('Select An Start Up First',0);
				return;
			}
			$('#employeerecruitreqreplaceFrm  [name=employee_recruit_req_id]').val(employee_recruit_req_id)
			MsEmployeeRecruitReqReplace.showGrid(employee_recruit_req_id);
		}

		if(index==2){
			if(employee_recruit_req_id===''){
				$('#employeeRecruittabs').tabs('select',0);
				msApp.showError('Select An Start Up First',0);
				return;
			}
			$('#employeerecruitreqjobFrm  [name=employee_recruit_req_id]').val(employee_recruit_req_id)
			MsEmployeeRecruitReqJob.showGrid(employee_recruit_req_id);
		}
	}
});