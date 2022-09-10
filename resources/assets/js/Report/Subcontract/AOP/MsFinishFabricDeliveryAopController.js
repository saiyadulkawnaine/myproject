let MsFinishFabricDeliveryAopModel = require('./MsFinishFabricDeliveryAopModel');
require('./../../../datagrid-filter.js');

class MsFinishFabricDeliveryAopController {
	constructor(MsFinishFabricDeliveryAopModel)
	{
		this.MsFinishFabricDeliveryAopModel = MsFinishFabricDeliveryAopModel;
		this.formId='finishfabricdeliveryaopFrm';
		this.dataTable='#finishfabricdeliveryaopTbl';
		this.route=msApp.baseUrl()+"/finishfabricdeliveryaop";
	}
	
	getSelf(){
		let params={};
		params.date_from = $('#finishfabricdeliveryaopFrm  [name=date_from]').val();
		params.date_to = $('#finishfabricdeliveryaopFrm  [name=date_to]').val();
		params.company_id = $('#finishfabricdeliveryaopFrm  [name=company_id]').val();
		params.buyer_id = $('#finishfabricdeliveryaopFrm  [name=buyer_id]').val();

		let d= axios.get(this.route+"/getdataself",{params})
		.then(function (response) {
			$('#finishfabricdeliveryaopTbl').datagrid('loadData', response.data);
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
				var grey_wgt=0;
				var rate=0;
				var no_of_roll=0
				var amount_bdt=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					grey_wgt+=data.rows[i]['grey_wgt'].replace(/,/g,'')*1;
					no_of_roll+=data.rows[i]['no_of_roll'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				
				if (grey_wgt) {
					rate=amount/grey_wgt;	
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_wgt: grey_wgt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
		params.date_from = $('#finishfabricdeliveryaopFrm  [name=date_from]').val();
		params.date_to = $('#finishfabricdeliveryaopFrm  [name=date_to]').val();
		params.company_id = $('#finishfabricdeliveryaopFrm  [name=company_id]').val();
		params.buyer_id = $('#finishfabricdeliveryaopFrm  [name=buyer_id]').val();

		let d= axios.get(this.route+"/getdatasubcontract",{params})
		.then(function (response) {
			$('#subcontractWindow').window('open');
			$('#subcontractfinishfabricdeliveryaopTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showSubcontractGrid(data)
	{
		var sdg = $('#subcontractfinishfabricdeliveryaopTbl');
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
				var grey_wgt=0;
				var rate=0;
				var no_of_roll=0;
				var amount_bdt=0;

				var rate=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					grey_wgt+=data.rows[i]['grey_wgt'].replace(/,/g,'')*1;
					no_of_roll+=data.rows[i]['no_of_roll'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				
				if (grey_wgt) {
					rate=amount/grey_wgt;	
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_wgt: grey_wgt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
window.MsFinishFabricDeliveryAop= new MsFinishFabricDeliveryAopController(new MsFinishFabricDeliveryAopModel());
MsFinishFabricDeliveryAop.showGrid([]);
MsFinishFabricDeliveryAop.showSubcontractGrid([]);