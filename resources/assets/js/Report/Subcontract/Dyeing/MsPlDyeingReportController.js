let MsPlDyeingReportModel = require('./MsPlDyeingReportModel');
require('./../../../datagrid-filter.js');

class MsPlDyeingReportController {
	constructor(MsPlDyeingReportModel)
	{
		this.MsPlDyeingReportModel = MsPlDyeingReportModel;
		this.formId='pldyeingreportFrm';
		this.dataTable='#pldyeingreportTbl';
		this.route=msApp.baseUrl()+"/pldyeingreport";
	}
	
	get(){
		let params={};
		params.company_id = $('#pldyeingreportFrm  [name=company_id]').val();
		params.location_id = $('#pldyeingreportFrm  [name=location_id]').val();
		params.date_from = $('#pldyeingreportFrm  [name=date_from]').val();
		params.date_to = $('#pldyeingreportFrm  [name=date_to]').val();
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
			
			$('#pldyeingreportData').html(response.data);
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
window.MsPlDyeingReport= new MsPlDyeingReportController(new MsPlDyeingReportModel());
