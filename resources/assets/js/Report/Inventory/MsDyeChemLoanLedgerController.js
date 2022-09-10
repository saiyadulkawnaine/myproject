let MsDyeChemLoanLedgerModel = require('./MsDyeChemLoanLedgerModel');
require('./../../datagrid-filter.js');

class MsDyeChemLoanLedgerController {
	constructor(MsDyeChemLoanLedgerModel)
	{
		this.MsDyeChemLoanLedgerModel = MsDyeChemLoanLedgerModel;
		this.formId='dyechemloanledgerFrm';
		this.dataTable='#dyechemloanledgerTbl';
		this.route=msApp.baseUrl()+"/dyechemloanledger/getdata"
	}

	getParams(){
		let params={};
		params.store_id = $('#dyechemloanledgerFrm  [name=store_id]').val();
		params.date_from = $('#dyechemloanledgerFrm  [name=date_from]').val();
		params.date_to = $('#dyechemloanledgerFrm  [name=date_to]').val();
		params.company_id = $('#dyechemloanledgerFrm  [name=company_id]').val();
		params.supplier_id = $('#dyechemloanledgerFrm  [name=supplier_id]').val();
		return params;
	}
	
	get(){
		let params=this.getParams();
        // if(!params.menu_id){
		// 	alert('Select A Menu Name First ');
		// 	return;
		// }
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
            //$('#dyechemloanledgerTbl').datagrid('loadData', response.data);
            $('#dyechemloanledgermatrix').html(response.data);
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
			//showFooter:true,
			fit:true,
			//rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	pdf(){
		let store_id = $('#dyechemloanledgerFrm  [name=store_id]').val();
		let date_from = $('#dyechemloanledgerFrm  [name=date_from]').val();
		let date_to = $('#dyechemloanledgerFrm  [name=date_to]').val();
		let company_id = $('#dyechemloanledgerFrm  [name=company_id]').val();
		let supplier_id = $('#dyechemloanledgerFrm  [name=supplier_id]').val();
		// if(company_id=='' || company_id==0){
		// 	alert('Select Company');
		// 	return;
		// }
		if(date_from=='' || date_from==0){
			alert('Select As on Date');
			return;
		}
		if(date_to=='' || date_to==0){
			alert('Select As on Date');
			return;
		}
		window.open(msApp.baseUrl()+"/dyechemloanledger/getpdf?company_id="+company_id+"&store_id="+store_id+"&date_from="+date_from+"&date_to="+date_to+"&supplier_id="+supplier_id);
	}

}
window.MsDyeChemLoanLedger = new MsDyeChemLoanLedgerController(new MsDyeChemLoanLedgerModel());
MsDyeChemLoanLedger.showGrid({rows :{}});;