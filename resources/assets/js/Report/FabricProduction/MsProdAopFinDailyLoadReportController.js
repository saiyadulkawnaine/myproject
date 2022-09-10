require('./../../datagrid-filter.js');
let MsProdAopFinDailyLoadReportModel = require('./MsProdAopFinDailyLoadReportModel');

class MsProdAopFinDailyLoadReportController {
	constructor(MsProdAopFinDailyLoadReportModel)
	{
		this.MsProdAopFinDailyLoadReportModel = MsProdAopFinDailyLoadReportModel;
		this.formId='prodaopfindailyloadreportFrm';
		this.dataTable='#prodaopfindailyloadreportTbl';
		this.route=msApp.baseUrl()+"/prodaopfindailyloadreport";
	}

	getParams(){
		let params={};
		params.date_from = $('#prodaopfindailyloadreportFrm  [name=date_from]').val();
		params.date_to = $('#prodaopfindailyloadreportFrm  [name=date_to]').val();
		params.production_process_id = $('#prodaopfindailyloadreportFrm  [name=production_process_id]').val();
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
			$('#prodaopfindailyloadreportTbl').datagrid('loadData', response.data);
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
				$('#prodaopfindailyloadreportTbl').datagrid('reloadFooter', [
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
window.MsProdAopFinDailyLoadReport=new MsProdAopFinDailyLoadReportController(new MsProdAopFinDailyLoadReportModel());
MsProdAopFinDailyLoadReport.showGrid([]);