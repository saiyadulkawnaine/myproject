require('./../../datagrid-filter.js');
let MsAssetRepairBackReportModel = require('./MsAssetRepairBackReportModel');

class MsAssetRepairBackReportController {
	constructor(MsAssetRepairBackReportModel) {
		this.MsAssetRepairBackReportModel = MsAssetRepairBackReportModel;
		this.formId = 'assetrepairbackreportFrm';
		this.dataTable = '#assetrepairbackreportTbl';
		this.route = msApp.baseUrl() + "/assetrepairbackreport";
	}

	get() {
		let params = {};
		// params.company_id = $('#assetrepairbackreportFrm  [name=company_id]').val();
		params.date_from = $('#assetrepairbackreportFrm [name=date_from]').val();
		params.date_to = $('#assetrepairbackreportFrm [name=date_to]').val();
		// params.location_id = $('#assetrepairbackreportFrm  [name=location_id]').val();
		// params.asset_id = $('#assetrepairbackreportFrm  [name=asset_id]').val();
		// params.type_id = $('#assetrepairbackreportFrm  [name=type_id]').val();
		// params.production_area_id = $('#assetrepairbackreportFrm  [name=production_area_id]').val();
		if (!params.date_from && !params.date_to) {
			alert('Select Date Range');
			return;
		}

		let d = axios.get(this.route + '/getdata', {
				params
			})
			.then(function (response) {
				$('#assetrepairbackreportTblContainer').html(response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
	}

	resetForm() {
		msApp.resetForm(this.formId);
	}

}
window.MsAssetRepairBackReport = new MsAssetRepairBackReportController(new MsAssetRepairBackReportModel());