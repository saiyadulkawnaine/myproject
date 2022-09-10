require('./../../datagrid-filter.js');
let MsProdDyeFinDailyLoadReportModel = require('./MsProdDyeFinDailyLoadReportModel');

class MsProdDyeFinDailyLoadReportController {
	constructor(MsProdDyeFinDailyLoadReportModel)
	{
		this.MsProdDyeFinDailyLoadReportModel = MsProdDyeFinDailyLoadReportModel;
		this.formId='proddyefindailyloadreportFrm';
		this.dataTable='#proddyefindailyloadreportTbl';
		this.route=msApp.baseUrl()+"/proddyefindailyloadreport";
	}

	getParams(){
		let params={};
		params.date_from = $('#proddyefindailyloadreportFrm  [name=date_from]').val();
		params.date_to = $('#proddyefindailyloadreportFrm  [name=date_to]').val();
		params.production_process_id = $('#proddyefindailyloadreportFrm  [name=production_process_id]').val();
		return params;
	}

	get(){
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select Date Range First');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#proddyefindailyloadreportTbl').datagrid('loadData', response.data);
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
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var roll_qty=0;

				for(var i=0; i<data.rows.length; i++){
					roll_qty+=data.rows[i]['roll_qty'].replace(/,/g,'')*1;	
				}
				$('#proddyefindailyloadreportTbl').datagrid('reloadFooter', [
					{ 
						roll_qty: roll_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	

}
window.MsProdDyeFinDailyLoadReport=new MsProdDyeFinDailyLoadReportController(new MsProdDyeFinDailyLoadReportModel());
MsProdDyeFinDailyLoadReport.showGrid([]);