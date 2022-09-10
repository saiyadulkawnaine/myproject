require('./../../datagrid-filter.js');
let MsTargetAchievementReportModel = require('./MsTargetAchievementReportModel');

class MsTargetAchievementReportController {
	constructor(MsTargetAchievementReportModel)
	{
		this.MsTargetAchievementReportModel = MsTargetAchievementReportModel;
		this.formId='targetachievementreportFrm';
		this.dataTable='#targetachievementreportTbl';
		this.route=msApp.baseUrl()+"/targetachievementreport";
	}

	get(){
		let params={};
		params.date_from = $('#targetachievementreportFrm  [name=tgtach_date_from]').val();
		params.date_to = $('#targetachievementreportFrm  [name=tgtach_date_to]').val();
		if(!params.date_to){
			alert('Select Date first');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#targetachievementreportTblContainer').html(response.data);
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
window.MsTargetAchievementReport=new MsTargetAchievementReportController(new MsTargetAchievementReportModel());
