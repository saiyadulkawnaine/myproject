require('./../../datagrid-filter.js');
let MsMonthlySewingCapacityReportModel = require('./MsMonthlySewingCapacityReportModel');

class MsMonthlySewingCapacityReportController {
	constructor(MsMonthlySewingCapacityReportModel)
	{
		this.MsMonthlySewingCapacityReportModel = MsMonthlySewingCapacityReportModel;
		this.formId='monthlysewingcapacityreportFrm';
		this.dataTable='#monthlysewingcapacityreportTbl';
		this.route=msApp.baseUrl()+"/monthlysewingcapacityreport";
	}

	get(){
		let params={};
		params.company_id = $('#monthlysewingcapacityreportFrm  [name=company_id]').val();
		params.location_id = $('#monthlysewingcapacityreportFrm  [name=location_id]').val();
		params.prod_source_id = $('#monthlysewingcapacityreportFrm  [name=prod_source_id]').val();
  params.month_from = $('#monthlysewingcapacityreportFrm  [name=month_from]').val();
  params.month_to = $('#monthlysewingcapacityreportFrm  [name=month_to]').val();
  params.year = $('#monthlysewingcapacityreportFrm  [name=year]').val();
		if(!params.month_from && !params.month_to){
			alert('Select Month Range');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#monthlysewingcapacityreportTblContainer').html(response.data);
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
window.MsMonthlySewingCapacityReport=new MsMonthlySewingCapacityReportController(new MsMonthlySewingCapacityReportModel());