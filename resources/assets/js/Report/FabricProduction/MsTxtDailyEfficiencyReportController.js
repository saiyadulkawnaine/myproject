require('./../../datagrid-filter.js');
let MsTxtDailyEfficiencyReportModel = require('./MsTxtDailyEfficiencyReportModel');

class MsTxtDailyEfficiencyReportController {
	constructor(MsTxtDailyEfficiencyReportModel)
	{
		this.MsTxtDailyEfficiencyReportModel = MsTxtDailyEfficiencyReportModel;
		this.formId='txtdailyefficiencyreportFrm';
		this.dataTable='#txtdailyefficiencyreportTbl';
		this.route=msApp.baseUrl()+"/txtdailyefficiencyreport";
	}

	get(){
		let params={};
		params.date_to = $('#txtdailyefficiencyreportFrm  [name=date_to]').val();
		if(!params.date_to){
			alert('Select Date first');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#txtdailyefficiencyreportTblContainer').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	


	

	

	
    
	

	getMonthly(){
		let params={};
		params.date_from = $('#txtmonthlyefficiencyreportFrm  [name=txtmonthly_date_from]').val();
		params.date_to = $('#txtmonthlyefficiencyreportFrm  [name=txtmonthly_date_to]').val();
		if(!params.date_from){
			alert('Select Date first');
			return;
		}
		if(!params.date_to){
			alert('Select Date first');
			return;
		}

		let d= axios.get(this.route+'/getdatamonthly',{params})
		.then(function (response) {
			$('#txtmonthlyefficiencyreportTblContainer').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	
}
window.MsTxtDailyEfficiencyReport=new MsTxtDailyEfficiencyReportController(new MsTxtDailyEfficiencyReportModel());
