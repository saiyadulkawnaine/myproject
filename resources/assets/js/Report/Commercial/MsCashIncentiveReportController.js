let MsCashIncentiveReportModel = require('./MsCashIncentiveReportModel');
require('./../../datagrid-filter.js');

class MsCashIncentiveReportController {
	constructor(MsCashIncentiveReportModel)
	{
		this.MsCashIncentiveReportModel = MsCashIncentiveReportModel;
		this.formId='cashincentivereportFrm';
		this.dataTable='#cashincentivereportTbl';
		this.route=msApp.baseUrl()+"/cashincentivereport/getdata"
	}
	
		
	getParams(){
		let params={};
		params.year = $('#cashincentivereportFrm  [name=year]').val();
		params.company_id = $('#cashincentivereportFrm  [name=company_id]').val();
		params.buyer_id = $('#cashincentivereportFrm  [name=buyer_id]').val();
		params.bank_bill_no = $('#cashincentivereportFrm  [name=bank_bill_no]').val();
		params.incentive_no = $('#cashincentivereportFrm  [name=incentive_no]').val();
		params.lc_sc_no = $('#cashincentivereportFrm  [name=lc_sc_no]').val();
		return params;
		
	}

	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#cashincentivereportTbl').datagrid('loadData', response.data);
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
			showFooter:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var balance_tk=0;
				var localAmount=0;
				var advAmountTk=0;
				var claimInvoiceQty=0;
				var claimInvoiceAmount=0;
				var netWgtQty=0;
				var claimRealizAmount=0;
				var costExport=0;
				var netRealizAmount=0;
				var claimAmountUsd=0;
				var AdvanceAmountTk=0;
				var FileAmount=0;
				for(var i=0; i<data.rows.length; i++){
					balance_tk+=data.rows[i]['balance_tk'].replace(/,/g,'')*1;
					localAmount+=data.rows[i]['local_cur_amount'].replace(/,/g,'')*1;
					advAmountTk+=data.rows[i]['advance_amount_tk'].replace(/,/g,'')*1;
					claimInvoiceQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					claimInvoiceAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					netWgtQty+=data.rows[i]['net_wgt_exp_qty'].replace(/,/g,'')*1;
					claimRealizAmount+=data.rows[i]['realized_amount'].replace(/,/g,'')*1;
					costExport+=data.rows[i]['cost_of_export'].replace(/,/g,'')*1;
					netRealizAmount+=data.rows[i]['net_realized_amount'].replace(/,/g,'')*1;
					claimAmountUsd+=data.rows[i]['claim_amount'].replace(/,/g,'')*1;
					AdvanceAmountTk+=data.rows[i]['advance_applied_amount'].replace(/,/g,'')*1;
					FileAmount+=data.rows[i]['file_amount'].replace(/,/g,'')*1;
				}
				$('#cashincentivereportTbl').datagrid('reloadFooter', [
					{
						balance_tk: balance_tk.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						local_cur_amount: localAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						advance_amount_tk: advAmountTk.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_qty: claimInvoiceQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						invoice_amount: claimInvoiceAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						net_wgt_exp_qty: netWgtQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						realized_amount: claimRealizAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						cost_of_export: costExport.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						net_realized_amount: netRealizAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						claim_amount: claimAmountUsd.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						advance_applied_amount: AdvanceAmountTk.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						file_amount: FileAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	docPrepDetailWindow(cash_incentive_ref_id){
		$('#incentiveDocPrepWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/cashincentivereport/getdocprep?cash_incentive_ref_id="+cash_incentive_ref_id)
		.then(function (response) {
			$('#containerDocWindow').html(response.data);
			//$.parser.parse('#containerDocWindow');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	formatincentiveref(value,row){
		if(!row.incentive_no){
			return '';
		}else{
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.docPrepDetailWindow('+row.cash_incentive_ref_id+')">'+row.incentive_no+'</a>';
		}
	}

	claimDetailWindow(cash_incentive_ref_id/* ,year,bank_bill_no,incentive_no */){
		let params=this.getParams();
		//params.year=year;
		//params.bank_bill_no=bank_bill_no;
		//params.incentive_no=incentive_no;
		params.cash_incentive_ref_id=cash_incentive_ref_id;
		let data= axios.get(msApp.baseUrl()+"/cashincentivereport/getclaim",{params});
		let ic=data.then(function (response) {
			if(cash_incentive_ref_id)
			{
				//alert(cash_incentive_ref_id)
				$('#incentiveClaimWindow').window('open');
				$('#incentiveClaimReportTbl').datagrid('loadData',response.data);
			}
			else{
				//alert(params.year);
				$('#incentiveClaimWindow').window('open');
				$('#incentiveClaimReportTbl').datagrid('loadData',response.data);
			}
			
		})
		.catch(function (error) {
			console.log(error);
		});
		//return ic;
	}

	showGridClaim(data){
		var claim = $('#incentiveClaimReportTbl');
		claim.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var invoiceQty=0;
				var invoiceAmount=0;
				var netWgtExport=0;
				var realizedAmount=0;
				var costOfExport=0;
				var freight=0;
				var netRealizedValue=0;
				var claimAmount=0;
				var localCurrency=0;
				var shortRealized=0;
				var shortRealizedPercent=0;

			for(var i=0; i<data.rows.length; i++){
				invoiceQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
				invoiceAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
				netWgtExport+=data.rows[i]['net_wgt_exp_qty'].replace(/,/g,'')*1;
				realizedAmount+=data.rows[i]['realized_amount'].replace(/,/g,'')*1;
				costOfExport+=data.rows[i]['cost_of_export'].replace(/,/g,'')*1;
				freight+=data.rows[i]['freight'].replace(/,/g,'')*1;
				netRealizedValue+=data.rows[i]['net_realized_amount'].replace(/,/g,'')*1;
				claimAmount+=data.rows[i]['claim_amount'].replace(/,/g,'')*1;
				localCurrency+=data.rows[i]['local_cur_amount'].replace(/,/g,'')*1;
				shortRealized+=data.rows[i]['short_realized_amount'].replace(/,/g,'')*1;

				shortRealizedPercent = (shortRealized/invoiceAmount)*100;
			}
			$('#incentiveClaimReportTbl').datagrid('reloadFooter', [
			{
				invoice_qty: invoiceQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				invoice_amount: invoiceAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				net_wgt_exp_qty: netWgtExport.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				realized_amount: realizedAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				cost_of_export: costOfExport.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				freight: freight.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				net_realized_amount: netRealizedValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				claim_amount: claimAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				local_cur_amount: localCurrency.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				short_realized_amount: shortRealized.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				short_realize_percent: shortRealizedPercent.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			}
			]);
		}

		});
		claim.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatincentiveclaim(value,row){
		return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">Click</a>';
	}

	formatClaimInvoiceQty(value,row){
		if(row.invoice_qty){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.invoice_qty+'</a>';
		}
		return 0;
	}

	formatClaimInvoiceAmount(value,row){
		if(row.invoice_amount){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.invoice_amount+'</a>';
		}
		return 0;
	}

	formatClaimInvoiceNetWgtExpQty(value,row){
		if(row.net_wgt_exp_qty){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.net_wgt_exp_qty+'</a>';
		}
		return 0;
	}

	formatClaimRealizedAmount(value,row){
		if(row.realized_amount){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.realized_amount+'</a>';
		}
		return 0;
		
	}

	formatClaimFreight(value,row){
		if(row.freight){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.freight+'</a>';
		}
		else{
			return '';
		}
	}

	formatClaimNetRealizedAmount(value,row){
		if(row.net_realized_amount){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.net_realized_amount+'</a>';
		}
		return 0;
		
	}

	formatClaimCostOfExport(value,row){
		if(row.cost_of_export){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.cost_of_export+'</a>';
		}
		return 0;
		
	}

	formatClaim(value,row){
		if(row.claim){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.claim+'</a>';
		}
		else{
			return '';
		}	
	}

	formatClaimAmount(value,row){
		if(row.claim_amount){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.claim_amount+'</a>';
		}
		return 0;
		
	}

	formatLocalCurrencyAmount(value,row){
		if(row.local_cur_amount){
			return '<a href="javascript:void(0)" onClick="MsCashIncentiveReport.claimDetailWindow('+row.cash_incentive_ref_id+')">'+row.local_cur_amount+'</a>';
		}
		return 0;
		
	}

	docalert(value,row,index){
		if(value !== "Yes"){
			// if(row.gsp_certify_btma_arranged == "No"){
			// 	return 'background-color:#ff00004d';
			// }
			// if(row.vat_eleven_arranged == "No"){
			// 	return 'background-color:#ff00004d';
			// }
			// if(row.ud_copy_arranged == "No"){
			// 	return 'background-color:#ff00004d';
			// }
			// if(row.prc_bd_format_arranged == "No"){
			// 	return 'background-color:#ff00004d';
			// }
			// if(row.alt_cash_assist_bgmea_arranged == "No"){
			// 	return 'background-color:#ff00004d';
			// }
			// if(row.cash_certify_btma_arranged == "No"){
			// 	return 'background-color:#ff00004d';
			// }
			return 'background-color:#ff00004d';
		}
		else{
			return '';
		}
	}

}
window.MsCashIncentiveReport = new MsCashIncentiveReportController(new MsCashIncentiveReportModel());
MsCashIncentiveReport.showGrid([]);
MsCashIncentiveReport.showGridClaim([]);
//MsCashIncentiveReport.showGridDocPrep([]);