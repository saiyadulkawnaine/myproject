//require('./../../jquery.easyui.min.js');
let MsBalanceSheetModel = require('./MsBalanceSheetModel');
//require('./../../datagrid-filter.js');

class MsBalanceSheetController {
	constructor(MsBalanceSheetModel)
	{
		this.MsBalanceSheetModel = MsBalanceSheetModel;
		this.formId='balancesheetFrm';
		this.dataTable='#balancesheetTbl';
		this.route=msApp.baseUrl()+"/balancesheet/html";
	}

	get(){
		let params={};
		params.company_id = $('#balancesheetFrm  [name=company_id]').val();
		params.acc_year_id = $('#balancesheetFrm  [name=acc_year_id]').val();
		//params.from_periods = $('#balancesheetFrm  [name=from_periods]').val();
		//params.to_periods = $('#balancesheetFrm  [name=to_periods]').val();
		params.date_to = $('#balancesheetFrm  [name=date_to]').val();
		params.level = $('#balancesheetFrm  [name=level]').val();

		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		if(params.acc_year_id=='' || params.acc_year_id==0){
			alert('Select Year');
			return;
		}

		if(params.date_to=='' || params.date_to==0){
			alert('Select As on Date');
			return;
		}

		/*if(params.from_periods=='' || params.from_periods==0){
			alert('Select Priods');
			return;
		}

		if(params.to_periods=='' || params.to_periods==0){
			params.to_periods=params.from_periods;
			$('#balancesheetFrm  [name=to_periods]').val(params.from_periods)
		}*/
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#balancesheetcontainer').html(response.data);
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
		let d= axios.get(msApp.baseUrl()+"/balancesheet/getYear",{params})
		.then(function (response) {
			MsBalanceSheet.setYear(response.data);
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
				$('#balancesheetFrm  [name=acc_year_id]').val(value.id);
				//MsBalanceSheet.getPeriods(value.id)
			}
		});
	}

	getPeriods(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/balancesheet/getPeriods",{params})
		.then(function (response) {
			MsBalanceSheet.SetPeriods(response.data);
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

		


		let company_id = $('#balancesheetFrm  [name=company_id]').val();
		let acc_year_id = $('#balancesheetFrm  [name=acc_year_id]').val();
		let date_from = $('#balancesheetFrm  [name=date_from]').val();
		let date_to = $('#balancesheetFrm  [name=date_to]').val();
		//let from_periods = $('#balancesheetFrm  [name=from_periods]').val();
		//let to_periods = $('#balancesheetFrm  [name=to_periods]').val();
		//let date_to = $('#balancesheetFrm  [name=date_to]').val();
		let level = $('#balancesheetFrm  [name=level]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		if(date_to=='' || date_to==0){
			alert('Select As on Date');
			return;
		}

		/*if(from_periods=='' || params.from_periods==0){
			alert('Select Priods');
			return;
		}

		if(to_periods=='' || to_periods==0){
			to_periods=from_periods;
			$('#balancesheetFrm  [name=to_periods]').val(from_periods)
		}*/
		window.open(msApp.baseUrl()+"/balancesheet/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_to="+date_to+"&level="+level);
	}

}
window.MsBalanceSheet=new MsBalanceSheetController(new MsBalanceSheetModel());
MsBalanceSheet.showGrid([]);
