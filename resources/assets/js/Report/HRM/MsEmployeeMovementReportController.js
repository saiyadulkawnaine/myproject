//require('./../../jquery.easyui.min.js');
let MsEmployeeMovementReportModel = require('./MsEmployeeMovementReportModel');
require('./../../datagrid-filter.js');

class MsEmployeeMovementReportController {
	constructor(MsEmployeeMovementReportModel)
	{
		this.MsEmployeeMovementReportModel = MsEmployeeMovementReportModel;
		this.formId='employeemovementreportFrm';
		this.dataTable='#employeemovementreportTbl';
		this.route=msApp.baseUrl()+"/employeemovementreport/getdata"
	}
	
	get(){
		let params={};
		params.date_from = $('#employeemovementreportFrm  [name=date_from]').val();
        params.date_to = $('#employeemovementreportFrm  [name=date_to]').val();
        params.company_id = $('#employeemovementreportFrm  [name=company_id]').val();
        params.department_id = $('#employeemovementreportFrm  [name=department_id]').val();
        params.designation_id = $('#employeemovementreportFrm  [name=designation_id]').val();
        //let params=this.getParams();
        if(!params.date_from && !params.date_to){
			alert('Select A Date Range First ');
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
            //$('#employeemovementreportmatrix').html(response.data);
			$('#employeemovementreportTbl').datagrid('loadData', response.data);
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
            nowrap:false,
			emptyMsg:'No Record Found',
			// onLoadSuccess: function(data){
			// 	var total_out_this_month=0;

			// 	for(var i=0; i<data.rows.length; i++){
					
			// 		total_out_this_month+=data.rows[i]['total_out_this_month'].replace(/,/g,'')*1;
			// 	}			
			// 	$(this.dataTable).datagrid('reloadFooter', [
			// 		{ 
			// 			total_out_this_month: total_out_this_month.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
			// 		}
			// 	]);
				
			// }
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getDepartment(){
		let params={};
		params.date_from = $('#employeemovementreportFrm  [name=date_from]').val();
        params.date_to = $('#employeemovementreportFrm  [name=date_to]').val();
        params.department_id = $('#employeemovementreportFrm  [name=department_id]').val();
        params.designation_id = $('#employeemovementreportFrm  [name=designation_id]').val();
		params.company_id = $('#employeemovementreportFrm  [name=company_id]').val();
        //let params=this.getParams();
        if(!params.date_from && !params.date_to){
			alert('Select A Date Range First ');
			return;
		}
		let dep= axios.get(msApp.baseUrl()+"/employeemovementreport/getdeparmentwise",{params})
		.then(function (response) {
            $('#departmentWiseWindow').window('open');
			$('#departmentwiseReportTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return dep;
	}

	showGridDepartment(data){
		var claim = $('#departmentwiseReportTbl');
		claim.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var NoOfEmployee=0;

			for(var i=0; i<data.rows.length; i++){
				NoOfEmployee+=data.rows[i]['no_of_employee'].replace(/,/g,'')*1;
			}
			$('#departmentwiseReportTbl').datagrid('reloadFooter', [
			{
				no_of_employee: NoOfEmployee.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			}
			]);
		}

		});
		claim.datagrid('enableFilter').datagrid('loadData', data);
	}

	DepEmpWindow(department_id){
		let params={};
		params.date_from = $('#employeemovementreportFrm  [name=date_from]').val();
		params.date_to = $('#employeemovementreportFrm  [name=date_to]').val();
		params.designation_id = $('#employeemovementreportFrm  [name=designation_id]').val();
		params.company_id = $('#employeemovementreportFrm  [name=company_id]').val();
		params.department_id=department_id;

		let data= axios.get(msApp.baseUrl()+"/employeemovementreport/getdepemp",{params})
		.then(function (response) {
		    $('#depempTbl').datagrid('loadData', response.data);
		    $('#depemployeeWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
        });
        return data;
	}

	showDepEmp(data){
		var rc = $('#depempTbl');
		rc.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			
		});
		rc.datagrid('enableFilter').datagrid('loadData', data);
	}
 
	formatDepEmp(value,row){
		return '<a href="javascript:void(0)" onClick="MsEmployeeMovementReport.DepEmpWindow('+'\''+row.department_id+'\''+')">'+row.no_of_employee+'</a>';
	}

}
window.MsEmployeeMovementReport = new MsEmployeeMovementReportController(new MsEmployeeMovementReportModel());
MsEmployeeMovementReport.showGrid([]);
MsEmployeeMovementReport.showGridDepartment([]);
MsEmployeeMovementReport.showDepEmp([]);