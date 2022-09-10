require('./../../datagrid-filter.js');
let MsYarnProcurementReportModel = require('./MsYarnProcurementReportModel');

class MsYarnProcurementReportController {
	constructor(MsYarnProcurementReportModel)
	{
		this.MsYarnProcurementReportModel = MsYarnProcurementReportModel;
		this.formId='yarnprocurementreportFrm';
		this.dataTable='#yarnprocurementreportTbl';
		this.route=msApp.baseUrl()+"/yarnprocurementreport/getdata";
	}

	getParams(){
		let params={};
		params.date_from = $('#yarnprocurementreportFrm  [name=date_from]').val();
		params.date_to = $('#yarnprocurementreportFrm  [name=date_to]').val();
		params.produced_company_id = $('#yarnprocurementreportFrm  [name=produced_company_id]').val();
		params.buyer_id = $('#yarnprocurementreportFrm  [name=buyer_id]').val();
		params.style_id = $('#yarnprocurementreportFrm  [name=style_id]').val();
		params.sales_order_id = $('#yarnprocurementreportFrm  [name=sales_order_id]').val();
		params.order_status = $('#yarnprocurementreportFrm  [name=order_status]').val();
		return params;
	}

	get()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#yarnprocurementreportTbl').datagrid('loadData', response.data);
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
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var req_qty=0;
				var po_qty=0;
				var po_bal=0;
				var issue_qty=0;
				var issue_bal=0;

				for(var i=0; i<data.rows.length; i++){
					req_qty+=data.rows[i]['req_qty'].replace(/,/g,'')*1;
					po_qty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					po_bal+=data.rows[i]['po_bal'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issue_bal+=data.rows[i]['issue_bal'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					req_qty: req_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_qty: po_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_bal: po_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_bal: issue_bal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

    formatdlmerchant(value,row){
		if (row.team_member_name) {
			return '<a href="javascript:void(0)" onClick="MsYarnProcurementReport.dlmerchantWindow('+row.user_id+')">'+row.team_member_name+'</a>';
		}
	}

	dlmerchantWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/yarnprocurementreport/getdlmerchant?user_id="+user_id);
		data.then(function (response) {
			$('#dealmctinfoTbl').datagrid('loadData', response.data);
			$('#dlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDlmct(data)
	{
		var dl = $('#dealmctinfoTbl');
		dl.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dl.datagrid('loadData', data);
	}

    openStyleWindow(){
		$('#openstyleWindow').window('open');
	}

	getStyleParams(){
		let params={};
		params.buyer_id = $('#stylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#stylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#stylesearchFrm  [name=style_description]').val();
		return params;
	}

	searchStyle(){
		let params=this.getStyleParams();
		let d= axios.get(msApp.baseUrl()+"/yarnprocurementreport/getstyle",{params})
		.then(function(response){
			$('#stylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showStyleGrid(data){
		let self=this;
		$('#stylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#yarnprocurementreportFrm [name=style_ref]').val(row.style_ref);
				$('#yarnprocurementreportFrm [name=style_id]').val(row.id);
				$('#openstyleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

    openOrderWindow(){
		$('#salesorderWindow').window('open');
	}
	getOrderParams(){
		let params={};
		params.sale_order_no = $('#yarnordersearchFrm  [name=sale_order_no]').val();
		params.style_ref = $('#yarnordersearchFrm  [name=style_ref]').val();
		params.job_no = $('#yarnordersearchFrm  [name=job_no]').val();
		return params;
	}
	searchOrder(){
		let params=this.getOrderParams();
		let sd= axios.get(msApp.baseUrl()+"/yarnprocurementreport/getorder",{params})
		.then(function(response){
			$('#yarnordersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showOrderGrid(data){
		let self=this;
		$('#yarnordersearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#yarnprocurementreportFrm [name=sales_order_id]').val(row.sales_order_id);
				$('#yarnprocurementreportFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#salesorderWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatPoQty(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsYarnProcurementReport.detailPoQtyWindow('+row.sale_order_id+','+'\''+row.item_account_id+'\''+')">'+row.po_qty+'</a>';
	}

	detailPoQtyWindow(sale_order_id,item_account_id){
		let params=this.getParams();
		params.item_account_id=item_account_id;
		params.sale_order_id=sale_order_id;
		let podata= axios.get(msApp.baseUrl()+"/yarnprocurementreport/getpoqtydtl",{params});
		let po=podata.then(function (response) {
			$('#poqtydtlTbl').datagrid('loadData', response.data);
			$('#poqtydtlWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
		});
		return po;
	}

	showPoQtyGrid(data){
		let self=this;
		$('#poqtydtlTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			onClickRow: function(index,row){
				//
			},
			onLoadSuccess: function(data){
				var poQty=0;
				var poAmount=0;
				for(var i=0; i<data.rows.length; i++){
					poQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					poAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					po_qty: poQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: poAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getsummery(){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/yarnprocurementreport/yarnprocurementsummery",{params})
		.then(function (response) {
			$('#yarnprocurementsummeryTbl').datagrid('loadData', response.data);
				
		})
		.catch(function (error) {
			console.log(error);
		});
	}


   showGridSummery(data)
	{
		var yps = $('#yarnprocurementsummeryTbl');
		yps.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				
				var tReqQty=0;
				var tPoItemQty=0;
				var tPoQty=0;
				var tPoBal=0;
				var tIssueQty=0;
				var tIssueBal=0;
				

				for(var i=0; i<data.rows.length; i++){
					tReqQty+=data.rows[i]['req_qty'].replace(/,/g,'')*1;
					tPoItemQty+=data.rows[i]['po_item_qty'].replace(/,/g,'')*1;
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoBal+=data.rows[i]['po_bal'].replace(/,/g,'')*1;
					tIssueQty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					tIssueBal+=data.rows[i]['issue_bal'].replace(/,/g,'')*1;
				}
					$(this).datagrid('reloadFooter', [
				{
					req_qty: tReqQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_item_qty: tPoItemQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_qty: tPoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_bal: tPoBal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: tIssueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_bal: tIssueBal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		yps.datagrid('enableFilter').datagrid('loadData', data);
	}

	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(msApp.baseUrl()+"/yarnprocurementreport/getdata",{params})
		.then(function (response) {
			$('#yarnprocurementreportTbl').datagrid('loadData', response.data);
			$('#yarnprocurementreportTbl').datagrid('toExcel','Yarn Procurement.xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
}
window.MsYarnProcurementReport=new MsYarnProcurementReportController(new MsYarnProcurementReportModel());
MsYarnProcurementReport.showGrid([]);
MsYarnProcurementReport.showGridDlmct([]);
MsYarnProcurementReport.showStyleGrid([]);
MsYarnProcurementReport.showOrderGrid([]);
MsYarnProcurementReport.showPoQtyGrid([]);
MsYarnProcurementReport.showGridSummery([]);