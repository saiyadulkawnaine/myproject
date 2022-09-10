require('./../../datagrid-filter.js');
let MsGreyFabStockModel = require('./MsGreyFabStockModel');

class MsGreyFabStockController {
	constructor(MsGreyFabStockModel)
	{
		this.MsGreyFabStockModel = MsGreyFabStockModel;
		this.formId='greyfabstockFrm';
		this.dataTable='#greyfabstockTbl';
		this.route=msApp.baseUrl()+"/greyfabstock/getdata";
	}

	getParams(){
		let params={};
		params.company_id = $('#greyfabstockFrm  [name=company_id]').val();
		params.store_id = $('#greyfabstockFrm  [name=store_id]').val();
		//params.item_category_id = $('#greyfabstockFrm  [name=item_category_id]').val();
		params.date_from = $('#greyfabstockFrm  [name=date_from]').val();
		params.date_to = $('#greyfabstockFrm  [name=date_to]').val();
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
			$('#greyfabstockTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Grey Fabric Stock Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#greyfabstck').layout('panel', 'center').panel('setTitle', title);
		
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

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

}
window.MsGreyFabStock=new MsGreyFabStockController(new MsGreyFabStockModel());
MsGreyFabStock.showGrid([]);
//MsGreyFabStock.showGridReceiveQty([]);
//MsGreyFabStock.showGridSalesQty([]);