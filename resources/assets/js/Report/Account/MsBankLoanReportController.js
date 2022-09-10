let MsBankLoanReportModel = require('./MsBankLoanReportModel');
require('./../../datagrid-filter.js');
class MsBankLoanReportController {
	constructor(MsBankLoanReportModel)
	{
		this.MsBankLoanReportModel = MsBankLoanReportModel;
		this.formId='bankloanreportFrm';
		this.dataTable='#bankloanreportTbl';
		this.route=msApp.baseUrl()+"/bankloanreport";
	}

	get(){
		let params={};
		params.date_to = $('#bankloanreportFrm  [name=date_to]').val();
		params.company_id = $('#bankloanreportFrm  [name=company_id]').val();
		params.bank_id = $('#bankloanreportFrm  [name=bank_id]').val();
		if (params.date_to ==""){
			alert('Select Date First');
			return;
		}
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#bankloanreportcontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}
}
window.MsBankLoanReport=new MsBankLoanReportController(new MsBankLoanReportModel());
