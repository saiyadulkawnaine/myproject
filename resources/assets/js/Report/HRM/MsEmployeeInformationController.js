//require('./../../jquery.easyui.min.js');
let MsEmployeeInformationModel = require('./MsEmployeeInformationModel');
require('./../../datagrid-filter.js');

class MsEmployeeInformationController {
	constructor(MsEmployeeInformationModel)
	{
		this.MsEmployeeInformationModel = MsEmployeeInformationModel;
		this.formId='employeeinformationFrm';
		this.dataTable='#employeeinformationTbl';
		this.route=msApp.baseUrl()+"/employeeinformation/getdata"
	}
	
	getParams(){
		let params={};
		params.company_id = $('#employeeinformationFrm  [name=company_id]').val();
		params.department_id = $('#employeeinformationFrm  [name=department_id]').val();
		params.name = $('#employeeinformationFrm  [name=name]').val();
		params.code = $('#employeeinformationFrm  [name=code]').val();
		params.date_from = $('#employeeinformationFrm  [name=date_from]').val();
		params.date_to = $('#employeeinformationFrm  [name=date_to]').val();
		params.status_id = $('#employeeinformationFrm  [name=status_id]').val();
		params.employee_type_id = $('#employeeinformationFrm  [name=employee_type_id]').val();
		return params;
	}
	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#employeeinformationTbl').datagrid('loadData', response.data);
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
		window.open(msApp.baseUrl()+"/employeeinformation/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsEmployeeInformation.getpdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Print Pdf</span></a>';
	}

	showExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(this.route,{params})
		.then(function (response) {
			$('#employeeinformationTbl').datagrid('loadData', response.data);
			$('#employeeinformationTbl').datagrid('toExcel','Employee List.xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsEmployeeInformation = new MsEmployeeInformationController(new MsEmployeeInformationModel());
MsEmployeeInformation.showGrid([]);
