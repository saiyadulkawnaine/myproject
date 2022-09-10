//require('./../../jquery.easyui.min.js');
let MsEmployeeListModel = require('./MsEmployeeListModel');
require('./../../datagrid-filter.js');

class MsEmployeeListController {
	constructor(MsEmployeeListModel)
	{
		this.MsEmployeeListModel = MsEmployeeListModel;
		this.formId='employeelistFrm';
		this.dataTable='#employeelistTbl';
		this.route=msApp.baseUrl()+"/employeelist/getdata"
	}
	
	getParams(){
		let params={};
		params.company_id = $('#employeelistFrm  [name=company_id]').val();
		params.department_id = $('#employeelistFrm  [name=department_id]').val();
		params.name = $('#employeelistFrm  [name=name]').val();
		params.code = $('#employeelistFrm  [name=code]').val();
		params.date_from = $('#employeelistFrm  [name=date_from]').val();
		params.date_to = $('#employeelistFrm  [name=date_to]').val();
		params.status_id = $('#employeelistFrm  [name=status_id]').val();
		params.employee_type_id = $('#employeelistFrm  [name=employee_type_id]').val();
		params.designation_id = $('#employeelistFrm  [name=designation_id]').val();
		params.designation_level_id = $('#employeelistFrm  [name=designation_level_id]').val();
		params.salary_from = $('#employeelistFrm  [name=salary_from]').val();
		params.salary_to = $('#employeelistFrm  [name=salary_to]').val();
		params.location_id = $('#employeelistFrm  [name=location_id]').val();
		params.division_id = $('#employeelistFrm  [name=division_id]').val();
		params.section_id = $('#employeelistFrm  [name=section_id]').val();
		params.subsection_id = $('#employeelistFrm  [name=subsection_id]').val();
		return params;
	}
	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#employeelistTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tsalary=0;
				
				for(var i=0; i<data.rows.length; i++){
				tsalary+=data.rows[i]['salary'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						salary: tsalary.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getpdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/employeelist/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsEmployeeList.getpdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Print Pdf</span></a>';
	}

	showExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(this.route,{params})
		.then(function (response) {
			$('#employeelistTbl').datagrid('loadData', response.data);
			$('#employeelistTbl').datagrid('toExcel','Employee List.xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsEmployeeList = new MsEmployeeListController(new MsEmployeeListModel());
MsEmployeeList.showGrid([]);
