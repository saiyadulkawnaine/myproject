require('./../../datagrid-filter.js');
let MsYarnPurchaseSummeryModel = require('./MsYarnPurchaseSummeryModel');

class MsYarnPurchaseSummeryController {
	constructor(MsYarnPurchaseSummeryModel)
	{
		this.MsYarnPurchaseSummeryModel = MsYarnPurchaseSummeryModel;
		this.formId='yarnpurchasesummeryFrm';
		this.dataTable='#yarnpurchasesummeryTbl';
		this.route=msApp.baseUrl()+"/yarnpurchasesummery/getdata";
	}

	getParams(){
		let params={};
		params.date_from = $('#yarnpurchasesummeryFrm  [name=date_from]').val();
		params.date_to = $('#yarnpurchasesummeryFrm  [name=date_to]').val();
		params.company_id = $('#yarnpurchasesummeryFrm  [name=company_id]').val();
		params.supplier_id = $('#yarnpurchasesummeryFrm  [name=supplier_id]').val();
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
			$('#yarnpurchasesummeryTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Yarn Purchase Summery Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#yarnpurchasesummeryPanel').layout('panel', 'center').panel('setTitle', title);
		
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

				var qty=0;
				var amount=0;
				var no_of_bag=0;
				var rate=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					no_of_bag+=data.rows[i]['no_of_bag'].replace(/,/g,'')*1;

				}
				rate=amount/qty;
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					no_of_bag: no_of_bag.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	formatRcvQty(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsYarnPurchaseSummery.detailsRcvQtyWindow('+row.item_account_id+')">'+row.qty+'</a>';
	}

	detailsRcvQtyWindow(item_account_id){
		let params=this.getParams();
		
		params.item_account_id=item_account_id;
		let data= axios.get(msApp.baseUrl()+"/yarnpurchasesummery/getrcvqtydtl",{params});
		let g=data.then(function (response) {
		$('#yarnpursummeryrcvqtydtlTbl').datagrid('loadData', response.data);
		$('#yarnpursummeryrcvqtydtlWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridRcvQty(data)
	{
		var dg = $('#yarnpursummeryrcvqtydtlTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){

				var qty=0;
				var amount=0;
				var rate=0;
				var no_of_bag=0;
				


				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					no_of_bag+=data.rows[i]['no_of_bag'].replace(/,/g,'')*1;
				}
				rate=amount/qty;
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					no_of_bag: no_of_bag.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsYarnPurchaseSummery=new MsYarnPurchaseSummeryController(new MsYarnPurchaseSummeryModel());
MsYarnPurchaseSummery.showGrid([]);
MsYarnPurchaseSummery.showGridRcvQty([]);