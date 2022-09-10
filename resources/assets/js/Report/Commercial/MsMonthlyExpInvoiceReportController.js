let MsMonthlyExpInvoiceReportModel = require('./MsMonthlyExpInvoiceReportModel');
require('./../../datagrid-filter.js');

class MsMonthlyExpInvoiceReportController {
	constructor(MsMonthlyExpInvoiceReportModel)
	{
		this.MsMonthlyExpInvoiceReportModel = MsMonthlyExpInvoiceReportModel;
		this.formId='monthlyexpinvoicereportFrm';
		this.dataTable='#monthlyexpinvoicereportTbl';
		this.route=msApp.baseUrl()+"/monthlyexpinvoicereport/getdata"
	}
	
		
	getParams(){
		let params={};
		params.company_id = $('#monthlyexpinvoicereportFrm  [name=company_id]').val();
		params.buyer_id = $('#monthlyexpinvoicereportFrm  [name=buyer_id]').val();
		params.lc_sc_no = $('#monthlyexpinvoicereportFrm  [name=lc_sc_no]').val();
		params.lc_sc_date_from = $('#monthlyexpinvoicereportFrm  [name=lc_sc_date_from]').val();
		params.lc_sc_date_to = $('#monthlyexpinvoicereportFrm  [name=lc_sc_date_to]').val();
		params.invoice_no = $('#monthlyexpinvoicereportFrm  [name=invoice_no]').val();
		params.invoice_date_from = $('#monthlyexpinvoicereportFrm  [name=invoice_date_from]').val();
		params.invoice_date_to = $('#monthlyexpinvoicereportFrm  [name=invoice_date_to]').val();
		params.invoice_status_id = $('#monthlyexpinvoicereportFrm  [name=invoice_status_id]').val();
		params.exporter_bank_branch_id = $('#monthlyexpinvoicereportFrm  [name=exporter_bank_branch_id]').val();
		params.ex_factory_date_from = $('#monthlyexpinvoicereportFrm  [name=ex_factory_date_from]').val();
		params.ex_factory_date_to = $('#monthlyexpinvoicereportFrm  [name=ex_factory_date_to]').val();
		return params;
		
	}

	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#monthlyexpinvoicereportTbl').datagrid('loadData', response.data.details);
			$('#monthlyexpinvoicereportMonthTbl').datagrid('loadData', response.data.month);
			$('#monthlyexpinvoicereportBuyerTbl').datagrid('loadData', response.data.buyer);
			$('#monthlyexpinvoicereportCompanyTbl').datagrid('loadData', response.data.company);
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
				var invoice_qty=0;
				var invoice_amount=0;
				
				for(var i=0; i<data.rows.length; i++){
					invoice_qty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					invoice_amount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					
				}
				$('#monthlyexpinvoicereportTbl').datagrid('reloadFooter', [
					{
						invoice_qty: invoice_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_amount: invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);//
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}


	showMonthGrid(data)
	{
		var dg = $('#monthlyexpinvoicereportMonthTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var invoice_qty=0;
				var invoice_amount=0;
				var no_of_invoice=0;
				var net_invoice_amount=0;
				
				for(var i=0; i<data.rows.length; i++){
					invoice_qty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					invoice_amount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					net_invoice_amount+=data.rows[i]['net_invoice_amount'].replace(/,/g,'')*1;
					no_of_invoice+=data.rows[i]['no_of_invoice'].replace(/,/g,'')*1;
					
				}
				$('#monthlyexpinvoicereportMonthTbl').datagrid('reloadFooter', [
					{
						invoice_qty: invoice_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_amount: invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						net_invoice_amount: net_invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						no_of_invoice: no_of_invoice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);//
	}

	showBuyerGrid(data)
	{
		var dg = $('#monthlyexpinvoicereportBuyerTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var invoice_qty=0;
				var invoice_amount=0;
				var no_of_invoice=0;
				var net_invoice_amount=0;
				
				for(var i=0; i<data.rows.length; i++){
					invoice_qty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					invoice_amount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					net_invoice_amount+=data.rows[i]['net_invoice_amount'].replace(/,/g,'')*1;
					no_of_invoice+=data.rows[i]['no_of_invoice'].replace(/,/g,'')*1;
					
				}
				$('#monthlyexpinvoicereportBuyerTbl').datagrid('reloadFooter', [
					{
						invoice_qty: invoice_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_amount: invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						net_invoice_amount: net_invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						no_of_invoice: no_of_invoice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);//
	}

	showCompanyGrid(data)
	{
		var dg = $('#monthlyexpinvoicereportCompanyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var invoice_qty=0;
				var invoice_amount=0;
				var no_of_invoice=0;
				var net_invoice_amount=0;
				
				for(var i=0; i<data.rows.length; i++){
					invoice_qty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					invoice_amount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					net_invoice_amount+=data.rows[i]['net_invoice_amount'].replace(/,/g,'')*1;
					no_of_invoice+=data.rows[i]['no_of_invoice'].replace(/,/g,'')*1;
					
				}
				$('#monthlyexpinvoicereportCompanyTbl').datagrid('reloadFooter', [
					{
						invoice_qty: invoice_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_amount: invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						net_invoice_amount: net_invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						no_of_invoice: no_of_invoice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);//
	}

	ordercipdf(invoice_id){
		window.open(msApp.baseUrl()+"/expinvoice/orderwiseinvoice?id="+invoice_id);
   	}

   	formatOrderCIPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsMonthlyExpInvoiceReport.ordercipdf('+row.invoice_id+')">'+row.invoice_no+'</a>';
	}

	getInvoiceWise()
	{
		let params=this.getParams();
		let e= axios.get(msApp.baseUrl()+"/monthlyexpinvoicereport/getinvoicedata",{params})
		.then(function (response) {
			$('#invoicewisereportWindow').window('open');
			$('#invoicewisereportTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showInvoiceWiseGrid(data)
	{
		var dgiw = $('#invoicewisereportTbl');
		dgiw.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var invoice_qty=0;
				var invoice_amount=0;
				var net_invoice_amount=0;
				
				for(var i=0; i<data.rows.length; i++){
					invoice_qty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					invoice_amount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					net_invoice_amount+=data.rows[i]['net_invoice_amount'].replace(/,/g,'')*1;

				}
				$('#invoicewisereportTbl').datagrid('reloadFooter', [
					{
						invoice_qty: invoice_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_amount: invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						net_invoice_amount: net_invoice_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dgiw.datagrid('enableFilter').datagrid('loadData', data);//
	}
	
}
window.MsMonthlyExpInvoiceReport = new MsMonthlyExpInvoiceReportController(new MsMonthlyExpInvoiceReportModel());
MsMonthlyExpInvoiceReport.showGrid([]);
MsMonthlyExpInvoiceReport.showMonthGrid([]);
MsMonthlyExpInvoiceReport.showBuyerGrid([]);
MsMonthlyExpInvoiceReport.showCompanyGrid([]);
MsMonthlyExpInvoiceReport.showInvoiceWiseGrid([]);