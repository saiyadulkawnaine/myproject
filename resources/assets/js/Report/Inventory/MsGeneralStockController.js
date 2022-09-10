require('./../../datagrid-filter.js');
let MsGeneralStockModel = require('./MsGeneralStockModel');

class MsGeneralStockController {
	constructor(MsGeneralStockModel)
	{
		this.MsGeneralStockModel = MsGeneralStockModel;
		this.formId='generalstockFrm';
		this.dataTable='#generalstockTbl';
		this.route=msApp.baseUrl()+"/generalstock/getdata";
	}

	getParams(){
		let params={};
		params.company_id = $('#generalstockFrm  [name=company_id]').val();
		params.store_id = $('#generalstockFrm  [name=store_id]').val();
		params.item_category_id = $('#generalstockFrm  [name=item_category_id]').val();
		params.consumption_level_id = $('#dyechemstockFrm  [name=consumption_level_id]').val();
		params.date_from = $('#generalstockFrm  [name=date_from]').val();
		params.date_to = $('#generalstockFrm  [name=date_to]').val();
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
			$('#generalstockTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='General Stock Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
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

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

}
window.MsGeneralStock=new MsGeneralStockController(new MsGeneralStockModel());
MsGeneralStock.showGrid([]);
//MsGeneralStock.showGridReceiveQty([]);
//MsGeneralStock.showGridSalesQty([]);