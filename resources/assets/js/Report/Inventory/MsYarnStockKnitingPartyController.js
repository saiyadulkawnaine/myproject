require('./../../datagrid-filter.js');
let MsYarnStockKnitingPartyModel = require('./MsYarnStockKnitingPartyModel');

class MsYarnStockKnitingPartyController {
	constructor(MsYarnStockKnitingPartyModel)
	{
		this.MsYarnStockKnitingPartyModel = MsYarnStockKnitingPartyModel;
		this.formId='yarnstockknitingpartyFrm';
		this.dataTable='#yarnstockknitingpartyTbl';
		this.route=msApp.baseUrl()+"/yarnstockknitingparty/getdata";
	}

	getParams(){
		let params={};
		params.date_from = $('#yarnstockknitingpartyFrm  [name=date_from]').val();
		params.date_to = $('#yarnstockknitingpartyFrm  [name=date_to]').val();
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
			$('#yarnstockknitingpartyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Kniting Party Wise Yarn Stock Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#kpwystck').layout('panel', 'center').panel('setTitle', title);
		
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
				var opening_qty=0;
				var issue_qty=0;
				var total_issue_qty=0;
				var used_qty=0;
				var return_qty=0;
				var total_adjusted=0;
				var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					total_issue_qty+=data.rows[i]['total_issue_qty'].replace(/,/g,'')*1;
					used_qty+=data.rows[i]['used_qty'].replace(/,/g,'')*1;
					return_qty+=data.rows[i]['return_qty'].replace(/,/g,'')*1;
					total_adjusted+=data.rows[i]['total_adjusted'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_issue_qty: total_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	used_qty: used_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	return_qty: return_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	total_adjusted: total_adjusted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	
	
	
	
	

	formatIssued(value,row){
		return '<a href="javascript:void(0)" onClick="MsYarnStockKnitingParty.issuedWindow('+row.id+')">'+row.issue_qty+'</a>';
	}

	issuedWindow(supplier_id)
	{
		let params=this.getParams();
		params.supplier_id=supplier_id;
		let data= axios.get(msApp.baseUrl()+"/yarnstockknitingparty/issuedtl" ,{params});
		let sq=data.then(function (response) {
			$('#yarnstockknitingpartyissuedWindow').window('open');
			$('#yarnstockknitingpartyissuedTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	issuedGrid(data){
		var slsqty = $('#yarnstockknitingpartyissuedTbl');
		slsqty.datagrid({
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
			
			for(var i=0; i<data.rows.length; i++){
				qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			rate=amount/qty;
			$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}

		});
		slsqty.datagrid('enableFilter').datagrid('loadData', data);
	}

	
	formatUsed(value,row){
		return '<a href="javascript:void(0)" onClick="MsYarnStockKnitingParty.usedWindow('+row.id+')">'+row.used_qty+'</a>';
	}

	usedWindow(supplier_id)
	{
		let params=this.getParams();
		params.supplier_id=supplier_id;
		let data= axios.get(msApp.baseUrl()+"/yarnstockknitingparty/useddtl" ,{params});
		let sq=data.then(function (response) {
			$('#yarnstockknitingpartyusedWindow').window('open');
			$('#yarnstockknitingpartyusedTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	usedGrid(data){
		var slsqty = $('#yarnstockknitingpartyusedTbl');
		slsqty.datagrid({
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
			
			for(var i=0; i<data.rows.length; i++){
				qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			rate=amount/qty;
			$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}

		});
		slsqty.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatReturn(value,row){
		return '<a href="javascript:void(0)" onClick="MsYarnStockKnitingParty.returnWindow('+row.id+')">'+row.return_qty+'</a>';
	}

	returnWindow(supplier_id)
	{
		let params=this.getParams();
		params.supplier_id=supplier_id;
		let data= axios.get(msApp.baseUrl()+"/yarnstockknitingparty/returndtl" ,{params});
		let sq=data.then(function (response) {
			$('#yarnstockknitingpartyreturnWindow').window('open');
			$('#yarnstockknitingpartyreturnTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	returnGrid(data){
		var slsqty = $('#yarnstockknitingpartyreturnTbl');
		slsqty.datagrid({
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
			
			for(var i=0; i<data.rows.length; i++){
				qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			rate=amount/qty;
			$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}

		});
		slsqty.datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsYarnStockKnitingParty=new MsYarnStockKnitingPartyController(new MsYarnStockKnitingPartyModel());
MsYarnStockKnitingParty.showGrid([]);
MsYarnStockKnitingParty.issuedGrid([]);
MsYarnStockKnitingParty.usedGrid([]);
MsYarnStockKnitingParty.returnGrid([]);
//MsYarnStock.showGridSalesQty([]);