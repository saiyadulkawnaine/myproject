require('./../../datagrid-filter.js');
//require('./../../datagrid-scrollview.js');
let MsDyeIssueReceiveModel = require('./MsDyeIssueReceiveModel');

class MsDyeIssueReceiveController {
	constructor(MsDyeIssueReceiveModel)
	{
		this.MsDyeIssueReceiveModel = MsDyeIssueReceiveModel;
		this.formId='dyeissuereceiveFrm';
		this.dataTable='#dyeissuereceiveTbl';
		this.route=msApp.baseUrl()+"/dyeissuereceive/getdata";
	}

	getParams(){
		let params={};
		params.company_id = $('#dyeissuereceiveFrm  [name=company_id]').val();
		params.supplier_id = $('#dyeissuereceiveFrm  [name=supplier_id]').val();
		params.date_from = $('#dyeissuereceiveFrm  [name=date_from]').val();
		params.date_to = $('#dyeissuereceiveFrm  [name=date_to]').val();
		params.from_company_id = $('#dyeissuereceiveFrm  [name=from_company_id]').val();
		params.store_id = $('#dyeissuereceiveFrm  [name=store_id]').val();
		return params;
	}

	getReceive()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#dyeissuereceiveTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Date wise Receive & Issue Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#yystck').layout('panel', 'center').panel('setTitle', title);
		
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
				var pur_qty=0;
				var po_rate=0;
				var po_amount=0;
				var store_amount=0;
				var trans_in_qty=0;
				var trans_amount=0;
				var issue_rtn_qty=0;
				var issue_rtn_rate=0;
				var issue_rtn_amount=0;

				for(var i=0; i<data.rows.length; i++){
					pur_qty+=data.rows[i]['pur_qty'].replace(/,/g,'')*1;
					po_amount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					store_amount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
					trans_in_qty+=data.rows[i]['trans_in_qty'].replace(/,/g,'')*1;
					trans_amount+=data.rows[i]['trans_amount'].replace(/,/g,'')*1;
					issue_rtn_qty+=data.rows[i]['issue_rtn_qty'].replace(/,/g,'')*1;
					issue_rtn_rate+=data.rows[i]['issue_rtn_rate'].replace(/,/g,'')*1;
					issue_rtn_amount+=data.rows[i]['issue_rtn_amount'].replace(/,/g,'')*1;

				}
				po_rate=po_amount/pur_qty;
					$(this).datagrid('reloadFooter', [
				{
					pur_qty: pur_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_rate: po_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: po_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					store_amount: store_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_in_qty: trans_in_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_amount: trans_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_rtn_qty: issue_rtn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_rtn_rate: issue_rtn_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_rtn_amount: issue_rtn_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		var filter=[
			{
				field: 'pur_qty',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'po_rate',
				type: 'textbox',
				op: ['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'po_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'store_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'trans_in_qty',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between'],
			},
			{
				field: 'trans_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal', 'greater','greaterorequal','between']
			},
			{
				field: 'issue_rtn_qty',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'issue_rtn_rate',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'issue_rtn_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
		]
		dg.datagrid('enableFilter',filter).datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	// getpdf(){
	// 	let company_id = $('#dyeissuereceiveFrm  [name=company_id]').val();
	// 	let supplier_id = $('#dyeissuereceiveFrm  [name=supplier_id]').val();
	// 	let date_from = $('#dyeissuereceiveFrm  [name=date_from]').val();
	// 	let date_to = $('#dyeissuereceiveFrm  [name=date_to]').val();
	// 	let from_company_id = $('#dyeissuereceiveFrm  [name=from_company_id]').val();
		
	// 	if(date_from=='' && date_to==''){
	// 		alert('Select A Date Range First');
	// 		return;
	// 	}
	// 	if(!company_id && !company_id){
	// 		alert('Select A Company First');
	// 		return;
	// 	}
	// 	window.open(msApp.baseUrl()+"/dyeissuereceive/report?company_id="+company_id+"&supplier_id="+supplier_id+"&date_from="+date_from+"&date_to="+date_to+"&from_company_id="+from_company_id);
	// }
	

	getIssue()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		
		let iss= axios.get(msApp.baseUrl()+"/dyeissuereceive/getissuedata",{params})
		.then(function (response) {
			$('#issueWindow').window('open');
			$('#dyeissueTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return iss;
		// let date_from = new Date(params.date_from)
        // let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        // let date_to = new Date(params.date_to)
        // let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		// var title='Date wise Receive & Issue Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		// var p = $('#yystck').layout('panel', 'center').panel('setTitle', title);
		
	}

	showIssueGrid(data)
	{
		var df = $('#dyeissueTbl');
		df.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var consumedQty=0;
				//var poRate=0;
				var poAmount=0;
				var trans_outQty=0;
				var transAmount=0;
				var purchaseRtnQty=0;
				//var purchaseRtnRate=0;
				var purchaseRtnAmount=0;
				var loanQty=0;
				var loanAmount=0;
				var otherLoanQty=0;
				var otherLoanAmount=0;
				var machineLoanQty=0;
				var machineLoanAmount=0;

				for(var i=0; i<data.rows.length; i++){
					consumedQty+=data.rows[i]['consumed_qty'].replace(/,/g,'')*1;
					poAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					trans_outQty+=data.rows[i]['trans_out_qty'].replace(/,/g,'')*1;
					transAmount+=data.rows[i]['trans_amount'].replace(/,/g,'')*1;
					purchaseRtnQty+=data.rows[i]['purchase_rtn_qty'].replace(/,/g,'')*1;
					//purchase_rtn_rate+=data.rows[i]['purchase_rtn_rate'].replace(/,/g,'')*1;
					purchaseRtnAmount+=data.rows[i]['purchase_rtn_amount'].replace(/,/g,'')*1;
					loanQty+=data.rows[i]['loan_qty'].replace(/,/g,'')*1;
					loanAmount+=data.rows[i]['loan_amount'].replace(/,/g,'')*1;
					otherLoanQty+=data.rows[i]['other_loan_qty'].replace(/,/g,'')*1;
					otherLoanAmount+=data.rows[i]['other_loan_amount'].replace(/,/g,'')*1;
					machineLoanQty+=data.rows[i]['machine_wash_qty'].replace(/,/g,'')*1;
					machineLoanAmount+=data.rows[i]['machine_wash_amount'].replace(/,/g,'')*1;

				}
				//poRate=poAmount/consumedQty;
				//purchaseRtnRate=purchaseRtnAmount/purchaseRtnQty;
				$('#dyeissueTbl').datagrid('reloadFooter', [
				{
					consumed_qty: consumedQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: poAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_out_qty: trans_outQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_amount: transAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					purchase_rtn_qty: purchaseRtnQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					purchase_rtn_amount: purchaseRtnAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					loan_qty: loanQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					loan_amount: loanAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					other_loan_qty: otherLoanQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					other_loan_amount: otherLoanAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					machine_wash_qty: machineLoanQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					machine_wash_amount: machineLoanAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		var filterIsu=[
			{
				field: 'consumed_qty',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'po_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'trans_out_qty',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'trans_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'purchase_rtn_qty',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'purchase_rtn_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'loan_qty',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'loan_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'other_loan_qty',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'other_loan_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'machine_wash_qty',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
			{
				field: 'machine_wash_amount',
				type: 'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			},
		]
		df.datagrid('enableFilter',filterIsu).datagrid('loadData', data);
	}

	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/dyeissuereceive/getissuedata",{params})
		.then(function (response) {
			$('#issueWindow').window('open');
			$('#dyeissueTbl').datagrid('loadData', response.data);
			$('#dyeissueTbl').datagrid('toExcel','Dyes & Chemical Issue.xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showReceiveExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#dyeissuereceiveTbl').datagrid('loadData', response.data);
			$('#dyeissuereceiveTbl').datagrid('toExcel','Dyes & Chemical Receive.xls')
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsDyeIssueReceive=new MsDyeIssueReceiveController(new MsDyeIssueReceiveModel());
MsDyeIssueReceive.showGrid([]);
MsDyeIssueReceive.showIssueGrid([]);