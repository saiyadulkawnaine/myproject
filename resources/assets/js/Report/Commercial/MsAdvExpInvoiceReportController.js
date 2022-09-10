let MsAdvExpInvoiceReportModel = require('./MsAdvExpInvoiceReportModel');
require('./../../datagrid-filter.js');

class MsAdvExpInvoiceReportController {
	constructor(MsAdvExpInvoiceReportModel)
	{
		this.MsAdvExpInvoiceReportModel = MsAdvExpInvoiceReportModel;
		this.formId='advexpinvoicereportFrm';
		this.dataTable='#advexpinvoicereportTbl';
		this.route=msApp.baseUrl()+"/advexpinvoicereport/getdata"
	}
	
		
	getParams(){
		let params={};
		params.company_id = $('#advexpinvoicereportFrm  [name=company_id]').val();
		params.buyer_id = $('#advexpinvoicereportFrm  [name=buyer_id]').val();
		params.lc_sc_no = $('#advexpinvoicereportFrm  [name=lc_sc_no]').val();
		params.lc_sc_date_from = $('#advexpinvoicereportFrm  [name=lc_sc_date_from]').val();
		params.lc_sc_date_to = $('#advexpinvoicereportFrm  [name=lc_sc_date_to]').val();
		params.invoice_no = $('#advexpinvoicereportFrm  [name=invoice_no]').val();
		params.invoice_date_from = $('#advexpinvoicereportFrm  [name=invoice_date_from]').val();
		params.invoice_date_to = $('#advexpinvoicereportFrm  [name=invoice_date_to]').val();
		return params;
		
	}

	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#advexpinvoicereportTbl').datagrid('loadData', response.data);
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
			nowrap:false,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var AdvInvoiceQty=0;
				var InvoiceQty=0;
				var YetToAdjQty=0;
				var AdvInvoiceAmount=0;
				var InvoiceAmount=0;
				var YetToAdjAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
					AdvInvoiceQty+=data.rows[i]['adv_invoice_qty'].replace(/,/g,'')*1;
					InvoiceQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					YetToAdjQty+=data.rows[i]['yet_to_adj_qty'].replace(/,/g,'')*1;
					AdvInvoiceAmount+=data.rows[i]['adv_invoice_amount'].replace(/,/g,'')*1;
					InvoiceAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					YetToAdjAmount+=data.rows[i]['yet_to_adj_amount'].replace(/,/g,'')*1;
					
				}
				$('#advexpinvoicereportTbl').datagrid('reloadFooter', [
					{
						adv_invoice_qty: AdvInvoiceQty.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_qty: InvoiceQty.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_to_adj_qty: YetToAdjQty.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						adv_invoice_amount: AdvInvoiceAmount.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_amount: InvoiceAmount.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						yet_to_adj_amount: YetToAdjAmount.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						
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

    openLcScWindow(){
		$('#explcscWindow').window('open');
	}

    getLcParams(){
        let params = {};
		params.lc_sc_no = $('#lcscsearchFrm [name="lc_sc_no"]').val();
		params.lc_sc_date = $('#lcscsearchFrm [name="lc_sc_date"]').val();
        return params;
    }

	searchLcScGrid(){
		let params=this.getLcParams();
		let lcsc= axios.get(msApp.baseUrl()+"/advexpinvoicereport/getexplcsc",{params})
		.then(function (response) {
			$('#lcscsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
        return lcsc;
	}

    showLcScGrid(data){
        let self = this;
		$('#lcscsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
                $('#advexpinvoicereportFrm [name=exp_lc_sc_id]').val(row.id);
                $('#advexpinvoicereportFrm [name=lc_sc_no]').val(row.lc_sc_no);
                $('#explcscWindow').window('close');
                $('#lcscsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
    }

	ordercipdf(exp_adv_invoice_id){
		window.open(msApp.baseUrl()+"/expadvinvoice/orderwiseinvoice?id="+exp_adv_invoice_id);
   	}

   	formatOrderCIPdf(value,row){
		if (row.exp_adv_invoice_id) {
			return '<a href="javascript:void(0)" onClick="MsAdvExpInvoiceReport.ordercipdf('+row.exp_adv_invoice_id+')">'+row.invoice_no+'</a>';
		}
		
	}

	invoiceWindow(exp_pi_order_id,exp_adv_invoice_id){
		let params=this.getParams();
		params.exp_pi_order_id=exp_pi_order_id;
		params.exp_adv_invoice_id=exp_adv_invoice_id;
		let idata= axios.get(msApp.baseUrl()+"/advexpinvoicereport/getinvoice",{params})
		.then(function (response) {
		    $('#invWindow').window('open');
		    $('#invoiceTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
        });
        return idata;
	}

	showInvoiceNo(data){
		var inv = $('#invoiceTbl');
		inv.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tRate=0;
				var tAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
				}
				tRate=tAmount/tQty;
				$('#invoiceTbl').datagrid('reloadFooter', [
					{ 
						invoice_qty: tQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						invoice_rate: tRate.toFixed(4).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						invoice_amount: tAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		inv.datagrid('enableFilter').datagrid('loadData', data);
	}
 
	formatInvoiceNo(value,row){
		//return '<a href="javascript:void(0)" onClick="MsAdvExpInvoiceReport.invoiceWindow('+'\''+row.exp_pi_order_id+'\''+')">'+value+'</a>';
		return '<a href="javascript:void(0)" onClick="MsAdvExpInvoiceReport.invoiceWindow('+row.exp_pi_order_id+','+row.exp_adv_invoice_id+')">'+value+'</a>';
	}

	showExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(this.route,{params})
		.then(function (response) {
			$('#advexpinvoicereportTbl').datagrid('loadData', response.data);
			$('#advexpinvoicereportTbl').datagrid('toExcel','Advance Export Invoice.xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsAdvExpInvoiceReport = new MsAdvExpInvoiceReportController(new MsAdvExpInvoiceReportModel());
MsAdvExpInvoiceReport.showGrid([]);
MsAdvExpInvoiceReport.showLcScGrid([]);
MsAdvExpInvoiceReport.showInvoiceNo([]);