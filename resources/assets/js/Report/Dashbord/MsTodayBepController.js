let MsTodayBepModel = require('./MsTodayBepModel');
require('./../../datagrid-filter.js');

class MsTodayBepController {
	constructor(MsTodayBepModel)
	{
		this.MsTodayBepModel = MsTodayBepModel;
		this.formId='todaybepFrm';
		this.dataTable='#todaybepTbl';
		this.route=msApp.baseUrl()+"/todaybep/getdata"
	}
	
	get(){
		let params={};
		params.company_id = $('#todaybepFrm  [name=company_id]').val();
		params.bep_date_from = $('#todaybepFrm  [name=bep_date_from]').val();
		params.bep_date_to = $('#todaybepFrm  [name=bep_date_to]').val();
		if(params.company_id==''){
			alert('Please Select Company');
			return;
		}

		if(params.bep_date_from==''){
			alert('Please Insert Date From');
			return;
		}
		if(params.bep_date_to==''){
			alert('Please Insert Date To');
			return;
		}
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#todaybepdata').html(response.data);
			//$('#todayShipmentTbl').datagrid('loadData', response.data);
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
window.MsTodayBep=new MsTodayBepController(new MsTodayBepModel());