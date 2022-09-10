//require('./../../jquery.easyui.min.js');
let MsTbModel = require('./MsTbModel');
require('./../../datagrid-filter.js');

class MsTbController {
	constructor(MsTbModel)
	{
		this.MsTbModel = MsTbModel;
		this.formId='tbFrm';
		this.dataTable='#tbTbl';
		this.route=msApp.baseUrl()+"/tb/html";
	}

	get(){
		let params={};
		params.company_id = $('#tbFrm  [name=company_id]').val();
		params.acc_year_id = $('#tbFrm  [name=acc_year_id]').val();
		//params.date_from = $('#tbFrm  [name=date_from]').val();
		params.date_to = $('#tbFrm  [name=date_to]').val();
		params.code_from = $('#tbFrm  [name=code_from]').val();
		params.code_to = $('#tbFrm  [name=code_to]').val();

		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		if(params.acc_year_id=='' || params.acc_year_id==0){
			alert('Select Year');
			return;
		}
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#tbcontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}

	getGl(coa_id){
		let params={};
		params.company_id = $('#tbFrm  [name=company_id]').val();
		params.acc_year_id = $('#tbFrm  [name=acc_year_id]').val();
		//params.date_from = $('#tbFrm  [name=date_from]').val();
		params.date_to = $('#tbFrm  [name=date_to]').val();
		params.code_from = $('#tbFrm  [name=code_from]').val();
		params.code_to = $('#tbFrm  [name=code_to]').val();
		params.coa_id = coa_id;

		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		if(params.acc_year_id=='' || params.acc_year_id==0){
			alert('Select Year');
			return;
		}
		
		let d= axios.get(msApp.baseUrl()+"/gl/html",{params})
		.then(function (response) {
			$('#tbglcontainer').html(response.data);
			$('#tbglWindow').window('open')
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}

	pdfGl(coa_id){
		let company_id = $('#tbFrm  [name=company_id]').val();
		let acc_year_id = $('#tbFrm  [name=acc_year_id]').val();
		//let date_from = $('#tbFrm  [name=date_from]').val();
		let date_to = $('#tbFrm  [name=date_to]').val();
		let code_from = $('#tbFrm  [name=code_from]').val();
		let code_to = $('#tbFrm  [name=code_to]').val();

		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/gl/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to+'&coa_id='+coa_id);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getYear(company_id){
		let params={};
		params.company_id=company_id;
		let d= axios.get(msApp.baseUrl()+"/tb/getYear",{params})
		.then(function (response) {
			MsTb.setYear(response.data);
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
				$('#tbFrm  [name=acc_year_id]').val(value.id);
				$('#tbFrm  [name=date_from]').val(value.start_date);
				//$('#tbFrm  [name=date_to]').val(value.end_date);
			}
		});
	}



	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/tb/getDateRange",{params})
		.then(function (response) {
			MsTb.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#tbFrm  [name=date_from]').val(value.start_date);
			//$('#glFrm  [name=date_to]').val(value.end_date);
		});
	}

	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#tbFrm  [name=company_id]').val();
		let acc_year_id = $('#tbFrm  [name=acc_year_id]').val();
		//let date_from = $('#tbFrm  [name=date_from]').val();
		let date_to = $('#tbFrm  [name=date_to]').val();
		let code_from = $('#tbFrm  [name=code_from]').val();
		let code_to = $('#tbFrm  [name=code_to]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/tb/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to);
	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#tbcodefromTbl').datagrid('loadData', response.data);
			$('#tbcodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#tbcodetoTbl').datagrid('loadData', response.data);
			$('#tbcodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#tbcodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#tbFrm  [name=code_from]').val(row.code);
			 $('#tbcodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#tbcodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#tbFrm  [name=code_to]').val(row.code);
			$('#tbcodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsTb=new MsTbController(new MsTbModel());
MsTb.showGrid([]);
MsTb.showcodefromGrid([]);
MsTb.showcodetoGrid([]);
