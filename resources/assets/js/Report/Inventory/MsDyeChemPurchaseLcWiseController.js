require('./../../datagrid-filter.js');
let MsDyeChemPurchaseLcWiseModel = require('./MsDyeChemPurchaseLcWiseModel');

class MsDyeChemPurchaseLcWiseController {
	constructor(MsDyeChemPurchaseLcWiseModel)
	{
		this.MsDyeChemPurchaseLcWiseModel = MsDyeChemPurchaseLcWiseModel;
		this.formId='dyechempurchaselcwiseFrm';
		this.dataTable='#dyechempurchaselcwiseTbl';
		this.route=msApp.baseUrl()+"/dyechempurchaselcwise/getdata";
	}

	getParams(){
		let params={};
		params.date_from = $('#dyechempurchaselcwiseFrm  [name=date_from]').val();
		params.date_to = $('#dyechempurchaselcwiseFrm  [name=date_to]').val();
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
			$('#dyechempurchaselcwiseTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='LC Wise Dyes & Chemical Purchase  Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#dyechempurchaselcwisePanel').layout('panel', 'center').panel('setTitle', title);
		
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
				var rate=0;
				var lc_qty=0;
				var lc_amount=0;
				var lc_rate=0;
				var balance_qty=0;
				var balance_amount=0;
				var acceptance_value=0;
				var balance_acpt=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					lc_qty+=data.rows[i]['lc_qty'].replace(/,/g,'')*1;
					lc_amount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
					balance_qty+=data.rows[i]['balance_qty'].replace(/,/g,'')*1;
					balance_amount+=data.rows[i]['balance_amount'].replace(/,/g,'')*1;
					acceptance_value+=data.rows[i]['acceptance_value'].replace(/,/g,'')*1;
					balance_acpt+=data.rows[i]['balance_acpt'].replace(/,/g,'')*1;

				}
				rate=amount/qty;
				lc_rate=lc_amount/lc_qty;
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_qty: lc_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_amount: lc_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_rate: lc_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					balance_qty: balance_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					balance_amount: balance_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					acceptance_value: acceptance_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					balance_acpt: balance_acpt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	formatLcQty(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsDyeChemPurchaseLcWise.detailsLcQtyWindow('+row.imp_lc_id+')">'+row.lc_qty+'</a>';
	}

	detailsLcQtyWindow(imp_lc_id){
		let params={}	
		params.imp_lc_id=imp_lc_id;
		let data= axios.get(msApp.baseUrl()+"/dyechempurchaselcwise/getlcqtydtl",{params});
		let g=data.then(function (response) {
		$('#lcwisedyechempurlcqtydtlTbl').datagrid('loadData', response.data);
		$('#lcwisedyechempurlcqtydtlWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
        });
        return g;
	}

	showGridLcQty(data)
	{
		var dg = $('#lcwisedyechempurlcqtydtlTbl');
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

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				rate=amount/qty;
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatRcvQty(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsDyeChemPurchaseLcWise.detailsRcvQtyWindow('+row.imp_lc_id+')">'+row.qty+'</a>';
	}

	detailsRcvQtyWindow(imp_lc_id){
		let params={}	
		params.imp_lc_id=imp_lc_id;
		let data= axios.get(msApp.baseUrl()+"/dyechempurchaselcwise/getrcvqtydtl",{params});
		let rc=data.then(function (response) {
		    $('#lcwisedyechempurrcvqtydtlTbl').datagrid('loadData', response.data);
		    $('#lcwisedyechempurrcvqtydtlWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
        });
        return rc;
	}

	showGridRcvQty(data)
	{
		var drc = $('#lcwisedyechempurrcvqtydtlTbl');
		drc.datagrid({
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
				var rcv_rtn_qty=0;
				var rcv_rtn_amount=0;
				var net_qty=0;
				var net_amount=0;
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					rcv_rtn_qty+=data.rows[i]['rcv_rtn_qty'].replace(/,/g,'')*1;
					rcv_rtn_amount+=data.rows[i]['rcv_rtn_amount'].replace(/,/g,'')*1;
					net_qty+=data.rows[i]['net_qty'].replace(/,/g,'')*1;
					net_amount+=data.rows[i]['net_amount'].replace(/,/g,'')*1;
				}
				rate=amount/qty;
				$('#lcwisedyechempurrcvqtydtlTbl').datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_rtn_qty: rcv_rtn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_rtn_amount: rcv_rtn_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					net_qty: net_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					net_amount: net_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
				
			}
		});
		drc.datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsDyeChemPurchaseLcWise=new MsDyeChemPurchaseLcWiseController(new MsDyeChemPurchaseLcWiseModel());
MsDyeChemPurchaseLcWise.showGrid([]);
MsDyeChemPurchaseLcWise.showGridLcQty([]);
MsDyeChemPurchaseLcWise.showGridRcvQty([]);