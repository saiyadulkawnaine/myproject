require('./../../../datagrid-filter.js');
let MsFabricStockSubconDyeingPartyModel = require('./MsFabricStockSubconDyeingPartyModel');

class MsFabricStockSubconDyeingPartyController {
	constructor(MsFabricStockSubconDyeingPartyModel)
	{
		this.MsFabricStockSubconDyeingPartyModel = MsFabricStockSubconDyeingPartyModel;
		this.formId='fabricstocksubcondyeingpartyFrm';
		this.dataTable='#fabricstocksubcondyeingpartyTbl';
		this.route=msApp.baseUrl()+"/fabricstocksubcondyeingparty/getdata";
	}

	getParams(){
		let params={};
		params.date_from = $('#fabricstocksubcondyeingpartyFrm  [name=date_from]').val();
		params.date_to = $('#fabricstocksubcondyeingpartyFrm  [name=date_to]').val();
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
			$('#fabricstocksubcondyeingpartyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Dyeing Party Wise Fabric Stock Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#fabricstocksubcondyeingpartypanel').layout('panel', 'center').panel('setTitle', title);
		
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
				var rcv_qty=0;
				var total_rcv_qty=0;
				var dlv_fin_qty=0;
				var dlv_grey_used_qty=0;
				var rtn_qty=0;
				var total_adjusted=0;
				var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					total_rcv_qty+=data.rows[i]['total_rcv_qty'].replace(/,/g,'')*1;
					dlv_fin_qty+=data.rows[i]['dlv_fin_qty'].replace(/,/g,'')*1;
					dlv_grey_used_qty+=data.rows[i]['dlv_grey_used_qty'].replace(/,/g,'')*1;
					rtn_qty+=data.rows[i]['rtn_qty'].replace(/,/g,'')*1;
					total_adjusted+=data.rows[i]['total_adjusted'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					total_rcv_qty: total_rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dlv_fin_qty: dlv_fin_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	dlv_grey_used_qty: dlv_grey_used_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rtn_qty: rtn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	
	
	
	
	

	formatReceived(value,row){
		return '<a href="javascript:void(0)" onClick="MsFabricStockSubconDyeingParty.receivedWindow('+row.id+')">'+row.rcv_qty+'</a>';
	}

	receivedWindow(buyer_id)
	{
		let params=this.getParams();
		params.buyer_id=buyer_id;
		let data= axios.get(msApp.baseUrl()+"/fabricstocksubcondyeingparty/receivedtl" ,{params});
		let sq=data.then(function (response) {
			$('#fabricstocksubcondyeingpartyreceivedWindow').window('open');
			$('#fabricstocksubcondyeingpartyreceivedTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	receivedGrid(data){
		var slsqty = $('#fabricstocksubcondyeingpartyreceivedTbl');
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
		return '<a href="javascript:void(0)" onClick="MsFabricStockSubconDyeingParty.usedWindow('+row.id+')">'+row.dlv_grey_used_qty+'</a>';
	}

	usedWindow(buyer_id)
	{
		let params=this.getParams();
		params.buyer_id=buyer_id;
		let data= axios.get(msApp.baseUrl()+"/fabricstocksubcondyeingparty/useddtl" ,{params});
		let sq=data.then(function (response) {
			$('#fabricstocksubcondyeingpartyusedWindow').window('open');
			$('#fabricstocksubcondyeingpartyusedTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	usedGrid(data){
		var slsqty = $('#fabricstocksubcondyeingpartyusedTbl');
		slsqty.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var fin_qty=0;
			var qty=0;
			var amount=0;
			var rate=0;
			
			for(var i=0; i<data.rows.length; i++){
				fin_qty+=data.rows[i]['fin_qty'].replace(/,/g,'')*1;
				qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
			}
			rate=amount/qty;
			$(this).datagrid('reloadFooter', [
				{
					fin_qty: fin_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
		return '<a href="javascript:void(0)" onClick="MsFabricStockSubconDyeingParty.returnWindow('+row.id+')">'+row.rtn_qty+'</a>';
	}

	returnWindow(buyer_id)
	{
		let params=this.getParams();
		params.buyer_id=buyer_id;
		let data= axios.get(msApp.baseUrl()+"/fabricstocksubcondyeingparty/returndtl" ,{params});
		let sq=data.then(function (response) {
			$('#fabricstocksubcondyeingpartyreturnWindow').window('open');
			$('#fabricstocksubcondyeingpartyreturnTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	returnGrid(data){
		var slsqty = $('#fabricstocksubcondyeingpartyreturnTbl');
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

	formatClosing(value,row){
		return '<a href="javascript:void(0)" onClick="MsFabricStockSubconDyeingParty.closingWindow('+row.id+')">'+row.stock_qty+'</a>';
	}

	closingWindow(buyer_id)
	{
		let params=this.getParams();
		params.buyer_id=buyer_id;
		let data= axios.get(msApp.baseUrl()+"/fabricstocksubcondyeingparty/closingdtl" ,{params});
		let sq=data.then(function (response) {
			$('#fabricstocksubcondyeingpartyclosingWindow').window('open');
			$('#fabricstocksubcondyeingpartyclosingTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	closingGrid(data){
		var slsqty = $('#fabricstocksubcondyeingpartyclosingTbl');
		slsqty.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var opening_qty=0;
				var rcv_qty=0;
				var total_rcv_qty=0;
				var dlv_fin_qty=0;
				var dlv_grey_used_qty=0;
				var rtn_qty=0;
				var total_adjusted=0;
				var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
				opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
				rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				total_rcv_qty+=data.rows[i]['total_rcv_qty'].replace(/,/g,'')*1;
				dlv_fin_qty+=data.rows[i]['dlv_fin_qty'].replace(/,/g,'')*1;
				dlv_grey_used_qty+=data.rows[i]['dlv_grey_used_qty'].replace(/,/g,'')*1;
				rtn_qty+=data.rows[i]['rtn_qty'].replace(/,/g,'')*1;
				total_adjusted+=data.rows[i]['total_adjusted'].replace(/,/g,'')*1;
				stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
				stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				rate=stock_value/stock_qty;
				$(this).datagrid('reloadFooter', [
				{
				opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				total_rcv_qty: total_rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				dlv_fin_qty: dlv_fin_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				dlv_grey_used_qty: dlv_grey_used_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				rtn_qty: rtn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				total_adjusted: total_adjusted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		slsqty.datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsFabricStockSubconDyeingParty=new MsFabricStockSubconDyeingPartyController(new MsFabricStockSubconDyeingPartyModel());
MsFabricStockSubconDyeingParty.showGrid([]);
MsFabricStockSubconDyeingParty.receivedGrid([]);
MsFabricStockSubconDyeingParty.usedGrid([]);
MsFabricStockSubconDyeingParty.returnGrid([]);
MsFabricStockSubconDyeingParty.closingGrid([]);
