let MsBuyerDevelopmentReportModel = require('./MsBuyerDevelopmentReportModel');
require('./datagrid-filter.js');

class MsBuyerDevelopmentReportController {
	constructor(MsBuyerDevelopmentReportModel)
	{
		this.MsBuyerDevelopmentReportModel = MsBuyerDevelopmentReportModel;
		this.formId='buyerdevelopmentrptFrm';
		this.dataTable='#buyerdevelopmentrptTbl';
		this.route=msApp.baseUrl()+"/buyerdevelopmentrpt"
	}
	getParams(){
		let params={};
		params.date_from = $('#buyerdevelopmentrptFrm  [name=date_from]').val();
		params.date_to = $('#buyerdevelopmentrptFrm  [name=date_to]').val();
		params.buyer_id = $('#buyerdevelopmentrptFrm  [name=buyer_id]').val();
		params.status_id = $('#buyerdevelopmentrptFrm  [name=status_id]').val();
		params.team_id = $('#buyerdevelopmentrptFrm  [name=team_id]').val();
		return params;
	}
	
	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#buyerdevelopmentrptTbl').datagrid('loadData', response.data);
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
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	formatevent(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsBuyerDevelopmentReport.eventWindow('+row.id+')">'+value+'</a>';	
	}

	eventWindow(id)
	{
		
		let params=this.getParams();
		params.id=id;
		let d= axios.get(this.route+'/getevents',{params})
		.then(function (response) {
			$('#buyerdevelopmentrpteventTbl').datagrid('loadData', response.data);
			$('#buyerdevelopmentrpteventwindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridEvent(data)
	{
		var dg = $('#buyerdevelopmentrpteventTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatintm(value,row)
	{
		if(value){
			return '<a href="javascript:void(0)" onClick="MsBuyerDevelopmentReport.intmWindow('+row.id+')">'+value+'</a>';	
		}
		else{
			return '';
		}
	}

	intmWindow(id)
	{
		
		let params=this.getParams();
		params.id=id;
		let d= axios.get(this.route+'/getintms',{params})
		.then(function (response) {
			$('#buyerdevelopmentrptintmTbl').datagrid('loadData', response.data);
			$('#buyerdevelopmentrptintmwindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridIntm(data)
	{
		var dg = $('#buyerdevelopmentrptintmTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatfile(value,row){
		return '<a target="_blank" href="' + msApp.baseUrl()+"/images/"+row.file_src+ '">'+row.file_src+'</a>';
	}

	formatdoc(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsBuyerDevelopmentReport.docWindow('+row.id+')">'+value+'</a>';	
	}

	docWindow(id)
	{
		
		let params=this.getParams();
		params.id=id;
		let d= axios.get(this.route+'/getdocs',{params})
		.then(function (response) {
			$('#buyerdevelopmentrptdocTbl').datagrid('loadData', response.data);
			$('#buyerdevelopmentrptdocwindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDoc(data)
	{
		var dg = $('#buyerdevelopmentrptdocTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatbuy(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsBuyerDevelopmentReport.buyWindow('+row.id+')">'+value+'</a>';	
	}

	buyWindow(id)
	{
		
		let params=this.getParams();
		params.id=id;
		let d= axios.get(this.route+'/getbuys',{params})
		.then(function (response) {
			$('#buyerdevelopmentrptbuyTbl').datagrid('loadData', response.data);
			$('#buyerdevelopmentrptbuywindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridBuy(data)
	{
		var dg = $('#buyerdevelopmentrptbuyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatcont(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsBuyerDevelopmentReport.contWindow('+row.buyer_id+')">'+value+'</a>';	
	}

	contWindow(buyer_id)
	{
		
		let params=this.getParams();
		params.buyer_id=buyer_id;
		let d= axios.get(this.route+'/getbuycont',{params})
		.then(function (response) {
			$('#buyerdevelopmentrptbuycontTbl').datagrid('loadData', response.data);
			$('#buyerdevelopmentrptbuycontwindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridBuyCont(data)
	{
		var dg = $('#buyerdevelopmentrptbuycontTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getOrderForcasting()
	{
		let params=this.getParams();
		let d= axios.get(this.route+'/getorderforcasting',{params})
		.then(function (response) {
			$('#orderforcastingWindow').window('open');
			$('#orderforcastingrptContainer').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	buyerdevelopmentMktCostWindow(buyer_development_order_id,start_date,end_date)
	{
		
		let params=this.getParams();
		params.buyer_development_order_id=buyer_development_order_id;
		params.start_date=start_date;
		params.end_date=end_date;
		let d= axios.get(this.route+'/getmktcost',{params})
		.then(function (response) {
			$('#buyerdevelopmentrptmktcostWindow').window('open');
			$('#buyerdevelopmentrptmktcostTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridBuyDevMktCost(data)
	{
		var dgm = $('#buyerdevelopmentrptmktcostTbl');
		dgm.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['offer_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 offer_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dgm.datagrid('enableFilter').datagrid('loadData', data);
	}

	approved(e,id)
	{
		let formObj={};
		formObj.id=id;
		let d= axios.post(msApp.baseUrl()+'/mktcostapproval/approved',msApp.qs.stringify(formObj))
		.then(function (response) {
			msApp.showSuccess("Approved Successfully");
		})
		.catch(function (error) {
			msApp.showError("Error Found",'');
		});
	}

	approveButton(value,row)
	{
		if(row.final_approved_by){
			return '';
		}else{
			return '<a href="javascript:void(0)"  onClick="MsBuyerDevelopmentReport.approved(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
		}
		
	}

	imageWindow(flie_src){
		var output = document.getElementById('quotationstatementImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#quotationstatementImageWindow').window('open');
	}
	
	pdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/mktcost/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBuyerDevelopmentReport.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsBuyerDevelopmentReport.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}
	
	quotedprice(value,row,index)
	{
		if (row.cost_per_pcs*1 > value*1){
			return 'color:red;';
		}
	}

	styleformat(value,row,index)
	{
		if (row.status == 'Confirmed'){
				return 'background-color:#8DF2AD;';
		}
		if (row.status == 'Refused'){
				return 'background-color:#E66775;';
		}
		if (row.status == 'Cancel'){
				return 'background-color:#E66775;';
		}
	}

	frofitformat(value,row,index)
	{
		if ( value <0 ){
				return 'color:red;';
		}
	}
}
window.MsBuyerDevelopmentReport=new MsBuyerDevelopmentReportController(new MsBuyerDevelopmentReportModel());
//MsBuyerDevelopmentReport.showGridSummary([]);
MsBuyerDevelopmentReport.showGrid([]);
MsBuyerDevelopmentReport.showGridEvent([]);
MsBuyerDevelopmentReport.showGridIntm([]);
MsBuyerDevelopmentReport.showGridDoc([]);
MsBuyerDevelopmentReport.showGridBuy([]);
MsBuyerDevelopmentReport.showGridBuyCont([]);
MsBuyerDevelopmentReport.showGridBuyDevMktCost([]);
