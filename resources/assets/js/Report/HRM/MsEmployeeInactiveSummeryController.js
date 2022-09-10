let MsEmployeeInactiveSummeryModel = require('./MsEmployeeInactiveSummeryModel');
require('./../../datagrid-filter.js');

class MsEmployeeInactiveSummeryController {
	constructor(MsEmployeeInactiveSummeryModel)
	{
		this.MsEmployeeInactiveSummeryModel = MsEmployeeInactiveSummeryModel;
		this.formId='employeeinactivesummeryFrm';
		this.dataTable='#employeeinactivesummeryTbl';
		this.route=msApp.baseUrl()+"/employeeinactivesummery/getdata"
	}
	
	getParams(){
		let params={};
		params.company_id = $('#employeeinactivesummeryFrm  [name=company_id]').val();
		params.date_from = $('#employeeinactivesummeryFrm  [name=date_from]').val();
		params.date_to = $('#employeeinactivesummeryFrm  [name=date_to]').val();
		params.status_id = $('#employeeinactivesummeryFrm  [name=status_id]').val();
		params.designation_level_id = $('#employeeinactivesummeryFrm  [name=designation_level_id]').val();
		params.employee_category_id = $('#employeeinactivesummeryFrm  [name=employee_category_id]').val();
		return params;
	}

	getDepartment()
	{
        let params=this.getParams();
		if( params.date_from=='' && params.date_to==''){
			alert('Please Select a date range ');
			return;
		}
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
            $('#employeeinactivesummerymatrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getSection()
	{
		let params=this.getParams();
		if( params.date_from=='' && params.date_to==''){
			alert('Please Select a date range ');
			return;
		}
		
		let d= axios.get(msApp.baseUrl()+"/employeeinactivesummery/getsectiondata",{params})
		.then(function (response) {
			$('#employeeinactivesummerymatrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getSubsection()
	{
		let params=this.getParams();
		if( params.date_from=='' && params.date_to==''){
			alert('Please Select a date range ');
			return;
		}
		
		let d= axios.get(msApp.baseUrl()+"/employeeinactivesummery/getsubsectiondata",{params})
		.then(function (response) {
			$('#employeeinactivesummerymatrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getDesignation()
	{
		let params=this.getParams();
		if( params.date_from=='' && params.date_to==''){
			alert('Please Select a date range ');
			return;
		}
		
		let d= axios.get(msApp.baseUrl()+"/employeeinactivesummery/getdesignationdata",{params})
		.then(function (response) {
			$('#employeeinactivesummerymatrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	sectionEmployeeDetailWindow(section_id){
		let params=this.getParams();
		params.section_id=section_id;
		let sd= axios.get(msApp.baseUrl()+"/employeeinactivesummery/getsectionemployee",{params})
		.then(function (response) {	
			//$('#sectionemployeewindow').window('open')
			//$('#sectionemployeeTbl').datagrid(response.data);
			let body=$('#employeedetailwindow').window('body');
			$(body).html('<table id="employeedetailTbl"></table>');
			$('#employeedetailTbl').datagrid({
				width:'100%',
				height:'100%',
				fit:true,
				//showFooter:true,
				singleSelect:true,
				idField:'id',
				rownumbers:true,
				columns:[[
					{
						field:'employee_h_r_id',
						title:'ERP ID',
						width:80,
						halign:'center',
						align:'left',
					},
					{
						field:'employee_name',
						title:'Employee Name',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'designation',
						title:'Designation',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'company',
						title:'Company',
						width:150,
						halign:'center',
						align:'left',
					},
					{
						field:'location',
						title:'Location',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'division',
						title:'Division',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'department',
						title:'Department',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'section',
						title:'Section',
						width:80,
						halign:'center',
						align:'left',
					},
					{
						field:'date_of_join',
						title:'Joining Date',
						width:70,
						halign:'center',
						align:'left',
					},
					{
						field:'status_date',
						title:'Inactive Date',
						width:70,
						halign:'center',
						align:'left',
					},
					{
						field:'discontinue_for',
						title:'Discontinue For',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'remarks',
						title:'Remarks',
						width:200,
						halign:'center',
						align:'left',
					}

				]]
			}).datagrid('enableFilter').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		$('#employeedetailwindow').window({title:'Section wise Employee Details', width:'100%'});
		$('#employeedetailwindow').window('open');
		$('#employeedetailwindow').window('center');
	}

	subSectionEmployeeDetailWindow(subsection_id){
		let params=this.getParams();
		params.subsection_id=subsection_id;
		let ssd= axios.get(msApp.baseUrl()+"/employeeinactivesummery/getsubsectionemployee",{params})
		.then(function (response) {	
			let body=$('#employeedetailwindow').window('body');
			$(body).html('<table id="employeedetailTbl"></table>');
			$('#employeedetailTbl').datagrid({
				width:'100%',
				height:'100%',
				fit:true,
				//showFooter:true,
				singleSelect:true,
				idField:'id',
				rownumbers:true,
				columns:[[
					{
						field:'employee_h_r_id',
						title:'ERP ID',
						width:80,
						halign:'center',
						align:'left',
					},
					{
						field:'employee_name',
						title:'Employee Name',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'designation',
						title:'Designation',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'company',
						title:'Company',
						width:150,
						halign:'center',
						align:'left',
					},
					{
						field:'location',
						title:'Location',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'division',
						title:'Division',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'department',
						title:'Department',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'section',
						title:'Section',
						width:80,
						halign:'center',
						align:'left',
					},
					{
						field:'date_of_join',
						title:'Joining Date',
						width:70,
						halign:'center',
						align:'left',
					},
					{
						field:'status_date',
						title:'Inactive Date',
						width:70,
						halign:'center',
						align:'left',
					},
					{
						field:'discontinue_for',
						title:'Discontinue For',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'remarks',
						title:'Remarks',
						width:200,
						halign:'center',
						align:'left',
					}
				]]
			}).datagrid('enableFilter').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		$('#employeedetailwindow').window({title:'Section wise Employee Details', width:'100%'});
		$('#employeedetailwindow').window('open');
		$('#employeedetailwindow').window('center');
	}

	departmentEmployeeDetailWindow(department_id){
		let params=this.getParams();
		params.department_id=department_id;

		let dpd= axios.get(msApp.baseUrl()+"/employeeinactivesummery/getdepartmentemployee",{params})
		.then(function (response) {	
			let body=$('#employeedetailwindow').window('body');
			$(body).html('<table id="employeedetailTbl"></table>');
			$('#employeedetailTbl').datagrid({
				width:'100%',
				height:'100%',
				fit:true,
				singleSelect:true,
				idField:'id',
				rownumbers:true,
				columns:[[
					{
						field:'employee_h_r_id',
						title:'ERP ID',
						width:80,
						halign:'center',
						align:'left',
					},
					{
						field:'employee_name',
						title:'Employee Name',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'designation',
						title:'Designation',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'company',
						title:'Company',
						width:150,
						halign:'center',
						align:'left',
					},
					{
						field:'location',
						title:'Location',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'division',
						title:'Division',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'department',
						title:'Department',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'section',
						title:'Section',
						width:80,
						halign:'center',
						align:'left',
					},
					{
						field:'date_of_join',
						title:'Joining Date',
						width:70,
						halign:'center',
						align:'left',
					},
					{
						field:'status_date',
						title:'Inactive Date',
						width:70,
						halign:'center',
						align:'left',
					},
					{
						field:'discontinue_for',
						title:'Discontinue For',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'remarks',
						title:'Remarks',
						width:200,
						halign:'center',
						align:'left',
					}
				]]
			}).datagrid('enableFilter').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		$('#employeedetailwindow').window({title:'Section wise Employee Details', width:'100%'});
		$('#employeedetailwindow').window('open');
		$('#employeedetailwindow').window('center');
	}

	designationEmployeeDetailWindow(designation_id){
		let params=this.getParams();
		params.designation_id=designation_id;
		let dsgd= axios.get(msApp.baseUrl()+"/employeeinactivesummery/getdesignationemployee",{params})
		.then(function (response) {	
			let body=$('#employeedetailwindow').window('body');
			$(body).html('<table id="employeedetailTbl"></table>');
			$('#employeedetailTbl').datagrid({
				width:'100%',
				height:'100%',
				fit:true,
				//showFooter:true,
				singleSelect:true,
				idField:'id',
				rownumbers:true,
				columns:[[
					{
						field:'employee_h_r_id',
						title:'ERP ID',
						width:80,
						halign:'center',
						align:'left',
					},
					{
						field:'employee_name',
						title:'Employee Name',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'designation',
						title:'Designation',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'company',
						title:'Company',
						width:150,
						halign:'center',
						align:'left',
					},
					{
						field:'location',
						title:'Location',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'division',
						title:'Division',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'department',
						title:'Department',
						width:200,
						halign:'center',
						align:'left',
					},
					{
						field:'section',
						title:'Section',
						width:80,
						halign:'center',
						align:'left',
					},
					{
						field:'date_of_join',
						title:'Joining Date',
						width:70,
						halign:'center',
						align:'left',
					},
					{
						field:'status_date',
						title:'Inactive Date',
						width:70,
						halign:'center',
						align:'left',
					},
					{
						field:'discontinue_for',
						title:'Discontinue For',
						width:100,
						halign:'center',
						align:'left',
					},
					{
						field:'remarks',
						title:'Remarks',
						width:200,
						halign:'center',
						align:'left',
					}
				]]
			}).datagrid('enableFilter').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		$('#employeedetailwindow').window({title:'Designation wise Employee Details', width:'100%'});
		$('#employeedetailwindow').window('open');
		$('#employeedetailwindow').window('center');
	}


	// showExcel(table_id,file_name){
	// 	let params=this.getParams();
	// 	let e= axios.get(this.route,{params})
	// 	.then(function (response) {
	// 		$('#employeeinactivesummeryTbl').datagrid('loadData', response.data);
	// 		$('#employeeinactivesummeryTbl').datagrid('toExcel','Employee List.xls');
	// 		//msApp.toExcel(table_id,file_name);
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

}
window.MsEmployeeInactiveSummery = new MsEmployeeInactiveSummeryController(new MsEmployeeInactiveSummeryModel());