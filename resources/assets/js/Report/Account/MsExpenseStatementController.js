let MsExpenseStatementModel = require('./MsExpenseStatementModel');

class MsExpenseStatementController {
	constructor(MsExpenseStatementModel)
	{
		this.MsExpenseStatementModel = MsExpenseStatementModel;
		this.formId='expensestatementFrm';
		this.dataTable='#expensestatementTbl';
		this.route=msApp.baseUrl()+"/expensestatement/html";
	}

	get(){
		let params={};
		params.company_id = $('#expensestatementFrm  [name=company_id]').val();
		params.date_from = $('#expensestatementFrm  [name=date_from]').val();
		params.date_to = $('#expensestatementFrm  [name=date_to]').val();
		params.profitcenter_id = $('#expensestatementFrm  [name=profitcenter_id]').val();

		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		// if(params.acc_year_id=='' || params.acc_year_id==0){
		// 	alert('Select Year');
		// 	return;
		// }
		// if(params.from_periods=='' || params.from_periods==0){
		// 	alert('Select Priods');
		// 	return;
		// }

		// if(params.to_periods=='' || params.to_periods==0){
		// 	params.to_periods=params.from_periods;
		// 	$('#expensestatementFrm  [name=to_periods]').val(params.from_periods)
		// }
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#expensestatementcontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
            groupField:'account',
		});
		dg.datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){

		let params={};
		params.company_id = $('#expensestatementFrm  [name=company_id]').val();
		params.date_from = $('#expensestatementFrm  [name=date_from]').val();
		params.date_to = $('#expensestatementFrm  [name=date_to]').val();
		params.profitcenter_id = $('#expensestatementFrm  [name=profitcenter_id]').val();

		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		if(params.acc_year_id=='' || params.acc_year_id==0){
			alert('Select Year');
			return;
		}
		if(params.date_from=='' || params.date_from==0){
			alert('Select Date From');
			return;
		}

		if(params.date_to=='' || params.date_to==0){
			params.date_to=params.date_from;
			$('#expensestatementFrm  [name=date_to]').val(params.date_from)
		}


		let company_id = $('#expensestatementFrm  [name=company_id]').val();
		let date_from = $('#expensestatementFrm  [name=date_from]').val();
		let date_to = $('#expensestatementFrm  [name=date_to]').val();
		let profitcenter_id = $('#expensestatementFrm  [name=profitcenter_id]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}


		if(date_from=='' || params.date_from==0){
			alert('Select Date from');
			return;
		}

		if(date_to=='' || date_to==0){
			date_to=date_from;
			$('#expensestatementFrm  [name=date_to]').val(date_from)
		}
		window.open(msApp.baseUrl()+"/expensestatement/pdf?company_id="+company_id+"&date_from="+date_from+"&date_to="+date_to+"&profitcenter_id="+profitcenter_id);
	}

}
window.MsExpenseStatement=new MsExpenseStatementController(new MsExpenseStatementModel());
MsExpenseStatement.showGrid([]);
