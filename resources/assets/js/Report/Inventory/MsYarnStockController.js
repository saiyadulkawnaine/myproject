require('./../../datagrid-filter.js');
let MsYarnStockModel = require('./MsYarnStockModel');

class MsYarnStockController {
	constructor(MsYarnStockModel)
	{
		this.MsYarnStockModel = MsYarnStockModel;
		this.formId='yarnstockFrm';
		this.dataTable='#yarnstockTbl';
		this.route=msApp.baseUrl()+"/yarnstock/getdata";
	}

	getParams(){
		let params={};
		params.store_id = $('#yarnstockFrm  [name=store_id]').val();
		params.date_from = $('#yarnstockFrm  [name=date_from]').val();
		params.date_to = $('#yarnstockFrm  [name=date_to]').val();
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
			$('#yarnstockTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Yarn Stock Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
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
window.MsYarnStock=new MsYarnStockController(new MsYarnStockModel());
MsYarnStock.showGrid([]);
//MsYarnStock.showGridReceiveQty([]);
//MsYarnStock.showGridSalesQty([]);