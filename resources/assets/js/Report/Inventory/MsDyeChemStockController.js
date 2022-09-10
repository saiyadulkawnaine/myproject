require('./../../datagrid-filter.js');
let MsDyeChemStockModel = require('./MsDyeChemStockModel');

class MsDyeChemStockController {
	constructor(MsDyeChemStockModel)
	{
		this.MsDyeChemStockModel = MsDyeChemStockModel;
		this.formId='dyechemstockFrm';
		this.dataTable='#dyechemstockTbl';
		this.route=msApp.baseUrl()+"/dyechemstock/getdata";
	}

	getParams(){
		let params={};
		params.company_id = $('#dyechemstockFrm  [name=company_id]').val();
		params.store_id = $('#dyechemstockFrm  [name=store_id]').val();
		params.item_category_id = $('#dyechemstockFrm  [name=item_category_id]').val();
		params.consumption_level_id = $('#dyechemstockFrm  [name=consumption_level_id]').val();
		params.date_from = $('#dyechemstockFrm  [name=date_from]').val();
		params.date_to = $('#dyechemstockFrm  [name=date_to]').val();
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
			$('#dyechemstockTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Dyes & Chemical Stock Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
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
				var openingQty=0;
				var purQty=0;
				var transInQty=0;
				var issueRtnQty=0;
				var receiveQty=0;
				var regularIssueQty=0;
				var transOutIssueQty=0;
				var rcvRtnIssueQty=0;
				var issueQty=0;
				var stockQty=0;
				var stockValue=0;
				var rate=0;
				var maxReceiveQty=0;
				var maxIssueQty=0;

				for(var i=0; i<data.rows.length; i++){
					openingQty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					purQty+=data.rows[i]['pur_qty'].replace(/,/g,'')*1;
					transInQty+=data.rows[i]['trans_in_qty'].replace(/,/g,'')*1;
					issueRtnQty+=data.rows[i]['isu_rtn_qty'].replace(/,/g,'')*1;
					receiveQty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					regularIssueQty+=data.rows[i]['regular_issue_qty'].replace(/,/g,'')*1;
					transOutIssueQty+=data.rows[i]['trans_out_issue_qty'].replace(/,/g,'')*1;
					rcvRtnIssueQty+=data.rows[i]['rcv_rtn_issue_qty'].replace(/,/g,'')*1;
					issueQty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					stockQty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stockValue+=data.rows[i]['stock_value'].replace(/,/g,'')*1;
					maxReceiveQty+=data.rows[i]['max_receive_qty'].replace(/,/g,'')*1;
					maxIssueQty+=data.rows[i]['max_issue_qty'].replace(/,/g,'')*1;

				}
				rate=stockValue/stockQty;
					$(this).datagrid('reloadFooter', [
				{
					opening_qty: openingQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					pur_qty: purQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_in_qty: transInQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					isu_rtn_qty: issueRtnQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					receive_qty: receiveQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					regular_issue_qty: regularIssueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trans_out_issue_qty: transOutIssueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_rtn_issue_qty: rcvRtnIssueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					issue_qty: issueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stockQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_value: stockValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	max_receive_qty: maxReceiveQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	max_issue_qty: maxIssueQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
window.MsDyeChemStock=new MsDyeChemStockController(new MsDyeChemStockModel());
MsDyeChemStock.showGrid([]);
//MsDyeChemStock.showGridReceiveQty([]);
//MsDyeChemStock.showGridSalesQty([]);