//require('./../../jquery.easyui.min.js');
let MsGlOpnModel = require('./MsGlOpnModel');
require('./../../datagrid-filter.js');

class MsGlOpnController {
	constructor(MsGlOpnModel)
	{
		this.MsGlOpnModel = MsGlOpnModel;
		this.formId='glopnFrm';
		this.dataTable='#glopnTbl';
		this.route=msApp.baseUrl()+"/glopn/html";
	}

	get(){
		let params={};
		params.company_id = $('#glopnFrm  [name=company_id]').val();
		params.acc_year_id = $('#glopnFrm  [name=acc_year_id]').val();
		params.date_from = $('#glopnFrm  [name=date_from]').val();
		params.date_to = $('#glopnFrm  [name=date_to]').val();
		params.code_from = $('#glopnFrm  [name=code_from]').val();
		params.code_to = $('#glopnFrm  [name=code_to]').val();

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
			$('#glopncontainer').html(response.data);
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
		let d= axios.get(msApp.baseUrl()+"/glopn/getYear",{params})
		.then(function (response) {
			MsGlOpn.setYear(response.data);
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
				$('#glopnFrm  [name=acc_year_id]').val(value.id);
				$('#glopnFrm  [name=date_from]').val(value.start_date);
				$('#glopnFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/glopn/getDateRange",{params})
		.then(function (response) {
			MsGlOpn.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#glopnFrm  [name=date_from]').val(value.start_date);
			$('#glopnFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glopnFrm  [name=company_id]').val();
		let acc_year_id = $('#glopnFrm  [name=acc_year_id]').val();
		let date_from = $('#glopnFrm  [name=date_from]').val();
		let date_to = $('#glopnFrm  [name=date_to]').val();
		let code_from = $('#glopnFrm  [name=code_from]').val();
		let code_to = $('#glopnFrm  [name=code_to]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/glopn/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to);
	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/glopn/getcode");
		data.then(function (response) {
			$('#glopncodefromTbl').datagrid('loadData', response.data);
			$('#glopncodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/glopn/getcode");
		data.then(function (response) {
			$('#glopncodetoTbl').datagrid('loadData', response.data);
			$('#glopncodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glopncodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glopnFrm  [name=code_from]').val(row.code);
			 $('#glopncodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glopncodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glopnFrm  [name=code_to]').val(row.code);
			$('#glopncodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsGlOpn=new MsGlOpnController(new MsGlOpnModel());
MsGlOpn.showGrid([]);
MsGlOpn.showcodefromGrid([]);
MsGlOpn.showcodetoGrid([]);
