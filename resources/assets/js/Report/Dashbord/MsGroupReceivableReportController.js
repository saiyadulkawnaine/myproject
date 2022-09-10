let MsGroupReceivableReportModel = require('./MsGroupReceivableReportModel');
require('./../../datagrid-filter.js');
class MsGroupReceivableReportController {
	constructor(MsGroupReceivableReportModel)
	{
		this.MsGroupReceivableReportModel = MsGroupReceivableReportModel;
		this.formId='groupreceivableFrm';
		this.dataTable='#groupreceivableTbl';
		this.route=msApp.baseUrl()+"/groupreceivables"
	}
	
	get(){
		let params={};
		params.date_to = $('#groupreceivableFrm  [name=date_to]').val();
		
		if(params.date_to==''){
			alert('Please Select Date Range')
			return;
		}
		let d= axios.get(this.route+'/html',{params})
		.then(function (response) {
			$('#groupreceivablecontainer').html(response.data)
			//$('#pendingShipmentTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getBuyerDelails(date_to,acc_id,company_id){
		let params={};
		params.date_to=date_to;
		params.acc_id=acc_id;
		params.company_id=company_id;
		let d= axios.get(this.route+'/getbuyerdetails',{params})
		.then(function (response) {
			//$('#groupslaehwindowcontainerlayoutcenter').html(response.data)
			$('#groupreceivablebuyerTbl').datagrid('loadData', response.data);
			$('#groupreceivablebuyerWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	buyerDetailGrid(data)
	{
		var dg = $('#groupreceivablebuyerTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var amount=0;
				for(var i=0; i<data.rows.length; i++){
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				
				$('#groupreceivablebuyerTbl').datagrid('reloadFooter', [
					{ 
						amount: amount.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsGroupReceivableReport=new MsGroupReceivableReportController(new MsGroupReceivableReportModel());
MsGroupReceivableReport.buyerDetailGrid([]);
/*MsGroupReceivableReport.dyeingsubGrid([]);
MsGroupReceivableReport.aopinhGrid([]);
MsGroupReceivableReport.aopsubGrid([]);
MsGroupReceivableReport.knitinginhGrid([]);
MsGroupReceivableReport.knitingsubGrid([]);
MsGroupReceivableReport.gmtGrid([]);*/
