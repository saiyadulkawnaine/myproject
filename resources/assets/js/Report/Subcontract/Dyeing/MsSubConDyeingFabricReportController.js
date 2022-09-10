require('./../../../datagrid-filter.js');
let MsSubConDyeingFabricReportModel = require('./MsSubConDyeingFabricReportModel');

class MsSubConDyeingFabricReportController {
	constructor(MsSubConDyeingFabricReportModel)
	{
		this.MsSubConDyeingFabricReportModel = MsSubConDyeingFabricReportModel;
		this.formId='subcondyeingfabricreportFrm';
		this.dataTable='#subcondyeingfabricreportTbl';
		this.route=msApp.baseUrl()+"/subcondyeingfabricreport";
	}
	
	get(){
		let params={};
		params.company_id = $('#subcondyeingfabricreportFrm  [name=company_id]').val();
		params.buyer_id = $('#subcondyeingfabricreportFrm  [name=buyer_id]').val();
		params.sales_order_no = $('#subcondyeingfabricreportFrm  [name=sales_order_no]').val();
		params.rcv_date_from = $('#subcondyeingfabricreportFrm  [name=rcv_date_from]').val();
		params.rcv_date_to = $('#subcondyeingfabricreportFrm  [name=rcv_date_to]').val();
		params.dlv_date_from = $('#subcondyeingfabricreportFrm  [name=dlv_date_from]').val();
		params.dlv_date_to = $('#subcondyeingfabricreportFrm  [name=dlv_date_to]').val();

		let d= axios.get(this.route+"/getdata",{params})
		.then(function (response) {
			$('#subcondyeingfabricreportTbl').datagrid('loadData', response.data);
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
				var qty=0;
				var amount=0;
                var rate=0;
				var grey_rcv_qty=0;
				var batch_qty=0;
				var batch_wip=0;
				var batch_bal=0;
				var dyeing_qty=0;
				var dyeing_wip=0;
				var dyeing_bal=0;
				var fin_qty=0;
				var fin_wip=0;
				var fin_bal=0;
				var dlv_qty=0;
				var grey_used=0;
				var dlv_wip=0;
				var dlv_bal=0;
				var bill_value=0;
				var bill_value_bal=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					grey_rcv_qty+=data.rows[i]['grey_rcv_qty'].replace(/,/g,'')*1;
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
					batch_wip+=data.rows[i]['batch_wip'].replace(/,/g,'')*1;
					batch_bal+=data.rows[i]['batch_bal'].replace(/,/g,'')*1;
					dyeing_qty+=data.rows[i]['dyeing_qty'].replace(/,/g,'')*1;
					dyeing_wip+=data.rows[i]['dyeing_wip'].replace(/,/g,'')*1;
					dyeing_bal+=data.rows[i]['dyeing_bal'].replace(/,/g,'')*1;
					fin_qty+=data.rows[i]['fin_qty'].replace(/,/g,'')*1;
					fin_wip+=data.rows[i]['fin_wip'].replace(/,/g,'')*1;
					fin_bal+=data.rows[i]['fin_bal'].replace(/,/g,'')*1;
					dlv_qty+=data.rows[i]['dlv_qty'].replace(/,/g,'')*1;
					grey_used+=data.rows[i]['grey_used'].replace(/,/g,'')*1;
					dlv_wip+=data.rows[i]['dlv_wip'].replace(/,/g,'')*1;
					dlv_bal+=data.rows[i]['dlv_bal'].replace(/,/g,'')*1;
					bill_value+=data.rows[i]['bill_value'].replace(/,/g,'')*1;
					bill_value_bal+=data.rows[i]['bill_value_bal'].replace(/,/g,'')*1;
				}
				
				if (qty) {
					rate=amount/qty;	
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_rcv_qty: grey_rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_wip: batch_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					batch_bal: batch_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_qty: dyeing_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_wip: dyeing_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_bal: dyeing_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fin_qty: fin_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fin_wip: fin_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fin_bal: fin_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dlv_qty: dlv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_used: grey_used.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dlv_wip: dlv_wip.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dlv_bal: dlv_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bill_value: bill_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bill_value_bal: bill_value_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
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
window.MsSubConDyeingFabricReport= new MsSubConDyeingFabricReportController(new MsSubConDyeingFabricReportModel());
MsSubConDyeingFabricReport.showGrid([]);