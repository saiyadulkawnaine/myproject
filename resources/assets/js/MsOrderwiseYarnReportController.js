let MsOrderwiseYarnReportModel = require('./MsOrderwiseYarnReportModel');
require('./datagrid-filter.js');

class MsOrderwiseYarnReportController {
	constructor(MsOrderwiseYarnReportModel)
	{
		this.MsOrderwiseYarnReportModel = MsOrderwiseYarnReportModel;
		this.formId='orderwiseyarnreportFrm';
		this.dataTable='#orderwiseyarnreportTbl';
		this.route=msApp.baseUrl()+"/orderwiseyarnreport"
	}
	getParams(){
		let params={};
		params.company_id = $('#orderwiseyarnreportFrm  [name=company_id]').val();
		params.buyer_id = $('#orderwiseyarnreportFrm  [name=buyer_id]').val();
		params.date_from = $('#orderwiseyarnreportFrm  [name=date_from]').val();
		params.date_to = $('#orderwiseyarnreportFrm  [name=date_to]').val();
		params.order_status = $('#orderwiseyarnreportFrm  [name=order_status]').val();
		params.original_date_from = $('#orderwiseyarnreportFrm  [name=original_date_from]').val();
		params.original_date_to = $('#orderwiseyarnreportFrm  [name=original_date_to]').val();
		return params;
	}
	
	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#orderwiseyarnreportTbl').datagrid('loadData', response.data);
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
				var yarn_req_qty=0;
				var po_yarn_qty=0;
				var po_yarn_lc_qty=0;
				var yarn_rcv_qty=0;
				var po_yarn_bal_qty=0;
				var po_yarn_lc_bal_qty=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					yarn_req_qty+=data.rows[i]['yarn_req_qty'].replace(/,/g,'')*1;
					po_yarn_qty+=data.rows[i]['po_yarn_qty'].replace(/,/g,'')*1;
					po_yarn_lc_qty+=data.rows[i]['po_yarn_lc_qty'].replace(/,/g,'')*1;
					yarn_rcv_qty+=data.rows[i]['yarn_rcv_qty'].replace(/,/g,'')*1;
					po_yarn_bal_qty+=data.rows[i]['po_yarn_bal_qty'].replace(/,/g,'')*1;
					po_yarn_lc_bal_qty+=data.rows[i]['po_yarn_lc_bal_qty'].replace(/,/g,'')*1;
				}

				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_req_qty: yarn_req_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_yarn_qty: po_yarn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_yarn_lc_qty: po_yarn_lc_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_rcv_qty: yarn_rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_yarn_bal_qty: po_yarn_bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_yarn_lc_bal_qty: po_yarn_lc_bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#orderwiseyarnreportTbl').datagrid('loadData', response.data);
			msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	// formatteamleader(value,row){
	// 	return '<a href="javascript:void(0)" onClick="MsOrderwiseYarnReport.teamleaderWindow('+row.teamleader_id+')">'+row.team_name+'</a>';
	// }

	// teamleaderWindow(teamleader_id){
	// 	let data= axios.get(msApp.baseUrl()+"/orderwiseyarnreport/getdlmerchant?user_id="+teamleader_id);
	// 	data.then(function (response) {
	// 		$('#dealmctinfoTbl').datagrid('loadData', response.data);
	// 		$('#dlmerchantWindow').window('open');			    
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

	// formatdlmerchant(value,row){
	// 	return '<a href="javascript:void(0)" onClick="MsOrderwiseYarnReport.dlmerchantWindow('+row.user_id+')">'+row.team_member_name+'</a>';
	// }

	// dlmerchantWindow(user_id){
	// 	let data= axios.get(msApp.baseUrl()+"/orderwiseyarnreport/getdlmerchant?user_id="+user_id);
	// 	data.then(function (response) {
	// 		$('#dealmctinfoTbl').datagrid('loadData', response.data);
	// 		$('#dlmerchantWindow').window('open');			    
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

	// showGridDlmct(data)
	// {
	// 	var dg = $('#dealmctinfoTbl');
	// 	dg.datagrid({
	// 	border:false,
	// 	singleSelect:true,
	// 	showFooter:true,
	// 	fit:true,
	// 	rownumbers:true,
	// 	emptyMsg:'No Record Found'

	// 	});
	// 	dg.datagrid('loadData', data);
	// }

	// formatbuyingAgent(value,row){
	// 	return '<a href="javascript:void(0)" onClick="MsOrderwiseYarnReport.buyingAgentWindow('+row.buying_agent_id+')">'+row.buying_agent_name+'</a>';
	// }

	// buyingAgentWindow(buying_agent_id){
		
	// 	let agent= axios.get(msApp.baseUrl()+"/orderwiseyarnreport/getbuyhouse?buyer_id="+buying_agent_id);
	// 	agent.then(function (response) {
	// 		$('#buyagentTbl').datagrid('loadData', response.data);
	// 		$('#buyagentwindow').window('open');			    
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

	// showGridBAgent(data)
	// {
	// 	var dg = $('#buyagentTbl');
	// 	dg.datagrid({
	// 	border:false,
	// 	singleSelect:true,
	// 	showFooter:false,
	// 	fit:true,
	// 	rownumbers:true,
	// 	emptyMsg:'No Record Found'

	// 	});
	// 	dg.datagrid('loadData', data);
	// }

	formatopfiles(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderwiseYarnReport.opfileWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}

	opfileWindow(style_id)
	{
		let data= axios.get(msApp.baseUrl()+"/orderwiseyarnreport/getopfile?style_id="+style_id);
		data.then(function (response) {
			$('#opfilesrcTbl').datagrid('loadData', response.data);
			$('#opfilesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridOpFileSrc(data)
	{
		var dg = $('#opfilesrcTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'
		});
		dg.datagrid('loadData', data);
	}

	formatShowOpFile(value,row)
	{
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}

	// formatlcsc(value,row)
	// {
	// 	return '<a href="javascript:void(0)" onClick="MsOrderwiseYarnReport.lcscWindow('+row.sale_order_id+')">'+row.lc_sc_no+'</a>';	
	// }

	// lcscWindow(sale_order_id)
	// {
	// 	let data= axios.get(msApp.baseUrl()+"/orderwiseyarnreport/getlcsc?sale_order_id="+sale_order_id);
	// 	data.then(function (response) {
	// 		$('#oplcscTbl').datagrid('loadData', response.data);
	// 		$('#oplcscwindow').window('open');	    
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

	// showGridLcSc(data)
	// {
	// 	var dg = $('#oplcscTbl');
	// 	dg.datagrid({
	// 	border:false,
	// 	singleSelect:true,
	// 	showFooter:true,
	// 	fit:true,
	// 	rownumbers:true,
	// 	emptyMsg:'No Record Found',
	// 	});
	// 	dg.datagrid('loadData', data);
	// }

	formatorderqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsOrderwiseYarnReport.orderqtyWindow('+row.sale_order_id+')">'+value+'</a>';	
	}

	orderqtyWindow(sale_order_id)
	{
		let params=this.getParams();
		params.sale_order_id=sale_order_id
		let data= axios.get(msApp.baseUrl()+"/orderwiseyarnreport/getorderqty",{params});
		data.then(function (response) {
			$('#oporderqtyTbl').datagrid('loadData', response.data);
			$('#oporderqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridOrderQty(data)
	{
		var dg = $('#oporderqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
					var qty=0;
					var rate=0;
					var amount=0;
					var smv=0;
					var booked_minute=0;
					
					for(var i=0; i<data.rows.length; i++){
						qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
						booked_minute+=data.rows[i]['booked_minute'].replace(/,/g,'')*1;
					}

					if(qty){
						rate=amount/qty;
						smv=booked_minute/qty;
					}

					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						smv: smv.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						booked_minute: booked_minute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatimage(value,row)
	{
	return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsOrderwiseYarnReport.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	imageWindow(flie_src)
	{
		var output = document.getElementById('orderProgressImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#orderProgressImageWindow').window('open');
	}
	

	openOrdStyleWindow(){
		$('#ordstyleWindow').window('open');
	}
	getOrdStyleParams(){
		let params={};
		params.buyer_id = $('#ordstylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#ordstylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#ordstylesearchFrm  [name=style_description]').val();
		return params;
	}
	searchOrdStyleGrid(){
		let params=this.getOrdStyleParams();
		let d= axios.get(this.route+'/ordpstyle',{params})
		.then(function(response){
			$('#ordstylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showOrdStyleGrid(data){
		let self=this;
		$('#ordstylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#orderwiseyarnreportFrm [name=style_ref]').val(row.style_ref);
				$('#orderwiseyarnreportFrm [name=style_id]').val(row.id);
				$('#ordstyleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	// openTeammemberDlmWindow(){
	// 	$('#teammemberDlmWindow').window('open');
	// }
	// getTdlmParams(){
	// 	let params={};
	// 	params.team_id = $('#teammemberdlmFrm  [name=team_id]').val();
	// 	return params;
	// }
	// searchTeammemberDlmGrid(){
	// 	let params=this.getTdlmParams();
	// 	let dlm= axios.get(this.route+'/ordteammemberdlm',{params})
	// 	.then(function(response){
	// 		$('#teammemberdlmTbl').datagrid('loadData', response.data);
	// 	}).catch(function (error) {
	// 		console.log(error);
	// 	});
	// }
	// showTeammemberDlmGrid(data){
	// 	let self=this;
	// 	$('#teammemberdlmTbl').datagrid({
	// 		border:false,
	// 		singleSelect:true,
	// 		fit:true,
	// 		onClickRow: function(index,row){
	// 			$('#orderwiseyarnreportFrm [name=factory_merchant_id]').val(row.factory_merchant_id);
	// 			$('#orderwiseyarnreportFrm [name=team_member_name]').val(row.dlm_name);
	// 			$('#teammemberDlmWindow').window('close');
	// 		}
	// 	}).datagrid('enableFilter').datagrid('loadData',data);	
	// }
}
window.MsOrderwiseYarnReport=new MsOrderwiseYarnReportController(new MsOrderwiseYarnReportModel());

MsOrderwiseYarnReport.showGrid([]);

//MsOrderwiseYarnReport.showGridDlmct([]);
//MsOrderwiseYarnReport.showGridBAgent([]);
MsOrderwiseYarnReport.showGridOpFileSrc([]);
//MsOrderwiseYarnReport.showGridLcSc([]);
//MsOrderwiseYarnReport.showGridOrderQty([]);

