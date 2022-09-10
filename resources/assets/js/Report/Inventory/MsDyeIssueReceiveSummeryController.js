require('./../../datagrid-filter.js');
let MsDyeIssueReceiveSummeryModel = require('./MsDyeIssueReceiveSummeryModel');

class MsDyeIssueReceiveSummeryController {
	constructor(MsDyeIssueReceiveSummeryModel)
	{
		this.MsDyeIssueReceiveSummeryModel = MsDyeIssueReceiveSummeryModel;
		this.formId='dyeissuereceivesummeryFrm';
		this.dataTable='#dyereceivesummeryTbl';
		this.route=msApp.baseUrl()+"/dyeissuereceivesummery/getdata";
	}

	getParams(){
		let params={};
		params.date_from = $('#dyeissuereceivesummeryFrm  [name=date_from]').val();
		params.date_to = $('#dyeissuereceivesummeryFrm  [name=date_to]').val();
		params.company_id = $('#dyeissuereceivesummeryFrm  [name=company_id]').val();
		params.supplier_id = $('#dyeissuereceivesummeryFrm  [name=supplier_id]').val();
		params.store_id = $('#dyeissuereceivesummeryFrm  [name=store_id]').val();
		params.identity = $('#dyeissuereceivesummeryFrm  [name=identity]').val();
		return params;
	}

	getReceive()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		if(!params.identity && !params.identity){
			alert('Select An Item First');
			return;
		}
		
		let r= axios.get(this.route,{params})
		.then(function (response) {
			$('#receiveWindow').window('open');
			$('#dyereceivesummeryTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		// let date_from = new Date(params.date_from)
        // let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        // let date_to = new Date(params.date_to)
        // let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		// var title='Date wise Issue Summery Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		// var p = $('#rcvsummery').layout('panel', 'center').panel('setTitle', title);	
	}


	showGrid(data)
	{
		var dg = $('#dyereceivesummeryTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var rcvQty=0;
				//var rcvRate=0;
				var rcvAmount=0;
				var storeAmount=0;
				var transInQty=0;
				var transAmount=0;
				var issueQty=0;
				var issueAmount=0;
				var loanQty=0;
				var loanAmount=0;

				for(var i=0; i<data.rows.length; i++){
					rcvQty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					rcvAmount+=data.rows[i]['rcv_amount'].replace(/,/g,'')*1;
					storeAmount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
					transInQty+=data.rows[i]['trans_in_qty'].replace(/,/g,'')*1;
					transAmount+=data.rows[i]['trans_in_amount'].replace(/,/g,'')*1;
					issueQty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issueAmount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
					loanQty+=data.rows[i]['loan_qty'].replace(/,/g,'')*1;
					loanAmount+=data.rows[i]['loan_amount'].replace(/,/g,'')*1;

				}
				//rcvRate=rcvAmount/rcvQty;

				$('#dyereceivesummeryTbl').datagrid('reloadFooter', [
				{
					rcv_qty: rcvQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//rcv_rate: rcvRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_amount: rcvAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					store_amount: storeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_in_qty: transInQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_in_amount: transAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_amount: issueAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					loan_qty: loanQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					loan_amount: loanAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	// getpdf(){
	// 	let company_id = $('#dyeissuereceivesummeryFrm  [name=company_id]').val();
	// 	let supplier_id = $('#dyeissuereceivesummeryFrm  [name=supplier_id]').val();
	// 	let date_from = $('#dyeissuereceivesummeryFrm  [name=date_from]').val();
	// 	let date_to = $('#dyeissuereceivesummeryFrm  [name=date_to]').val();
	// 	let from_company_id = $('#dyeissuereceivesummeryFrm  [name=from_company_id]').val();
	// 	if(date_from=='' && date_to==''){
	// 		alert('Select A Date Range First');
	// 		return;
	// 	}
	// 	if(!company_id && !company_id){
	// 		alert('Select A Company First');
	// 		return;
	// 	}
	// 	window.open(msApp.baseUrl()+"/dyeissuereceivesummery/report?company_id="+company_id+"&supplier_id="+supplier_id+"&date_from="+date_from+"&date_to="+date_to+"&from_company_id="+from_company_id);
	// }
	

	getIssue()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}

		if(!params.identity && !params.identity){
			alert('Select An Item First');
			return;
		}
		
		let iss= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getissuedata",{params})
		.then(function (response) {
			$('#dyeissueTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		//return iss;
		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Date wise Issue Summery Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#issuesummery').layout('panel', 'center').panel('setTitle', title);
		
	}

	showIssueGrid(data)
	{
		var df = $('#dyeissueTbl');
		df.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
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
				//var loanRate=0;
				var loanAmount=0;

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

				}
				//poRate=poAmount/consumedQty;
				//purchaseRtnRate=purchaseRtnAmount/purchaseRtnQty;
				$('#dyeissueTbl').datagrid('reloadFooter', [
				{
					consumed_qty: consumedQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//po_rate: poRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: poAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_out_qty: trans_outQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_amount: transAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					purchase_rtn_qty: purchaseRtnQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//purchase_rtn_rate: purchaseRtnRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					purchase_rtn_amount: purchaseRtnAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					loan_qty: loanQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//loan_rate: poRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					loan_amount: loanAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		df.datagrid('enableFilter').datagrid('loadData', data);
	}

	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getissuedata",{params})
		.then(function (response) {
			//$('#issueWindow').window('open');
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
			$('#receiveWindow').window('open');
			$('#dyereceivesummeryTbl').datagrid('loadData', response.data);
			$('#dyereceivesummeryTbl').datagrid('toExcel','Dyes & Chemical Receive.xls')
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	
	formatloandtl(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsDyeIssueReceiveSummery.loandtlWindow('+row.item_account_id+')">'+value+'</a>';	
	}

	loandtlWindow(item_account_id)
	{
		let params=this.getParams();
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getloandtl",{params});
		data.then(function (response) {
			$('#detailTbl').datagrid('loadData', response.data);
			$('#detailwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDetail(data)
	{
		var dla = $('#detailTbl');
		dla.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var Qty=0;
					var Rate=0;
					var Amount=0;
					
					for(var i=0; i<data.rows.length; i++){
						Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						Rate+=data.rows[i]['rate'].replace(/,/g,'')*1;
						Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

					}
					//Rate=Amount/Qty;
					$('#detailTbl').datagrid('reloadFooter', [
					{ 
						qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: Rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dla.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	formatregulardtl(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsDyeIssueReceiveSummery.regulardtlWindow('+row.item_account_id+')">'+value+'</a>';	
	}

	regulardtlWindow(item_account_id)
	{
		let params=this.getParams();
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getregulardtl",{params});
		data.then(function (response) {
			$('#regularTbl').datagrid('loadData', response.data);
			$('#regularwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridRegular(data)
	{
		var dl = $('#regularTbl');
		dl.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var Qty=0;
					var Rate=0;
					var Amount=0;
					
					for(var i=0; i<data.rows.length; i++){
						Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						Rate+=data.rows[i]['rate'].replace(/,/g,'')*1;
						Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

					}
					//Rate=Amount/Qty;
					$('#regularTbl').datagrid('reloadFooter', [
					{ 
						qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: Rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dl.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	formattransdtl(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsDyeIssueReceiveSummery.transdtlWindow('+row.item_account_id+')">'+value+'</a>';	
	}

	transdtlWindow(item_account_id)
	{
		let params=this.getParams();
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/gettransdtl",{params});
		data.then(function (response) {
			$('#transTbl').datagrid('loadData', response.data);
			$('#transwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridTrans(data)
	{
		var dlt = $('#transTbl');
		dlt.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var Qty=0;
					//var rate=0;
					var Amount=0;
					
					for(var i=0; i<data.rows.length; i++){
						Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						//rate+=data.rows[i]['rate'].replace(/,/g,'')*1;
						Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

					}
					//Rate=Amount/Qty;
					$('#transTbl').datagrid('reloadFooter', [
					{ 
						qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//rate: Rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dlt.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	formatrcvrtndtl(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsDyeIssueReceiveSummery.rcvrtndtlWindow('+row.item_account_id+')">'+value+'</a>';	
	}

	rcvrtndtlWindow(item_account_id)
	{
		let params=this.getParams();
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getrcvrtndtl",{params});
		data.then(function (response) {
			$('#returnTbl').datagrid('loadData', response.data);
			$('#returnwindow').window('open');   
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridReturn(data)
	{
		var dlr = $('#returnTbl');
		dlr.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var Qty=0;
					//var rate=0;
					var Amount=0;
					
					for(var i=0; i<data.rows.length; i++){
						Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						//rate+=data.rows[i]['rate'].replace(/,/g,'')*1;
						Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

					}
					Rate=Amount/Qty;
					$('#returnTbl').datagrid('reloadFooter', [
					{ 
						qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: Rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dlr.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	formatrcvregular(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsDyeIssueReceiveSummery.rcvregularWindow('+row.item_account_id+')">'+value+'</a>';	
	}

	rcvregularWindow(item_account_id)
	{
		let params=this.getParams();
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getrcvregular",{params});
		data.then(function (response) {
			$('#rcvregularwindow').window('open'); 
			$('#rcvregularTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridRcvRegular(data)
	{
		var rr = $('#rcvregularTbl');
		rr.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var Qty=0;
					var storeAmount=0;
					var Amount=0;
					
					for(var i=0; i<data.rows.length; i++){
						Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						storeAmount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
						Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

					}
					//Rate=Amount/Qty;
					$('#rcvregularTbl').datagrid('reloadFooter', [
					{ 
						qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						store_amount: storeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		rr.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	formatrcvtransin(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsDyeIssueReceiveSummery.rcvtransinWindow('+row.item_account_id+')">'+value+'</a>';	
	}

	rcvtransinWindow(item_account_id)
	{
		let params=this.getParams();
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getrcvtransin",{params});
		data.then(function (response) {
			$('#rcvtransinwindow').window('open'); 
			$('#rcvtransinTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridRcvTransIn(data)
	{
		var rtin = $('#rcvtransinTbl');
		rtin.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var Qty=0;
					var Amount=0;
					
					for(var i=0; i<data.rows.length; i++){
						Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

					}
					$('#rcvtransinTbl').datagrid('reloadFooter', [
					{ 
						qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		rtin.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	formatisurtn(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsDyeIssueReceiveSummery.isurtnWindow('+row.item_account_id+')">'+value+'</a>';	
	}

	isurtnWindow(item_account_id)
	{
		let params=this.getParams();
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getisurtn",{params});
		data.then(function (response) {
			$('#isurtnwindow').window('open'); 
			$('#isurtnTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridRcvIssue(data)
	{
		var ri = $('#isurtnTbl');
		ri.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var Qty=0;
					var storeAmount=0;
					var Amount=0;
					
					for(var i=0; i<data.rows.length; i++){
						Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						storeAmount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
						Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

					}
					//Rate=Amount/Qty;
					$('#isurtnTbl').datagrid('reloadFooter', [
					{ 
						qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						store_amount: storeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		ri.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	formatrcvloan(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsDyeIssueReceiveSummery.rcvloanWindow('+row.item_account_id+')">'+value+'</a>';	
	}

	rcvloanWindow(item_account_id)
	{
		let params=this.getParams();
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/dyeissuereceivesummery/getrcvloan",{params});
		data.then(function (response) {
			$('#rcvloanwindow').window('open'); 
			$('#rcvloanTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridRcvLoan(data)
	{
		var rl = $('#rcvloanTbl');
		rl.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var Qty=0;
					var storeAmount=0;
					var Amount=0;
					
					for(var i=0; i<data.rows.length; i++){
						Qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						storeAmount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
						Amount+=data.rows[i]['amount'].replace(/,/g,'')*1;

					}
					//Rate=Amount/Qty;
					$('#rcvloanTbl').datagrid('reloadFooter', [
					{ 
						qty: Qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						store_amount: storeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: Amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		rl.datagrid('enableFilter').datagrid('loadData', data);
	}

	

}
window.MsDyeIssueReceiveSummery=new MsDyeIssueReceiveSummeryController(new MsDyeIssueReceiveSummeryModel());
MsDyeIssueReceiveSummery.showGrid([]);
MsDyeIssueReceiveSummery.showIssueGrid([]);
MsDyeIssueReceiveSummery.showGridDetail([]);
MsDyeIssueReceiveSummery.showGridRegular([]);
MsDyeIssueReceiveSummery.showGridTrans([]);
MsDyeIssueReceiveSummery.showGridReturn([]);
MsDyeIssueReceiveSummery.showGridRcvRegular([]);
MsDyeIssueReceiveSummery.showGridRcvTransIn([]);
MsDyeIssueReceiveSummery.showGridRcvIssue([]);
MsDyeIssueReceiveSummery.showGridRcvLoan([]);