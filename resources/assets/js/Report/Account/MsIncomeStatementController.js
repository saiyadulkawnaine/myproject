//require('./../../jquery.easyui.min.js');
let MsIncomeStatementModel = require('./MsIncomeStatementModel');
//require('./../../datagrid-filter.js');

class MsIncomeStatementController {
	constructor(MsIncomeStatementModel)
	{
		this.MsIncomeStatementModel = MsIncomeStatementModel;
		this.formId='incomestatementFrm';
		this.dataTable='#incomestatementTbl';
		this.route=msApp.baseUrl()+"/incomestatement/html";
	}

	get(){
		let params={};
		params.company_id = $('#incomestatementFrm  [name=company_id]').val();
		params.acc_year_id = $('#incomestatementFrm  [name=acc_year_id]').val();
		params.from_periods = $('#incomestatementFrm  [name=from_periods]').val();
		params.to_periods = $('#incomestatementFrm  [name=to_periods]').val();
		params.level = $('#incomestatementFrm  [name=level]').val();
		params.profitcenter_id = $('#incomestatementFrm  [name=profitcenter_id]').val();

		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		if(params.acc_year_id=='' || params.acc_year_id==0){
			alert('Select Year');
			return;
		}
		if(params.from_periods=='' || params.from_periods==0){
			alert('Select Priods');
			return;
		}

		if(params.to_periods=='' || params.to_periods==0){
			params.to_periods=params.from_periods;
			$('#incomestatementFrm  [name=to_periods]').val(params.from_periods)
		}
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#incomestatementcontainer').html(response.data);
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

	getYear(company_id){
		let params={};
		params.company_id=company_id;
		let d= axios.get(msApp.baseUrl()+"/incomestatement/getYear",{params})
		.then(function (response) {
			MsIncomeStatement.setYear(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setYear(data)
	{
		$('select[name="acc_year_id"]').empty();
		$('select[name="acc_year_id"]').append('<option value="">-Select-</option>');
		$.each(data, function(key, value) {
			$('select[name="acc_year_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
			if(value.is_current==1)
			{
				$('#incomestatementFrm  [name=acc_year_id]').val(value.id);
				MsIncomeStatement.getPeriods(value.id)
				//$('#incomestatementFrm  [name=date_from]').val(value.start_date);
				//$('#incomestatementFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getPeriods(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/incomestatement/getPeriods",{params})
		.then(function (response) {
			MsIncomeStatement.SetPeriods(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	SetPeriods(data)
	{
		$('select[name="from_periods"]').empty();
		$('select[name="from_periods"]').append('<option value="">-Select-</option>');
		$('select[name="to_periods"]').empty();
		$('select[name="to_periods"]').append('<option value="">-Select-</option>');
		$.each(data, function(key, value) {
			$('select[name="from_periods"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
			$('select[name="to_periods"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
			
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){

		let params={};
		params.company_id = $('#incomestatementFrm  [name=company_id]').val();
		params.acc_year_id = $('#incomestatementFrm  [name=acc_year_id]').val();
		params.from_periods = $('#incomestatementFrm  [name=from_periods]').val();
		params.to_periods = $('#incomestatementFrm  [name=to_periods]').val();
		params.level = $('#incomestatementFrm  [name=level]').val();
		params.profitcenter_id = $('#incomestatementFrm  [name=profitcenter_id]').val();

		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		if(params.acc_year_id=='' || params.acc_year_id==0){
			alert('Select Year');
			return;
		}
		if(params.from_periods=='' || params.from_periods==0){
			alert('Select Priods');
			return;
		}

		if(params.to_periods=='' || params.to_periods==0){
			params.to_periods=params.from_periods;
			$('#incomestatementFrm  [name=to_periods]').val(params.from_periods)
		}


		let company_id = $('#incomestatementFrm  [name=company_id]').val();
		let acc_year_id = $('#incomestatementFrm  [name=acc_year_id]').val();
		let date_from = $('#incomestatementFrm  [name=date_from]').val();
		let date_to = $('#incomestatementFrm  [name=date_to]').val();
		let from_periods = $('#incomestatementFrm  [name=from_periods]').val();
		let to_periods = $('#incomestatementFrm  [name=to_periods]').val();
		let level = $('#incomestatementFrm  [name=level]').val();
		let profitcenter_id = $('#incomestatementFrm  [name=profitcenter_id]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}

		if(from_periods=='' || params.from_periods==0){
			alert('Select Priods');
			return;
		}

		if(to_periods=='' || to_periods==0){
			to_periods=from_periods;
			$('#incomestatementFrm  [name=to_periods]').val(from_periods)
		}
		window.open(msApp.baseUrl()+"/incomestatement/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&from_periods="+from_periods+"&to_periods="+to_periods+"&level="+level+'&profitcenter_id='+profitcenter_id);
	}

}
window.MsIncomeStatement=new MsIncomeStatementController(new MsIncomeStatementModel());
MsIncomeStatement.showGrid([]);
