require('./../../datagrid-filter.js');
let MsYarnIssueReceiveModel = require('./MsYarnIssueReceiveModel');

class MsYarnIssueReceiveController {
	constructor(MsYarnIssueReceiveModel)
	{
		this.MsYarnIssueReceiveModel = MsYarnIssueReceiveModel;
		this.formId='yarnissuereceiveFrm';
		this.dataTable='#yarnissuereceiveTbl';
		this.route=msApp.baseUrl()+"/yarnissuereceive/getdata";
	}

	getParams(){
		let params={};
		params.company_id = $('#yarnissuereceiveFrm  [name=company_id]').val();
		params.supplier_id = $('#yarnissuereceiveFrm  [name=supplier_id]').val();
		params.date_from = $('#yarnissuereceiveFrm  [name=date_from]').val();
		params.date_to = $('#yarnissuereceiveFrm  [name=date_to]').val();
		params.imp_lc_id = $('#yarnissuereceiveFrm  [name=imp_lc_id]').val();
		params.store_id = $('#yarnissuereceiveFrm  [name=store_id]').val();
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
			$('#yarnissuereceiveTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Date wise Receive & Issue Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#yrnisrcv').layout('panel', 'center').panel('setTitle', title);
		
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
				var rcvQty=0;
				var poRate=0;
				var poAmount=0;
				var storeAmount=0;
				var transInQty=0;
				var transAmount=0;
				var issueRtnQty=0;
				var issueRtnRate=0;
				var issueRtnAmount=0;
				var otherIssueRtnQty=0;
				var otherIssueRtnRate=0;
				var otherIssueRtnAmount=0;

				for(var i=0; i<data.rows.length; i++){
					rcvQty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					poAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					storeAmount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
					transInQty+=data.rows[i]['trans_in_qty'].replace(/,/g,'')*1;
					transAmount+=data.rows[i]['trans_amount'].replace(/,/g,'')*1;
					issueRtnQty+=data.rows[i]['issue_rtn_qty'].replace(/,/g,'')*1;
					issueRtnRate+=data.rows[i]['issue_rtn_rate'].replace(/,/g,'')*1;
					issueRtnAmount+=data.rows[i]['issue_rtn_amount'].replace(/,/g,'')*1;
					otherIssueRtnQty+=data.rows[i]['other_issue_rtn_qty'].replace(/,/g,'')*1;
					otherIssueRtnRate+=data.rows[i]['other_issue_rtn_rate'].replace(/,/g,'')*1;
					otherIssueRtnAmount+=data.rows[i]['other_issue_rtn_amount'].replace(/,/g,'')*1;

				}
					poRate=poAmount/rcvQty;
					issueRtnRate=issueRtnAmount/issueRtnQty;
					otherIssueRtnRate=otherIssueRtnAmount/otherIssueRtnQty;
				$(this).datagrid('reloadFooter', [
				{
					rcv_qty: rcvQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_rate: poRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: poAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					store_amount: storeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_in_qty: transInQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_amount: transAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_rtn_qty: issueRtnQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_rtn_rate: issueRtnRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_rtn_amount: issueRtnAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					other_issue_rtn_qty: otherIssueRtnQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					other_issue_rtn_rate: otherIssueRtnRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					other_issue_rtn_amount: otherIssueRtnAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
	
	getIssue()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		
		let iss= axios.get(msApp.baseUrl()+"/yarnissuereceive/getissuedata",{params})
		.then(function (response) {
			$('#issueWindow').window('open');
			$('#yarnissueTbl').datagrid('loadData', response.data);
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
		var df = $('#yarnissueTbl');
		df.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			 onLoadSuccess: function(data){
			 	var issueQty=0;
			 	var issueAmount=0;
				var issueRate=0;
				//var poAmount=0;
				var transOutQty=0;
				var transAmount=0;
				var purchaseRtnQty=0;
				var purchaseRtnRate=0;
				var purchaseRtnAmount=0;

				for(var i=0; i<data.rows.length; i++){
					issueQty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issueAmount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
					transOutQty+=data.rows[i]['trans_out_qty'].replace(/,/g,'')*1;
					transAmount+=data.rows[i]['trans_out_amount'].replace(/,/g,'')*1;
					purchaseRtnQty+=data.rows[i]['purchase_rtn_qty'].replace(/,/g,'')*1;
					purchaseRtnAmount+=data.rows[i]['purchase_rtn_amount'].replace(/,/g,'')*1;
				}
				issueRate=issueAmount/issueQty;
				purchaseRtnRate=purchaseRtnAmount/purchaseRtnQty;
				$('#yarnissueTbl').datagrid('reloadFooter', [
				{
					issue_qty: issueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_amount: issueAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_rate: issueRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_out_qty: transOutQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_out_amount: transAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					purchase_rtn_qty: purchaseRtnQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					purchase_rtn_rate: purchaseRtnRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					purchase_rtn_amount: purchaseRtnAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		df.datagrid('enableFilter').datagrid('loadData', data);
	}

	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/yarnissuereceive/getissuedata",{params})
		.then(function (response) {
			$('#issueWindow').window('open');
			$('#yarnissueTbl').datagrid('loadData', response.data);
			$('#yarnissueTbl').datagrid('toExcel','Yarn Issue.xls');
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
			$('#yarnissuereceiveTbl').datagrid('loadData', response.data);
			$('#yarnissuereceiveTbl').datagrid('toExcel','Yarn Receive.xls')
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	openYarnImpLcWndow(){
		$('#yarnImpLcWindow').window('open');
	}

	getLcParams(){
		let params = {};
		params.company_id=$('#yarnimplcsearchFrm  [name=company_id]').val();
      	params.supplier_id=$('#yarnimplcsearchFrm  [name=supplier_id]').val();
      	params.lc_type_id=$('#yarnimplcsearchFrm  [name=lc_type_id]').val();
      	params.issuing_bank_branch_id=$('#yarnimplcsearchFrm  [name=issuing_bank_branch_id]').val();
      	return params;
	}

	searchYarnImpLc(){
		let params=MsYarnIssueReceive.getLcParams();
		let d=axios.get(msApp.baseUrl()+"/yarnissuereceive/getyarnimplc",{params})
		.then(function(response){
			$('#yarnimplcsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
	
	showImpLcGrid(data){ 
      let self=this;
		$('#yarnimplcsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#yarnissuereceiveFrm [name=imp_lc_id]').val(row.id);
				$('#yarnissuereceiveFrm [name=lc_no]').val(row.lc_no);
				//$('#yarnimplcsearchTbl').datagrid('loadData',[]);
				$('#yarnImpLcWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getIssRegular()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		
		let isscons= axios.get(msApp.baseUrl()+"/yarnissuereceive/getissregular",{params})
		.then(function (response) {
			$('#issregularWindow').window('open');
			$('#issregularTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return isscons;
		
	}

	showIssRegularGrid(data)
	{
		var dsr = $('#issregularTbl');
		dsr.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			 onLoadSuccess: function(data){
			 	var issueQty=0;
			 	var issueAmount=0;
				var issueRate=0;

				for(var i=0; i<data.rows.length; i++){
					issueQty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issueAmount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
				}
				issueRate=issueAmount/issueQty;
				$('#issregularTbl').datagrid('reloadFooter', [
				{
					issue_qty: issueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_amount: issueAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_rate: issueRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dsr.datagrid('enableFilter').datagrid('loadData', data);
	}

	detailMrrPoWindow(inv_yarn_isu_item_id){
		let params=params={};
		params.inv_yarn_isu_item_id=inv_yarn_isu_item_id;
		//params.isu_against_id=isu_against_id;
		let mrpo= axios.get(msApp.baseUrl()+"/yarnissuereceive/getdtlmrrpo",{params});
		let gs=mrpo.then(function (response) {
			$('#detailmrrpoWindow').window('open');	
			$('#detailmrrpoTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return gs;
	}

	showGridMrrPo(data)
	{
		var dmp = $('#detailmrrpoTbl');
		dmp.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tIssueQty=0;
				var tstoreAmount=0;
				var tRate=0;
				var tPoRate=0;
				var poAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tIssueQty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					tstoreAmount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
					poAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
				}
				tRate=tstoreAmount/tIssueQty;
				tPoRate=poAmount/tIssueQty;
				$('#detailmrrpoTbl').datagrid('reloadFooter', [
				{
					issue_qty: tIssueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					store_rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					store_amount: tstoreAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: poAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_rate: tPoRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
				
			}
		});
		dmp.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatMrrPo(value,row){
		return '<a href="javascript:void(0)" onClick="MsYarnIssueReceive.detailMrrPoWindow('+row.inv_yarn_isu_item_id+')">'+value+'</a>';
	}

	showIssRegularExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/yarnissuereceive/getissregular",{params})
		.then(function (response) {
			$('#issregularWindow').window('open');
			$('#issregularTbl').datagrid('loadData', response.data);
			$('#issregularTbl').datagrid('toExcel','Yarn Issue(Consumption).xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getIssTransfer()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		
		let isstrns= axios.get(msApp.baseUrl()+"/yarnissuereceive/getisstransfer",{params})
		.then(function (response) {
			$('#isstransoutWindow').window('open');
			$('#isstransferTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return isstrns;
		
	}

	getIssPurRtn()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		
		let issrtn= axios.get(msApp.baseUrl()+"/yarnissuereceive/getisspurrtn",{params})
		.then(function (response) {
			$('#isstransoutWindow').window('open');
			$('#isstransferTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return issrtn;
		
	}

	showIssTransferGrid(data)
	{
		var dto = $('#isstransferTbl');
		dto.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			 onLoadSuccess: function(data){
			 	var tQty=0;
			 	var tAmount=0;
				var tRate=0;

				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				tRate=tAmount/tQty;
				$('#isstransferTbl').datagrid('reloadFooter', [
				{
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dto.datagrid('enableFilter').datagrid('loadData', data);
	}

	showIssPoRtnExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/yarnissuereceive/getisspurrtn",{params})
		.then(function (response) {
			$('#isstransoutWindow').window('open');
			$('#isstransferTbl').datagrid('loadData', response.data);
			$('#isstransferTbl').datagrid('toExcel','Yarn Issue(Purchase Return).xls');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showIssTransExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/yarnissuereceive/getisstransfer",{params})
		.then(function (response) {
			$('#isstransoutWindow').window('open');
			$('#isstransferTbl').datagrid('loadData', response.data);
			$('#isstransferTbl').datagrid('toExcel','Yarn Issue(Transfer Out).xls');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsYarnIssueReceive=new MsYarnIssueReceiveController(new MsYarnIssueReceiveModel());
MsYarnIssueReceive.showGrid([]);
MsYarnIssueReceive.showImpLcGrid([]);
MsYarnIssueReceive.showIssueGrid([]);
MsYarnIssueReceive.showIssRegularGrid([]);
MsYarnIssueReceive.showIssTransferGrid([]);
MsYarnIssueReceive.showGridMrrPo([]);