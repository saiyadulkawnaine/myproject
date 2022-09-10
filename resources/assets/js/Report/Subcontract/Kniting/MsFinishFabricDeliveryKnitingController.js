let MsFinishFabricDeliveryKnitingModel = require('./MsFinishFabricDeliveryKnitingModel');
require('./../../../datagrid-filter.js');

class MsFinishFabricDeliveryKnitingController {
	constructor(MsFinishFabricDeliveryKnitingModel)
	{
		this.MsFinishFabricDeliveryKnitingModel = MsFinishFabricDeliveryKnitingModel;
		this.formId='finishfabricdeliveryknitingFrm';
		this.dataTable='#finishfabricdeliveryknitingTbl';
		this.route=msApp.baseUrl()+"/finishfabricdeliverykniting";
	}
	
	getSelf(){
		let params={};
		params.date_from = $('#finishfabricdeliveryknitingFrm  [name=date_from]').val();
		params.date_to = $('#finishfabricdeliveryknitingFrm  [name=date_to]').val();
		params.company_id = $('#finishfabricdeliveryknitingFrm  [name=company_id]').val();
		params.buyer_id = $('#finishfabricdeliveryknitingFrm  [name=buyer_id]').val();

		let d= axios.get(this.route+"/getdataself",{params})
		.then(function (response) {
			$('#finishfabricdeliveryknitingTbl').datagrid('loadData', response.data);
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
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount=0;
				//var grey_wgt=0;
				var rate=0;
				var no_of_roll=0
				var amount_bdt=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					//grey_wgt+=data.rows[i]['grey_wgt'].replace(/,/g,'')*1;
					no_of_roll+=data.rows[i]['no_of_roll'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				
				if (qty) {
					rate=amount/qty;	
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//grey_wgt: grey_wgt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					no_of_roll: no_of_roll.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount_bdt: amount_bdt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	
	getSubcontract(){
		let params={};
		params.date_from = $('#finishfabricdeliveryknitingFrm  [name=date_from]').val();
		params.date_to = $('#finishfabricdeliveryknitingFrm  [name=date_to]').val();
		params.company_id = $('#finishfabricdeliveryknitingFrm  [name=company_id]').val();
		params.buyer_id = $('#finishfabricdeliveryknitingFrm  [name=buyer_id]').val();

		let d= axios.get(this.route+"/getdatasubcontract",{params})
		.then(function (response) {
			$('#subcontractWindow').window('open');
			$('#subcontractfinishfabricdeliveryknitingTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showSubcontractGrid(data)
	{
		var sdg = $('#subcontractfinishfabricdeliveryknitingTbl');
		sdg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount=0;
				//var grey_wgt=0;
				var rate=0;
				var no_of_roll=0;
				var amount_bdt=0;

				var rate=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					//grey_wgt+=data.rows[i]['grey_wgt'].replace(/,/g,'')*1;
					no_of_roll+=data.rows[i]['no_of_roll'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				
				if (qty) {
					rate=amount/qty;	
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					//grey_wgt: grey_wgt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					no_of_roll: no_of_roll.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount_bdt: amount_bdt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		sdg.datagrid('enableFilter').datagrid('loadData', data);
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsFinishFabricDeliveryKniting= new MsFinishFabricDeliveryKnitingController(new MsFinishFabricDeliveryKnitingModel());
MsFinishFabricDeliveryKniting.showGrid([]);
MsFinishFabricDeliveryKniting.showSubcontractGrid([]);