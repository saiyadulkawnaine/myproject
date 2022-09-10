let MsPlKnitExiReportModel = require('./MsPlKnitExiReportModel');
require('./../../../datagrid-filter.js');

class MsPlKnitExiReportController {
	constructor(MsPlKnitExiReportModel)
	{
		this.MsPlKnitExiReportModel = MsPlKnitExiReportModel;
		this.formId='plknitexireportFrm';
		this.dataTable='#plknitexireportTbl';
		this.route=msApp.baseUrl()+"/plknitexireport";
	}
	
	get(){
		let params={};
		params.company_id = $('#plknitexireportFrm  [name=company_id]').val();
		//params.location_id = $('#plknitexireportFrm  [name=location_id]').val();
		params.date_from = $('#plknitexireportFrm  [name=date_from]').val();
		params.date_to = $('#plknitexireportFrm  [name=date_to]').val();
		if(!params.company_id){
			alert('Select Company');
			return;
		}
		/*if(!params.location_id){
			alert('Select Location');
			return;
		}*/
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
			
			//$('#plknitreportData').html(response.data);
			$('#plknitexireportTbl').datagrid('loadData', response.data);
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
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var prod_qty=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					prod_qty+=data.rows[i]['prod_qty'].replace(/,/g,'')*1;
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prod_qty: prod_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
window.MsPlKnitExiReport= new MsPlKnitExiReportController(new MsPlKnitExiReportModel());
MsPlKnitExiReport.showGrid([]);