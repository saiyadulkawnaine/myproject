let MsRenewalReportModel = require('./MsRenewalReportModel');
require('./../../datagrid-filter.js');

class MsRenewalReportController {
	constructor(MsRenewalReportModel)
	{
		this.MsRenewalReportModel = MsRenewalReportModel;
		this.formId='renewalreportFrm';
		this.dataTable='#renewalreportTbl';
		this.route=msApp.baseUrl()+"/renewalreport"
	}

	get()
	{
		let params={};
		params.company_id = $('#renewalreportFrm  [name=company_id]').val();
		params.renewal_item_id = $('#renewalreportFrm  [name=renewal_item_id]').val();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#renewentrymatrix').html(response.data);
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
				/* for(var i=0;i<data.rows.length;i++){
					//
				} */
				/* $(this).datagrid('reloadFooter', [
				{ pdf: ' '}
				]); */
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getpdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/renewalreport/report?id="+id);
	}
	
	renewalEntryRemarksWindow(renewal_item_id,company_id){
		
		let d= axios.get(this.route+"/getrenewremarks?renewal_item_id="+renewal_item_id+"&company_id="+company_id)
		.then(function (response) {
			// $('#renewalWindowContainer').html(response.data);
			// $('#renewalentryFrm [name=company_id]').val(company_id);
			// $('#renewalentryFrm [name=renewal_item_id]').val(renewal_item_id);
			//$.parser.parse('#containerDocWindow');
			$('#renewalEntryRemarksWindow').window('open');
			$('#remarkTbl').datagrid('loadData',response.data);
			
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showRemarkGrid(data)
	{
		var rk = $('#remarkTbl');
		rk.datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			columns:[[
				{
					field:'remarks',
					title:'Remarks',
					width:500,
					halign:'center',
				}
			]]
		});
		rk.datagrid('enableFilter').datagrid('loadData', data);
	}


}
window.MsRenewalReport = new MsRenewalReportController(new MsRenewalReportModel());
MsRenewalReport.get();
MsRenewalReport.showRemarkGrid([]);
