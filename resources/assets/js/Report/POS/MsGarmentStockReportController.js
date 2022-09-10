require('./../../datagrid-filter.js');
let MsGarmentStockReportModel = require('./MsGarmentStockReportModel');

class MsGarmentStockReportController {
	constructor(MsGarmentStockReportModel)
	{
		this.MsGarmentStockReportModel = MsGarmentStockReportModel;
		this.formId='garmentstockreportFrm';
		this.dataTable='#garmentstockreportTbl';
		this.route=msApp.baseUrl()+"/garmentstockreport/getdata";
	}

	getParams(){
		let params={};
		params.style_gmt_name = $('#garmentstockreportFrm  [name=style_gmt_name]').val();
		params.color_name = $('#garmentstockreportFrm  [name=color_name]').val();
		params.size_name = $('#garmentstockreportFrm  [name=size_name]').val();
		params.date_from = $('#garmentstockreportFrm  [name=date_from]').val();
		params.date_to = $('#garmentstockreportFrm  [name=date_to]').val();
		/*let d= axios.get(this.route,{params})
		.then(function (response) {
			//MsGarmentStockReport.showGrid(response.data.datad)
			$('#garmentstockreportTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});*/
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
			$('#garmentstockreportTbl').datagrid('loadData', response.data);
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
				var tReceiveQty=0;
				var tSaleQty=0;
				//var tRate=0;
				var tOpeningStock=0;
				var totalQty=0;
				var tClosingStock=0;
				var tStockValue=0;
				var tSoldValue=0;

				for(var i=0; i<data.rows.length; i++){
					tReceiveQty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					tSaleQty+=data.rows[i]['sale_qty'].replace(/,/g,'')*1;
					//tRate+=data.rows[i]['rate'].replace(/,/g,'')*1;
					tOpeningStock+=data.rows[i]['opening_stock'].replace(/,/g,'')*1;
					totalQty+=data.rows[i]['total_qty'].replace(/,/g,'')*1;
					tClosingStock+=data.rows[i]['closing_stock'].replace(/,/g,'')*1;
					tStockValue+=data.rows[i]['stock_value'].replace(/,/g,'')*1;
					tSoldValue+=data.rows[i]['sale_amount'].replace(/,/g,'')*1;

				}
				tRate=tStockValue/tClosingStock;
					$(this).datagrid('reloadFooter', [
				{
					receive_qty: tReceiveQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sale_qty: tSaleQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	opening_stock: tOpeningStock.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	total_qty: totalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	closing_stock: tClosingStock.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: tStockValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	sale_amount: tSoldValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	receiveqtyWindow(style_id,style_gmt_name,size_name,color_name){
		let params=this.getParams();
		params.style_id=style_id;
		params.style_gmt_name=style_gmt_name;
		params.size_name=size_name;
		params.color_name=color_name;
		// params.date_from=date_from;
		// params.date_to=date_to;
		let data= axios.get(msApp.baseUrl()+"/garmentstockreport/getreceiveqty" ,{params});
		let ic=data.then(function (response) {
			//alert()
			$('#receiveqtyStockWindow').window('open');
			$('#receiveqtyTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return ic;
	}

	showGridReceiveQty(data){
		var rcvqty = $('#receiveqtyTbl');
		rcvqty.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tReceiveQty=0;
			//var tRate=0;
			var tReceiveAmount=0;
			
			for(var i=0; i<data.rows.length; i++){
				tReceiveQty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
				//tCpuRate+=data.rows[i]['receive_rate'].replace(/,/g,'')*1;
				tReceiveAmount+=data.rows[i]['receive_amount'].replace(/,/g,'')*1;
			}
			tCpuRate=tReceiveAmount/tReceiveQty;
			$(this).datagrid('reloadFooter', [
				{
					receive_qty: tReceiveQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_rate: tCpuRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_amount: tReceiveAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}

		});
		rcvqty.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatReceiveQty(value,row){
		return '<a href="javascript:void(0)" onClick="MsGarmentStockReport.receiveqtyWindow('+'\''+row.style_id+'\''+','+'\''+row.style_gmt_name+'\''+','+'\''+row.size_name+'\''+','+'\''+row.color_name+'\''+')">'+row.receive_qty+'</a>';
		/* ,'+'\''+row.date_from+'\''+','+'\''+row.date_to+'\''+' */
	}

	salesQtyWindow(style_id,style_gmt_name,size_name,color_name){
		let params=this.getParams();
		params.style_id=style_id;
		params.style_gmt_name=style_gmt_name;
		params.size_name=size_name;
		params.color_name=color_name;
		let data= axios.get(msApp.baseUrl()+"/garmentstockreport/getsalesqty" ,{params});
		let sq=data.then(function (response) {
			//alert()
			$('#salesQtyWindow').window('open');
			$('#salesqtyTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		return sq;
	}

	showGridSalesQty(data){
		var slsqty = $('#salesqtyTbl');
		slsqty.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		onLoadSuccess: function(data){
			var tSalesQty=0;
			var tSalesAmount=0;
			
			for(var i=0; i<data.rows.length; i++){
				tSalesQty+=data.rows[i]['sale_qty'].replace(/,/g,'')*1;
				tSalesAmount+=data.rows[i]['sale_amount'].replace(/,/g,'')*1;
			}
			tSpuRate=tSalesAmount/tSalesQty;
			$(this).datagrid('reloadFooter', [
				{
					sale_qty: tSalesQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sale_rate: tSpuRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					sale_amount: tSalesAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
			]);
		}

		});
		slsqty.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatSalesQty(value,row){
		return '<a href="javascript:void(0)" onClick="MsGarmentStockReport.salesQtyWindow('+'\''+row.style_id+'\''+','+'\''+row.style_gmt_name+'\''+','+'\''+row.size_name+'\''+','+'\''+row.color_name+'\''+')">'+row.sale_amount+'</a>';
	}


}
window.MsGarmentStockReport=new MsGarmentStockReportController(new MsGarmentStockReportModel());
MsGarmentStockReport.showGrid([]);
MsGarmentStockReport.showGridReceiveQty([]);
MsGarmentStockReport.showGridSalesQty([]);