let MsPlKnitReportModel = require('./MsPlKnitReportModel');
require('./../../../datagrid-filter.js');

class MsPlknitReportController {
	constructor(MsPlKnitReportModel)
	{
		this.MsPlKnitReportModel = MsPlKnitReportModel;
		this.formId='plknitreportFrm';
		this.dataTable='#plknitreportTbl';
		this.route=msApp.baseUrl()+"/plknitreport";
	}
	
	get(){
		let params={};
		params.company_id = $('#plknitreportFrm  [name=company_id]').val();
		params.location_id = $('#plknitreportFrm  [name=location_id]').val();
		params.date_from = $('#plknitreportFrm  [name=date_from]').val();
		params.date_to = $('#plknitreportFrm  [name=date_to]').val();
		if(!params.company_id){
			alert('Select Company');
			return;
		}
		if(!params.location_id){
			alert('Select Location');
			return;
		}
		if(!params.date_from){
			alert('Select Date Range');
			return;
		}
		if(!params.date_to){
			alert('Select Date Range');
			return;
		}
		let d= axios.get(this.route+"/html",{params})
		.then(function (response) {
			
			$('#plknitreportData').html(response.data);
			//$('#subinbmarketingreportTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsPlKnitReport= new MsPlknitReportController(new MsPlKnitReportModel());
