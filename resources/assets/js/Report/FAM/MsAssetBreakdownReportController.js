let MsAssetBreakdownReportModel = require('./MsAssetBreakdownReportModel');
require('./../../datagrid-filter.js');

class MsAssetBreakdownReportController {
	constructor(MsAssetBreakdownReportModel)
	{
		this.MsAssetBreakdownReportModel = MsAssetBreakdownReportModel;
		this.formId='assetbreakdownreportFrm';
		this.dataTable='#assetbreakdownreportTbl';
		this.route=msApp.baseUrl()+"/assetbreakdownreport/getdata"
	}
	
	get(){
		let params={};
		params.date_from = $('#assetbreakdownreportFrm  [name=date_from]').val();
		params.date_to = $('#assetbreakdownreportFrm  [name=date_to]').val();
		params.company_id = $('#assetbreakdownreportFrm  [name=company_id]').val();
		params.reason_id = $('#assetbreakdownreportFrm  [name=reason_id]').val();
		params.production_area_id = $('#assetbreakdownreportFrm  [name=production_area_id]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {

			$('#assetbreakdownreportTbl').datagrid('loadData', response.data);
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
			singleSelect:false,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var totalHr=0;
				var pendingHr=0;
				for(var i=0; i<data.rows.length; i++){
					totalHr+=data.rows[i]['total_breakdown_hour'].replace(/,/g,'')*1;
					pendingHr+=data.rows[i]['pending_breakdown_hour'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [{
					total_breakdown_hour: totalHr.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pending_breakdown_hour: pendingHr.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
	

	purchaserequisitionWindow(id)
	{
		//$('#openpurchaserequisitionwindow').window('open');	
		// let data= axios.get(msApp.baseUrl()+"/assetbreakdownreport/getpurchaserequisition?id="+id);
		// data.then(function (response) {
		// 	$('#containerRqWindow').html(response.data);	    
		// })
		// .catch(function (error) {
		// 	console.log(error);
		// });

		window.open(msApp.baseUrl()+"/assetbreakdownreport/getpurchaserequisition?id="+id);
	}

	formatpurchaserequisition(value,row)
	{
		if (row.inv_pur_req_asset_id) {
			return '<a href="javascript:void(0)" onClick="MsAssetBreakdownReport.purchaserequisitionWindow('+row.id+')">'+row.reason_id+'</a>';	
		}else{
			return row.reason_id;
		}
		
	}

}
window.MsAssetBreakdownReport = new MsAssetBreakdownReportController(new MsAssetBreakdownReportModel());
MsAssetBreakdownReport.showGrid([]);