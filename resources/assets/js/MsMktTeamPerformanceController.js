let MsMktTeamPerformanceModel = require('./MsMktTeamPerformanceModel');
require('./datagrid-filter.js');

class MsMktTeamPerformanceController {
	constructor(MsMktTeamPerformanceModel)
	{
		this.MsMktTeamPerformanceModel = MsMktTeamPerformanceModel;
		this.formId='mktteamperformanceFrm';
		this.dataTable='#mktteamperformanceTbl';
		this.route=msApp.baseUrl()+"/mktteamperformance"
	}
	getParams(){
		let params={};
		params.date_from = $('#mktteamperformanceFrm  [name=date_from]').val();
		params.date_to = $('#mktteamperformanceFrm  [name=date_to]').val();
		params.order_status = $('#mktteamperformanceFrm  [name=order_status]').val();
		params.receive_date_from = $('#mktteamperformanceFrm  [name=receive_date_from]').val();
		params.receive_date_to = $('#mktteamperformanceFrm  [name=receive_date_to]').val();
		params.sort_by = $('#mktteamperformanceFrm  [name=sort_by]').val();
		return params;
	}
	
	get()
	{
		$('#mktteamperformanceTab').tabs('select',0);
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			//$('#mktteamperformanceTbl').datagrid('loadData', response.data);
            $('#mktteamperformancematrix').html(response.data);
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
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#mktteamperformanceTbl').datagrid('loadData', response.data);
			msApp.toExcel(table_id,file_name);
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
window.MsMktTeamPerformance=new MsMktTeamPerformanceController(new MsMktTeamPerformanceModel());
//MsMktTeamPerformance.showGrid([]);
MsMktTeamPerformance.showGrid({rows :{}});