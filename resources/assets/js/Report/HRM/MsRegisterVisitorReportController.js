let MsRegisterVisitorReportModel = require('./MsRegisterVisitorReportModel');
require('./../../datagrid-filter.js');

class MsRegisterVisitorReportController {
	constructor(MsRegisterVisitorReportModel)
	{
		this.MsRegisterVisitorReportModel = MsRegisterVisitorReportModel;
		this.formId='registervisitorreportFrm';
		this.dataTable='#registervisitorreportTbl';
		this.route=msApp.baseUrl()+"/registervisitorreport/getdata"
	}
	
	get(){
		let params={};
		params.user_id = $('#registervisitorreportFrm  [name=user_id]').val();
		params.name = $('#registervisitorreportFrm  [name=name]').val();
		params.date_from = $('#registervisitorreportFrm  [name=date_from]').val();
		params.date_to = $('#registervisitorreportFrm  [name=date_to]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#registervisitorreportTbl').datagrid('loadData', response.data);
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
			emptyMsg:'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	// getpdf(id){
	// 	if(id==""){
	// 		return;
	// 	}
	// 	window.open(msApp.baseUrl()+"/registervisitorreport/report?id="+id);
	// }
	
	// formatpdf(value,row)
	// {		
	// 	return '<a href="javascript:void(0)" onClick="MsRegisterVisitorReport.getpdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Print Pdf</span></a>';
	// }

}
window.MsRegisterVisitorReport = new MsRegisterVisitorReportController(new MsRegisterVisitorReportModel());
MsRegisterVisitorReport.showGrid([]);