require('./../../datagrid-filter.js');
let MsGeneralStockAtReorderLevelModel = require('./MsGeneralStockAtReorderLevelModel');

class MsGeneralStockAtReorderLevelController {
	constructor(MsGeneralStockAtReorderLevelModel)
	{
		this.MsGeneralStockAtReorderLevelModel = MsGeneralStockAtReorderLevelModel;
		this.formId='generalstockatreorderlevelFrm';
		this.dataTable='#generalstockatreorderlevelTbl';
		this.route=msApp.baseUrl()+"/generalstockatreorderlevel/getdata";
	}

	getParams(){
		let params={};
		params.company_id = $('#generalstockatreorderlevelFrm  [name=company_id]').val();
		params.item_category_id = $('#generalstockatreorderlevelFrm  [name=item_category_id]').val();
		params.date_to = $('#generalstockatreorderlevelFrm  [name=date_to]').val();
		params.avg_of = $('#generalstockatreorderlevelFrm  [name=avg_of]').val();
		params.req_for = $('#generalstockatreorderlevelFrm  [name=req_for]').val();
		return params;
	}

	get()
	{
		let params=this.getParams();
		if(!params.date_to){
			alert('Please Select  Date Range First');
			return;
		}
		if(!params.avg_of){
			alert('Please Input  Average Of');
			return;
		}
		if(params.avg_of==0){
			alert('Please Input  Average Of');
			return;
		}
		if(!params.req_for){
			alert('Please Input  Required For');
			return;
		}
		if(params.req_for==0){
			alert('Please Input  Required For');
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#generalstockatreorderlevelTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='General Stock Report At Reorder Level: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp As On '+formatted_date_to;
		var p = $('#generalstockatreorderlevelpanel').layout('panel', 'center').panel('setTitle', title);
		
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
				var stock_qty=0;
				var req_qty=0;
				var req_amount=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					//opening_qty+=data.rows[i]['opening_qty'].replace(/,/g,'')*1;
					//receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					//issue_qty+=data.rows[i]['issue_qty'].replace(/,/g,'')*1;
					//stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					stock_qty+=data.rows[i]['stock_qty'].replace(/,/g,'')*1;
					req_qty+=data.rows[i]['req_qty'].replace(/,/g,'')*1;
					req_amount+=data.rows[i]['req_amount'].replace(/,/g,'')*1;

				}

				if(req_qty){
				rate=req_amount/req_qty;
				}

				$(this).datagrid('reloadFooter', [
				{
					//opening_qty: opening_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//issue_qty: issue_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	//stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	stock_qty: stock_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	req_qty: req_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	req_amount: req_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
window.MsGeneralStockAtReorderLevel=new MsGeneralStockAtReorderLevelController(new MsGeneralStockAtReorderLevelModel());
MsGeneralStockAtReorderLevel.showGrid([]);
//MsGeneralStock.showGridReceiveQty([]);
//MsGeneralStock.showGridSalesQty([]);