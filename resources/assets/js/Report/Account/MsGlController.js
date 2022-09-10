//require('./../../jquery.easyui.min.js');
let MsGlModel = require('./MsGlModel');
require('./../../datagrid-filter.js');

class MsGlController {
	constructor(MsGlModel)
	{
		this.MsGlModel = MsGlModel;
		this.formId='glFrm';
		this.dataTable='#glTbl';
		this.route=msApp.baseUrl()+"/gl/html";
	}

	get(){
		let params={};
		params.company_id = $('#glFrm  [name=company_id]').val();
		params.acc_year_id = $('#glFrm  [name=acc_year_id]').val();
		params.date_from = $('#glFrm  [name=date_from]').val();
		params.date_to = $('#glFrm  [name=date_to]').val();
		params.code_from = $('#glFrm  [name=code_from]').val();
		params.code_to = $('#glFrm  [name=code_to]').val();

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
			$('#glcontainer').html(response.data);
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
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getYear(company_id){
		let params={};
		params.company_id=company_id;
		let d= axios.get(msApp.baseUrl()+"/gl/getYear",{params})
		.then(function (response) {
			MsGl.setYear(response.data);
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
				$('#glFrm  [name=acc_year_id]').val(value.id);
				$('#glFrm  [name=date_from]').val(value.start_date);
				$('#glFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/gl/getDateRange",{params})
		.then(function (response) {
			MsGl.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#glFrm  [name=date_from]').val(value.start_date);
			$('#glFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glFrm  [name=company_id]').val();
		let acc_year_id = $('#glFrm  [name=acc_year_id]').val();
		let date_from = $('#glFrm  [name=date_from]').val();
		let date_to = $('#glFrm  [name=date_to]').val();
		let code_from = $('#glFrm  [name=code_from]').val();
		let code_to = $('#glFrm  [name=code_to]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/gl/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to);
	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glcodefromTbl').datagrid('loadData', response.data);
			$('#glcodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glcodetoTbl').datagrid('loadData', response.data);
			$('#glcodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glcodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glFrm  [name=code_from]').val(row.code);
			 $('#glcodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glcodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glFrm  [name=code_to]').val(row.code);
			$('#glcodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsGl=new MsGlController(new MsGlModel());
MsGl.showGrid([]);
MsGl.showcodefromGrid([]);
MsGl.showcodetoGrid([]);
