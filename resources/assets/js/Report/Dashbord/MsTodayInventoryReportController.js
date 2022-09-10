let MsTodayInventoryReportModel = require('./MsTodayInventoryReportModel');
require('./../../datagrid-filter.js');
class MsTodayInventoryReportController {
	constructor(MsTodayInventoryReportModel)
	{
		this.MsTodayInventoryReportModel = MsTodayInventoryReportModel;
		this.formId='todayinventoryreportFrm';
		this.dataTable='#todayinventoryreportTbl';
		this.route=msApp.baseUrl()+"/todayinventoryreport"
	}
	
	get()
	{
		let params={};
		params.trans_date = $('#todayinventoryreportFrm  [name=trans_date]').val();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#todayinventoryreportdatamatrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	general(item_category_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(msApp.baseUrl()+"/generalstock/getdata",{params})
		.then(function (response) {
			$('#todayinventorygeneralreportTbl').datagrid('loadData', response.data);
		    $('#todayinventorygeneralreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	generalGrid(data)
	{
		var dg = $('#todayinventorygeneralreportTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var stock_value=0;
				var rate=0;
				for(var i=0; i<data.rows.length; i++){
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	generalRcv(item_category_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(this.route+"/generalrcv",{params})
		.then(function (response) {
			$('#todayinventorygeneralreportRcvTbl').datagrid('loadData', response.data);
		    $('#todayinventorygeneralreportRcvWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	

	generalRcvGrid(data)
	{
		var dg = $('#todayinventorygeneralreportRcvTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var stock_value=0;
				var receive_amount=0;
				var issue_amount=0;
				var rate=0;
				for(var i=0; i<data.rows.length; i++){
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;
					receive_amount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
					issue_amount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	receive_amount: receive_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	issue_amount: issue_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	generalIsu(item_category_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(this.route+"/generalisu",{params})
		.then(function (response) {
			$('#todayinventorygeneralreportIsuTbl').datagrid('loadData', response.data);
		    $('#todayinventorygeneralreportIsuWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	generalIsuGrid(data)
	{
		var dg = $('#todayinventorygeneralreportIsuTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var stock_value=0;
				var receive_amount=0;
				var issue_amount=0;
				var rate=0;
				for(var i=0; i<data.rows.length; i++){
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;
					receive_amount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
					issue_amount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	receive_amount: receive_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	issue_amount: issue_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	yarn(item_category_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(msApp.baseUrl()+"/yarnstock/getdata",{params})
		.then(function (response) {
			$('#todayinventoryyarnreportTbl').datagrid('loadData', response.data);
		    $('#todayinventoryyarnreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	yarnGrid(data)
	{
		var dg = $('#todayinventoryyarnreportTbl');
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
				var receive_qty=0;
				//var tRate=0;
				var issue_qty=0;
				var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	yarnRcv()
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(this.route+"/yarnrcv",{params})
		.then(function (response) {
			$('#todayinventoryyarnreportRcvTbl').datagrid('loadData', response.data);
		    $('#todayinventoryyarnreportRcvWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	yarnRcvGrid(data)
	{
		var dg = $('#todayinventoryyarnreportRcvTbl');
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
				var receive_qty=0;
				var receive_amount=0;
				//var tRate=0;
				var issue_qty=0;
				var issue_amount=0;
				var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					receive_amount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issue_amount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_amount: receive_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_amount: issue_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	yarnIsu()
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(this.route+"/yarnisu",{params})
		.then(function (response) {
			$('#todayinventoryyarnreportIsuTbl').datagrid('loadData', response.data);
		    $('#todayinventoryyarnreportIsuWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	yarnIsuGrid(data)
	{
		var dg = $('#todayinventoryyarnreportIsuTbl');
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
				var receive_qty=0;
				var receive_amount=0;
				//var tRate=0;
				var issue_qty=0;
				var issue_amount=0;
				var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					receive_amount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issue_amount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_amount: receive_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_amount: issue_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	dyechem(company_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		params.company_id = company_id;
		let data= axios.get(msApp.baseUrl()+"/dyechemstock/getdata",{params})
		.then(function (response) {
			$('#todayinventorydyechemreportTbl').datagrid('loadData', response.data);
		    $('#todayinventorydyechemreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	dyechemGrid(data)
	{
		var dg = $('#todayinventorydyechemreportTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				//var opening_qty=0;
				//var receive_qty=0;
				//var issue_qty=0;
				//var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					//opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					//receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					//issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					//stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				//rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					//opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	//stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	//rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	dyechemRcv(company_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/dyechemrcv",{params})
		.then(function (response) {
			$('#todayinventorydyechemreportRcvTbl').datagrid('loadData', response.data);
		    $('#todayinventorydyechemreportRcvWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	dyechemRcvGrid(data)
	{
		var dg = $('#todayinventorydyechemreportRcvTbl');
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
				var receive_qty=0;
				var receive_amount=0;
				var issue_qty=0;
				var issue_amount=0;
				var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					receive_amount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issue_amount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_amount: receive_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	issue_amount: issue_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	dyechemIsu(company_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/dyechemisu",{params})
		.then(function (response) {
			$('#todayinventorydyechemreportIsuTbl').datagrid('loadData', response.data);
		    $('#todayinventorydyechemreportIsuWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	dyechemIsuGrid(data)
	{
		var dg = $('#todayinventorydyechemreportIsuTbl');
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
				var receive_qty=0;
				var receive_amount=0;
				var issue_qty=0;
				var issue_amount=0;
				var stock_qty=0;
				var stock_value=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					receive_amount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issue_amount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;

				}
				rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_amount: receive_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_amount: issue_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	greyfab(company_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		params.company_id = company_id;
		let data= axios.get(msApp.baseUrl()+"/greyfabstock/getdata",{params})
		.then(function (response) {
			$('#todayinventorygreyfabreportTbl').datagrid('loadData', response.data);
		    $('#todayinventorygreyfabreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	greyfabGrid(data)
	{
		var dg = $('#todayinventorygreyfabreportTbl');
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
				var pur_qty=0;
				var trans_in_qty=0;
				var isu_rtn_qty=0;
				var receive_qty=0;
				var regular_issue_qty=0;
				var trans_out_issue_qty=0;
				var rcv_rtn_issue_qty=0;
				var issue_qty=0;
				var stock_qty=0;
				var stock_value=0;
				var max_receive_qty=0;
				var max_issue_qty=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					pur_qty+=data.rows[i]['pur_qty'].replace(/,/g,'')*1;
					trans_in_qty+=data.rows[i]['trans_in_qty'].replace(/,/g,'')*1;
					isu_rtn_qty+=data.rows[i]['isu_rtn_qty'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					regular_issue_qty+=data.rows[i]['regular_issue_qty'].replace(/,/g,'')*1;
					trans_out_issue_qty+=data.rows[i]['trans_out_issue_qty'].replace(/,/g,'')*1;
					rcv_rtn_issue_qty+=data.rows[i]['rcv_rtn_issue_qty'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;
					max_receive_qty+=data.rows[i]['max_receive_qty'].replace(/,/g,'')*1;
					max_issue_qty+=data.rows[i]['max_issue_qty'].replace(/,/g,'')*1;

				}
				//rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pur_qty: pur_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_in_qty: trans_in_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					isu_rtn_qty: isu_rtn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					regular_issue_qty: regular_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_out_issue_qty: trans_out_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_rtn_issue_qty: rcv_rtn_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	max_receive_qty: max_receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	max_issue_qty: max_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	//rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	greyfabRcv(company_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/greyfabrcv",{params})
		.then(function (response) {
			$('#todayinventorygreyfabreportRcvTbl').datagrid('loadData', response.data);
		    $('#todayinventorygreyfabreportRcvWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	greyfabRcvGrid(data)
	{
		var dg = $('#todayinventorygreyfabreportRcvTbl');
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
				var pur_qty=0;
				var trans_in_qty=0;
				var isu_rtn_qty=0;
				var receive_qty=0;
				var receive_amount=0;
				var regular_issue_qty=0;
				var trans_out_issue_qty=0;
				var rcv_rtn_issue_qty=0;
				var issue_qty=0;
				var issue_amount=0;
				var stock_qty=0;
				var stock_value=0;
				var max_receive_qty=0;
				var max_issue_qty=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					pur_qty+=data.rows[i]['pur_qty'].replace(/,/g,'')*1;
					trans_in_qty+=data.rows[i]['trans_in_qty'].replace(/,/g,'')*1;
					isu_rtn_qty+=data.rows[i]['isu_rtn_qty'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					receive_amount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
					regular_issue_qty+=data.rows[i]['regular_issue_qty'].replace(/,/g,'')*1;
					trans_out_issue_qty+=data.rows[i]['trans_out_issue_qty'].replace(/,/g,'')*1;
					rcv_rtn_issue_qty+=data.rows[i]['rcv_rtn_issue_qty'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issue_amount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;
					max_receive_qty+=data.rows[i]['max_receive_qty'].replace(/,/g,'')*1;
					max_issue_qty+=data.rows[i]['max_issue_qty'].replace(/,/g,'')*1;

				}
				//rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pur_qty: pur_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_in_qty: trans_in_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					isu_rtn_qty: isu_rtn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_amount: receive_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					regular_issue_qty: regular_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_out_issue_qty: trans_out_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_rtn_issue_qty: rcv_rtn_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_amount: issue_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	max_receive_qty: max_receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	max_issue_qty: max_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	//rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	greyfabIsu(company_id)
	{
		let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		params.company_id = company_id;
		let data= axios.get(this.route+"/greyfabisu",{params})
		.then(function (response) {
			$('#todayinventorygreyfabreportIsuTbl').datagrid('loadData', response.data);
		    $('#todayinventorygreyfabreportIsuWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	greyfabIsuGrid(data)
	{
		var dg = $('#todayinventorygreyfabreportIsuTbl');
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
				var pur_qty=0;
				var trans_in_qty=0;
				var isu_rtn_qty=0;
				var receive_qty=0;
				var receive_amount=0;
				var regular_issue_qty=0;
				var trans_out_issue_qty=0;
				var rcv_rtn_issue_qty=0;
				var issue_qty=0;
				var issue_amount=0;
				var stock_qty=0;
				var stock_value=0;
				var max_receive_qty=0;
				var max_issue_qty=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					pur_qty+=data.rows[i]['pur_qty'].replace(/,/g,'')*1;
					trans_in_qty+=data.rows[i]['trans_in_qty'].replace(/,/g,'')*1;
					isu_rtn_qty+=data.rows[i]['isu_rtn_qty'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					receive_amount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
					regular_issue_qty+=data.rows[i]['regular_issue_qty'].replace(/,/g,'')*1;
					trans_out_issue_qty+=data.rows[i]['trans_out_issue_qty'].replace(/,/g,'')*1;
					rcv_rtn_issue_qty+=data.rows[i]['rcv_rtn_issue_qty'].replace(/,/g,'')*1;
					issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					issue_amount+=data.rows[i]['issue_amount'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_value+=data.rows[i]['stock_value'].replace(/,/g,'')*1;
					max_receive_qty+=data.rows[i]['max_receive_qty'].replace(/,/g,'')*1;
					max_issue_qty+=data.rows[i]['max_issue_qty'].replace(/,/g,'')*1;

				}
				//rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pur_qty: pur_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_in_qty: trans_in_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					isu_rtn_qty: isu_rtn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_amount: receive_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					regular_issue_qty: regular_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_out_issue_qty: trans_out_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_rtn_issue_qty: rcv_rtn_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_amount: issue_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stock_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	max_receive_qty: max_receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	max_issue_qty: max_issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	//rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}



	dyeingsubcon()
	{
		/*let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(msApp.baseUrl()+"/fabricstocksubcondyeingparty/getdata",{params})
		.then(function (response) {
			$('#todayinventorydyeingsubconreportTbl').datagrid('loadData', response.data);
		    $('#todayinventorydyeingsubconreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});*/
		let date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		let date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		let d= axios.get(msApp.baseUrl()+"/fabricstocksubcondyeingparty")
		.then(function (response) {
			$('#todayinventorydyeingsubconreportWindowContainer').html(response.data);
			$.parser.parse('#todayinventorydyeingsubconreportWindowContainer');
			$('#fabricstocksubcondyeingpartyFrm  [name=date_from]').val(date_from);
		    $('#fabricstocksubcondyeingpartyFrm  [name=date_to]').val(date_to);
			MsFabricStockSubconDyeingParty.get();
			$('#todayinventorydyeingsubconreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	/*dyeingsubconGrid(data)
	{
		var dg = $('#todayinventorydyeingsubconreportTbl');
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
	}*/

	aopsubcon()
	{
		/*let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(msApp.baseUrl()+"/fabricstocksubconaopparty/getdata",{params})
		.then(function (response) {
			$('#todayinventoryaopsubconreportTbl').datagrid('loadData', response.data);
		    $('#todayinventoryaopsubconreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});*/
		let date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		let date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		let d= axios.get(msApp.baseUrl()+"/fabricstocksubconaopparty")
		.then(function (response) {
			$('#todayinventoryaopsubconreportWindowContainer').html(response.data);
			$.parser.parse('#todayinventoryaopsubconreportWindowContainer');
			$('#fabricstocksubconaoppartyFrm  [name=date_from]').val(date_from);
		    $('#fabricstocksubconaoppartyFrm  [name=date_to]').val(date_to);
			MsFabricStockSubconAopParty.get();
			$('#todayinventoryaopsubconreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*aopsubconGrid(data)
	{
		var dg = $('#todayinventoryaopsubconreportTbl');
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
	}*/

	knitingsubcon()
	{
		/*let params={};
		params.date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		params.date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		//params.item_category_id = item_category_id;
		//params.company_id = company_id;
		let data= axios.get(msApp.baseUrl()+"/yarnstocksubconknitingparty/getdata",{params})
		.then(function (response) {
			$('#todayinventoryknitingsubconreportTbl').datagrid('loadData', response.data);
		    $('#todayinventoryknitingsubconreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});*/
		let date_from = $('#todayinventoryreportFrm  [name=trans_date]').val();
		let date_to = $('#todayinventoryreportFrm  [name=trans_date]').val();
		let d= axios.get(msApp.baseUrl()+"/yarnstocksubconknitingparty")
		.then(function (response) {
			$('#todayinventoryknitingsubconreportWindowContainer').html(response.data);
			$.parser.parse('#todayinventoryknitingsubconreportWindowContainer');
			$('#yarnstocksubconknitingpartyFrm  [name=date_from]').val(date_from);
		    $('#yarnstocksubconknitingpartyFrm  [name=date_to]').val(date_to);
			MsYarnStockSubconKnitingParty.get();
			$('#todayinventoryknitingsubconreportWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*knitingsubconGrid(data)
	{
		var dg = $('#todayinventoryknitingsubconreportTbl');
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
	}*/
}	
window.MsTodayInventoryReport=new MsTodayInventoryReportController(new MsTodayInventoryReportModel());
MsTodayInventoryReport.generalGrid([]);
MsTodayInventoryReport.yarnGrid([]);
MsTodayInventoryReport.dyechemGrid([]);
MsTodayInventoryReport.greyfabGrid([]);
//MsTodayInventoryReport.dyeingsubconGrid([]);
//MsTodayInventoryReport.aopsubconGrid([]);
//MsTodayInventoryReport.knitingsubconGrid([]);
MsTodayInventoryReport.generalRcvGrid([]);
MsTodayInventoryReport.generalIsuGrid([]);
MsTodayInventoryReport.yarnRcvGrid([]);
MsTodayInventoryReport.yarnIsuGrid([]);
MsTodayInventoryReport.dyechemRcvGrid([]);
MsTodayInventoryReport.dyechemIsuGrid([]);
MsTodayInventoryReport.greyfabRcvGrid([]);
MsTodayInventoryReport.greyfabIsuGrid([]);


